<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use LogicException;

#[Fillable([
    'order_number',
    'user_id',
    'customer_name',
    'customer_email',
    'total',
    'status',
    'payment_method',
    'payment_reference',
    'payment_account_name',
    'payment_reviewed_at',
    'payment_reviewed_by',
    'rejection_reason',
])]
class Order extends Model
{
    /** Payment submitted and waiting for an admin to review it. */
    public const STATUS_PENDING = 'pending';

    /** Payment approved by an admin. Downloads are unlocked. */
    public const STATUS_COMPLETED = 'completed';

    /** Payment rejected by an admin. Reserved artworks are released. */
    public const STATUS_FAILED = 'failed';

    /** Approved payment later refunded. Artworks return to the catalog. */
    public const STATUS_REFUNDED = 'refunded';

    /**
     * Status changes an admin may make, keyed by the current status.
     *
     * @var array<string, list<string>>
     */
    public const ALLOWED_TRANSITIONS = [
        self::STATUS_PENDING => [self::STATUS_COMPLETED, self::STATUS_FAILED],
        self::STATUS_COMPLETED => [self::STATUS_REFUNDED],
        self::STATUS_FAILED => [],
        self::STATUS_REFUNDED => [],
    ];

    /**
     * @var array<string, string>
     */
    public const STATUS_LABELS = [
        self::STATUS_PENDING => 'Awaiting approval',
        self::STATUS_COMPLETED => 'Payment approved',
        self::STATUS_FAILED => 'Payment rejected',
        self::STATUS_REFUNDED => 'Refunded',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payment_reviewed_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isAwaitingApproval(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst($this->status);
    }

    public function canTransitionTo(string $status): bool
    {
        return in_array($status, self::ALLOWED_TRANSITIONS[$this->status] ?? [], true);
    }

    /**
     * Move the order to a new payment status and keep its artworks in step:
     * approval marks them sold, rejection or refund puts them back on sale.
     */
    public function transitionTo(string $status, User $reviewer, ?string $rejectionReason = null): void
    {
        DB::transaction(function () use ($status, $reviewer, $rejectionReason): void {
            /** @var Order $lockedOrder */
            $lockedOrder = self::query()->lockForUpdate()->findOrFail($this->id);

            if (! $lockedOrder->canTransitionTo($status)) {
                throw new LogicException("Order {$lockedOrder->order_number} cannot move from {$lockedOrder->status} to {$status}.");
            }

            $artworkIds = $lockedOrder->items()->whereNotNull('artwork_id')->pluck('artwork_id');

            match ($status) {
                self::STATUS_COMPLETED => Artwork::query()
                    ->whereIn('id', $artworkIds)
                    ->update(['availability' => Artwork::AVAILABILITY_SOLD, 'sold_at' => now()]),
                self::STATUS_FAILED, self::STATUS_REFUNDED => Artwork::query()
                    ->whereIn('id', $artworkIds)
                    ->update(['availability' => Artwork::AVAILABILITY_AVAILABLE, 'sold_at' => null]),
                default => null,
            };

            $lockedOrder->update([
                'status' => $status,
                'payment_reviewed_at' => now(),
                'payment_reviewed_by' => $reviewer->id,
                'rejection_reason' => $status === self::STATUS_FAILED ? $rejectionReason : $lockedOrder->rejection_reason,
            ]);

            $this->setRawAttributes($lockedOrder->getAttributes(), true);
        });
    }

    protected function casts(): array
    {
        return [
            'payment_reviewed_at' => 'datetime',
        ];
    }
}
