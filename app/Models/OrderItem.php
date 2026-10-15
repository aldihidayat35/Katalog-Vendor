<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'unit_price',
        'quantity',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'integer',
            'quantity' => 'integer',
            'subtotal' => 'integer',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Determine if this order item was purchased with wholesale pricing.
     */
    public function isWholesale(): bool
    {
        if ($this->relationLoaded('product') && $this->product) {
            return $this->product->isWholesaleForQuantity($this->quantity);
        }

        if ($this->product_id) {
            return $this->product?->isWholesaleForQuantity($this->quantity) ?? false;
        }

        return false;
    }
}
