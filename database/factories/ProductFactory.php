<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'category_id' => Category::factory(),
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'short_description' => fake()->sentence(),
            'description' => "Isi Paket:\n• 1 Kartu Utama\n• 1 Amplop",
            'specifications' => "Bahan:\n• Jasmine Paper 210 gsm",
            'type' => 'printed',
            'price' => fake()->numberBetween(3000, 15000),
            'discount_price' => null,
            'dp_price' => 150000,
            'min_order' => 100,
            'badge' => null,
            'stock_status' => 'ready',
            'is_featured' => false,
            'is_active' => true,
        ];
    }
}
