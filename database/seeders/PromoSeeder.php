<?php

namespace Database\Seeders;

use App\Models\Promo;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Promo::updateOrCreate(
            ['code' => 'DISKON25'],
            [
                'name' => 'Promo Diskon Pernikahan 25%',
                'discount_type' => 'percent',
                'discount_amount' => 25,
                'min_purchase' => 500000,
                'max_discount' => 250000,
                'usage_limit' => 100,
                'used_count' => 5,
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->addMonths(3),
                'is_active' => true,
            ]
        );

        Promo::updateOrCreate(
            ['code' => 'HEMAT50K'],
            [
                'name' => 'Potongan Langsung Hemat 50 Ribu',
                'discount_type' => 'fixed',
                'discount_amount' => 50000,
                'min_purchase' => 300000,
                'max_discount' => null,
                'usage_limit' => 200,
                'used_count' => 12,
                'start_date' => Carbon::now()->subDays(15),
                'end_date' => Carbon::now()->addMonths(2),
                'is_active' => true,
            ]
        );
    }
}
