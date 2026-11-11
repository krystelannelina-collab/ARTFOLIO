<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'username', 'email', 'password', 'role', 'favorites_seeded_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function favoriteArtworks(): BelongsToMany
    {
        return $this->belongsToMany(Artwork::class, 'favorites')
            ->withTimestamps()
            ->orderByPivot('id', 'asc');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Orders whose payment an admin has reviewed (approved, rejected, or
     * refunded), newest review first. These are the user's notifications.
     */
    public function orderUpdates(): HasMany
    {
        return $this->orders()
            ->whereNotNull('payment_reviewed_at')
            ->latest('payment_reviewed_at');
    }

    public function unseenOrderUpdatesCount(): int
    {
        return $this->orderUpdates()
            ->when(
                $this->order_updates_seen_at !== null,
                fn (Builder $query): Builder => $query->where('payment_reviewed_at', '>', $this->order_updates_seen_at),
            )
            ->count();
    }

    /**
     * Up to two initials from the user's name, such as "JD" for "Jane Doe".
     */
    public function initials(): string
    {
        $initials = collect(preg_split('/\s+/', trim($this->name)) ?: [])
            ->filter()
            ->take(2)
            ->map(fn (string $part): string => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('');

        return $initials !== '' ? $initials : 'U';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'favorites_seeded_at' => 'datetime',
            'order_updates_seen_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
