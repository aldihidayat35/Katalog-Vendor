<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'avatar_url',
        'password',
        'phone',
        'instagram',
        'address',
        'role',
        'is_active',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Determine if the user is an administrator.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Determine if the user account is active.
     */
    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }

    /**
     * Determine if the user account is suspended.
     */
    public function isSuspended(): bool
    {
        return ! $this->isActive();
    }

    /**
     * Get the cart items for the user.
     */
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get the orders for the user.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Generate a unique username from an email address or name.
     */
    public static function generateUniqueUsername(string $email, ?string $name = null): string
    {
        $base = strstr($email, '@', true) ?: ($name ? Str::slug($name, '_') : 'user');
        $base = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', $base)) ?: 'user';

        $candidate = $base;
        $attempt = 1;

        while (static::where('username', $candidate)->exists()) {
            $candidate = $base.'_'.mt_rand(10, 9999);
            $attempt++;
            if ($attempt > 50) {
                $candidate = $base.'_'.substr(uniqid(), -4);
                break;
            }
        }

        return $candidate;
    }

    /**
     * Get the avatar image url or fallback.
     */
    public function getDisplayAvatarUrl(): ?string
    {
        return $this->avatar_url;
    }
}
