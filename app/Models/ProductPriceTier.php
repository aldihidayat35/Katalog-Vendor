<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPriceTier extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'min_quantity',
        'max_quantity',
        'unit_price',
    ];

    protected $appends = [
        'range_label',
    ];

    protected function casts(): array
    {
        return [
            'product_id' => 'integer',
            'min_quantity' => 'integer',
            'max_quantity' => 'integer',
            'unit_price' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Check if a given quantity falls into this tier.
     */
    public function matchesQuantity(int $quantity): bool
    {
        if ($quantity < $this->min_quantity) {
            return false;
        }

        if ($this->max_quantity !== null && $quantity > $this->max_quantity) {
            return false;
        }

        return true;
    }

    /**
     * Get human-readable quantity range label.
     */
    public function getRangeLabelAttribute(): string
    {
        if ($this->max_quantity === null) {
            return "≥ {$this->min_quantity} pcs";
        }

        if ($this->min_quantity === $this->max_quantity) {
            return "{$this->min_quantity} pcs";
        }

        return "{$this->min_quantity} – {$this->max_quantity} pcs";
    }
}
