<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'slug',
    'title',
    'artist_name',
    'category',
    'price',
    'image_path',
    'description',
    'status',
    'original_path',
    'image_width',
    'image_height',
    'file_format',
    'file_size',
    'canvas_size',
])]
class Artwork extends Model
{
    public const AVAILABILITY_AVAILABLE = 'available';

    public const AVAILABILITY_RESERVED = 'reserved';

    public const AVAILABILITY_SOLD = 'sold';

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'availability' => self::AVAILABILITY_AVAILABLE,
    ];

    /**
     * Keep the foreign keys in step with the category and artist names, so
     * every save links the artwork to the matching category and artist rows.
     */
    protected static function booted(): void
    {
        static::saving(function (Artwork $artwork): void {
            if ($artwork->isDirty('category') || $artwork->category_id === null) {
                $artwork->category_id = Category::query()
                    ->where('name', $artwork->category)
                    ->value('id');
            }

            if ($artwork->isDirty('artist_name') || $artwork->artist_id === null) {
                $artwork->artist_id = Artist::query()
                    ->whereRaw('LOWER(name) = ?', [mb_strtolower(trim((string) $artwork->artist_name))])
                    ->value('id');
            }
        });
    }

    /**
     * The category row this artwork belongs to. Named catalogCategory because
     * "category" is the display-name column.
     */
    public function catalogCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }

    /**
     * Customer-facing catalog number, such as "AW-0007".
     */
    public function catalogNumber(): string
    {
        return 'AW-'.str_pad((string) $this->id, 4, '0', STR_PAD_LEFT);
    }

    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Published artworks whose category is visible to clients.
     *
     * @param  Builder<Artwork>  $query
     */
    public function scopeVisibleToClients(Builder $query): void
    {
        $query->where('status', 'published')
            ->whereNotIn('category', Category::query()->where('is_visible', false)->select('name'));
    }

    /**
     * Artworks that nobody has bought or reserved yet.
     *
     * @param  Builder<Artwork>  $query
     */
    public function scopeAvailableForPurchase(Builder $query): void
    {
        $query->where('availability', self::AVAILABILITY_AVAILABLE);
    }

    public function isAvailableForPurchase(): bool
    {
        return $this->availability === self::AVAILABILITY_AVAILABLE;
    }

    public function isSold(): bool
    {
        return $this->availability === self::AVAILABILITY_SOLD;
    }

    public function isPurchasedBy(?User $user): bool
    {
        if ($user === null) {
            return false;
        }

        return $this->orderItems()
            ->whereHas('order', fn (Builder $order): Builder => $order
                ->where('user_id', $user->id)
                ->where('status', Order::STATUS_COMPLETED))
            ->exists();
    }

    /**
     * Width-to-height ratio, such as "16:9". Sizes that do not reduce to
     * small whole numbers are shown as a decimal instead, such as "2.97:1".
     */
    public function aspectRatio(): ?string
    {
        if (! $this->image_width || ! $this->image_height) {
            return null;
        }

        $divisor = $this->greatestCommonDivisor($this->image_width, $this->image_height);
        $width = intdiv($this->image_width, $divisor);
        $height = intdiv($this->image_height, $divisor);

        if (max($width, $height) <= 32) {
            return "{$width}:{$height}";
        }

        return $this->image_width >= $this->image_height
            ? round($this->image_width / $this->image_height, 2).':1'
            : '1:'.round($this->image_height / $this->image_width, 2);
    }

    public function readableFileSize(): ?string
    {
        if (! $this->file_size) {
            return null;
        }

        if ($this->file_size >= 1048576) {
            return number_format($this->file_size / 1048576, 1).' MB';
        }

        return number_format($this->file_size / 1024, 0).' KB';
    }

    protected function casts(): array
    {
        return [
            'sold_at' => 'datetime',
            'image_width' => 'integer',
            'image_height' => 'integer',
            'file_size' => 'integer',
        ];
    }

    private function greatestCommonDivisor(int $first, int $second): int
    {
        while ($second !== 0) {
            [$first, $second] = [$second, $first % $second];
        }

        return max($first, 1);
    }
}
