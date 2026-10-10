<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Bronze',
                'slug' => 'bronze',
                'description' => 'Paket undangan hemat berkualitas dengan konsep minimalis, modern, dan tetap berkesan anggun.',
                'image_path' => null,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Silver',
                'slug' => 'silver',
                'description' => 'Koleksi favorit calon pengantin dengan sentuhan foil emas, amplop kustom, dan kertas tekstur impor.',
                'image_path' => null,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Gold',
                'slug' => 'gold',
                'description' => 'Paket eksklusif berornamen wax seal, pita sutra, pocket amplop, dan aksen foil timbul (emboss).',
                'image_path' => null,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Diamond',
                'slug' => 'diamond',
                'description' => 'Kasta tertinggi undangan mewah dengan hard cover premium, beludru velvet, laser cut, dan akrilik kristal.',
                'image_path' => null,
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
