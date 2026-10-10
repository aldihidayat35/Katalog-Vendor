<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'discount_type',
        'discount_amount',
        'min_purchase',
        'max_discount',
        'usage_limit',
        'used_count',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'discount_amount' => 'integer',
            'min_purchase' => 'integer',
            'max_discount' => 'integer',
            'usage_limit' => 'integer',
            'used_count' => 'integer',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    protected function code(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => strtoupper(trim($value)),
        );
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isValidForAmount(int $amount): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $now = Carbon::now();
        if ($now->lt($this->start_date) || $now->gt($this->end_date)) {
            return false;
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        if ($this->min_purchase !== null && $amount < $this->min_purchase) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(int $amount): int
    {
        if (! $this->isValidForAmount($amount)) {
            return 0;
        }

        if ($this->discount_type === 'percent') {
            $discount = (int) round(($amount * $this->discount_amount) / 100);
            if ($this->max_discount !== null && $discount > $this->max_discount) {
                return $this->max_discount;
            }
            return $discount;
        }

        return min((int) $this->discount_amount, $amount);
    }
}
