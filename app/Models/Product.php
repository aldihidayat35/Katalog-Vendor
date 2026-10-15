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

    public const THEME_COLORS = [
        'gold' => [
            'name' => 'Gold',
            'label' => 'Gold Luxury',
            'hex' => '#D4AF37',
        ],
        'sage' => [
            'name' => 'Sage Green',
            'label' => 'Sage Green Natural',
            'hex' => '#8A9A86',
        ],
        'rose-gold' => [
            'name' => 'Rose Gold',
            'label' => 'Rose Gold Romantis',
            'hex' => '#B76E79',
        ],
        'emerald' => [
            'name' => 'Emerald Green',
            'label' => 'Hijau Zamrud Mewah',
            'hex' => '#1B4D3E',
        ],
        'burgundy' => [
            'name' => 'Burgundy / Maroon',
            'label' => 'Maroon Elegan',
            'hex' => '#800020',
        ],
        'navy' => [
            'name' => 'Royal Navy',
            'label' => 'Biru Navy Abadi',
            'hex' => '#1B263B',
        ],
        'terracotta' => [
            'name' => 'Terracotta',
            'label' => 'Terracotta Earthy',
            'hex' => '#E2725B',
        ],
        'champagne' => [
            'name' => 'Champagne',
            'label' => 'Champagne Glamour',
            'hex' => '#C5A880',
        ],
        'dusty-blue' => [
            'name' => 'Dusty Blue',
            'label' => 'Dusty Blue Pastel',
            'hex' => '#7A899C',
        ],
        'lilac' => [
            'name' => 'Lilac / Lavender',
            'label' => 'Ungu Lilac Lembut',
            'hex' => '#B59EC9',
        ],
        'monochrome' => [
            'name' => 'Hitam / Monokrom',
            'label' => 'Hitam Modern',
            'hex' => '#2B2B2B',
        ],
        'white' => [
            'name' => 'Putih / Pearl',
            'label' => 'Putih Mutiara',
            'hex' => '#F4F4F4',
        ],
    ];

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'short_description',
        'description',
        'specifications',
        'type',
        'theme_color',
        'price',
        'discount_price',
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

    public function priceTiers(): HasMany
    {
        return $this->hasMany(ProductPriceTier::class)->orderBy('min_quantity');
    }

    /**
     * Check if product has any wholesale price tiers.
     */
    public function hasPriceTiers(): bool
    {
        if ($this->relationLoaded('priceTiers')) {
            return $this->priceTiers->isNotEmpty();
        }

        return $this->priceTiers()->exists();
    }

    /**
     * Get the active tier for a specific quantity.
     */
    public function getTierForQuantity(int $quantity): ?ProductPriceTier
    {
        $tiers = $this->relationLoaded('priceTiers')
            ? $this->priceTiers
            : $this->priceTiers()->get();

        if ($tiers->isEmpty()) {
            return null;
        }

        $matched = $tiers->first(fn (ProductPriceTier $tier) => $tier->matchesQuantity($quantity));
        if ($matched) {
            return $matched;
        }

        // If quantity is higher than any max_quantity, resolve to the tier with highest min_quantity
        $highestTier = $tiers->sortByDesc('min_quantity')->first();
        if ($highestTier && $quantity >= $highestTier->min_quantity) {
            return $highestTier;
        }

        return null;
    }

    /**
     * Determine unit price based on quantity (wholesale tier or fallback to final_price).
     */
    public function getUnitPriceForQuantity(int $quantity): int
    {
        $tier = $this->getTierForQuantity($quantity);

        if ($tier) {
            return (int) $tier->unit_price;
        }

        return (int) $this->final_price;
    }

    /**
     * Check if a quantity triggers a wholesale discount tier.
     */
    public function isWholesaleForQuantity(int $quantity): bool
    {
        $tier = $this->getTierForQuantity($quantity);

        return $tier !== null && $tier->unit_price < $this->final_price;
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
        return ! is_null($this->discount_price) && $this->discount_price > 0 && $this->discount_price < $this->price;
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
     * Accessor: primary_image_url
     */
    public function getPrimaryImageUrlAttribute(): string
    {
        $primary = $this->images->firstWhere('is_primary', true) ?? $this->images->first();

        if ($primary && $primary->image_path) {
            if (str_starts_with($primary->image_path, 'http') || str_starts_with($primary->image_path, 'data:')) {
                return $primary->image_path;
            }
            if (file_exists(public_path($primary->image_path))) {
                return asset($primary->image_path);
            }

            return asset('storage/'.$primary->image_path);
        }

        return asset('images/placeholder-product.svg');
    }

    /**
     * Accessor: theme_color_data
     */
    public function getThemeColorDataAttribute(): ?array
    {
        if (empty($this->theme_color)) {
            return null;
        }

        return self::THEME_COLORS[$this->theme_color] ?? [
            'name' => ucfirst($this->theme_color),
            'label' => ucfirst($this->theme_color),
            'hex' => '#C5A880',
        ];
    }

    /**
     * Accessor: theme_color_label
     */
    public function getThemeColorLabelAttribute(): ?string
    {
        return $this->theme_color_data['name'] ?? null;
    }

    /**
     * Accessor: theme_color_hex
     */
    public function getThemeColorHexAttribute(): ?string
    {
        return $this->theme_color_data['hex'] ?? null;
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

        $query->when($filters['warna'] ?? $filters['color'] ?? null, function ($query, $color) {
            $query->where('theme_color', $color);
        });

        $sort = $filters['sort'] ?? 'terbaru';

        return match ($sort) {
            'harga_terendah' => $query->orderByRaw('CASE WHEN discount_price > 0 AND discount_price IS NOT NULL THEN discount_price ELSE price END ASC'),
            'harga_tertinggi' => $query->orderByRaw('CASE WHEN discount_price > 0 AND discount_price IS NOT NULL THEN discount_price ELSE price END DESC'),
            default => $query->latest('id'),
        };
    }
}
