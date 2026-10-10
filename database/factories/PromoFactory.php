<?php

namespace Database\Factories;

use App\Models\Promo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Promo>
 */
class PromoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => strtoupper(Str::random(8)),
            'name' => fake()->words(2, true),
            'discount_type' => 'fixed',
            'discount_amount' => 50000,
            'min_purchase' => 500000,
            'max_discount' => null,
            'usage_limit' => 100,
            'used_count' => 0,
            'start_date' => Carbon::now()->subDays(5),
            'end_date' => Carbon::now()->addDays(30),
            'is_active' => true,
        ];
    }
}
