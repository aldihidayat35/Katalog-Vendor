<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'short_description',
        'description',
        'specifications',
        'type',
        'price',
        'discount_price',
        'dp_price',
        'min_order',
        'badge',
        'stock_status',
        'is_featured',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'discount_price' => 'integer',
            'dp_price' => 'integer',
            'min_order' => 'integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Accessor: final_price
     */
    public function getFinalPriceAttribute(): int
    {
        if ($this->has_discount) {
            return (int) $this->discount_price;
        }

        return (int) $this->price;
    }

    /**
     * Accessor: has_discount
     */
    public function getHasDiscountAttribute(): bool
    {
        return !is_null($this->discount_price) && $this->discount_price > 0 && $this->discount_price < $this->price;
    }

    /**
     * Accessor: discount_percentage
     */
    public function getDiscountPercentageAttribute(): int
    {
        if ($this->has_discount && $this->price > 0) {
            return (int) round((($this->price - $this->discount_price) / $this->price) * 100);
        }

        return 0;
    }

    /**
     * Accessor: dp_price
     */
    public function getDpPriceAttribute(): int
    {
        if (isset($this->attributes['dp_price']) && $this->attributes['dp_price'] > 0) {
            return (int) $this->attributes['dp_price'];
        }

        $dpPercentage = (float) setting('dp_percentage', 50);
        return (int) round(($this->final_price * $dpPercentage) / 100);
    }

    /**
     * Accessor: dp_amount (alias for dp_price)
     */
    public function getDpAmountAttribute(): int
    {
        return $this->dp_price;
    }

    /**
     * Accessor: primary_image_url
     */
    public function getPrimaryImageUrlAttribute(): string
    {
        $primary = $this->images->firstWhere('is_primary', true) ?? $this->images->first();

        if ($primary && $primary->image_path) {
            return asset('storage/' . $primary->image_path);
        }

        return asset('images/placeholder-product.svg');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['q'] ?? null, function ($query, $q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('short_description', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        });

        $query->when($filters['kategori'] ?? $filters['category'] ?? null, function ($query, $cat) {
            $query->whereHas('category', function ($sub) use ($cat) {
                $sub->where('slug', $cat)->where('is_active', true);
            });
        });

        $query->when($filters['tipe'] ?? $filters['type'] ?? null, function ($query, $type) {
            $query->where('type', $type);
        });

        $sort = $filters['sort'] ?? 'terbaru';
        return match ($sort) {
            'harga_terendah' => $query->orderByRaw('CASE WHEN discount_price > 0 AND discount_price IS NOT NULL THEN discount_price ELSE price END ASC'),
            'harga_tertinggi' => $query->orderByRaw('CASE WHEN discount_price > 0 AND discount_price IS NOT NULL THEN discount_price ELSE price END DESC'),
            default => $query->latest('id'),
        };
    }
}
