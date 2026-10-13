<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_path',
        'is_primary',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getUrlAttribute(): string
    {
        if ($this->image_path) {
            if (str_starts_with($this->image_path, 'http') || str_starts_with($this->image_path, 'data:')) {
                return $this->image_path;
            }
            if (file_exists(public_path($this->image_path))) {
                return asset($this->image_path);
            }

            return asset('storage/'.$this->image_path);
        }

        return asset('images/placeholder-product.svg');
    }
}
