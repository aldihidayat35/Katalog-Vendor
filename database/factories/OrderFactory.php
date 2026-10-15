<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_number' => 'INV-'.date('Ymd').'-'.sprintf('%04d', fake()->unique()->numberBetween(1, 9999)),
            'user_id' => User::factory(),
            'customer_name' => fake()->name(),
            'customer_email' => fake()->safeEmail(),
            'customer_phone' => '0812'.fake()->numerify('########'),
            'instagram_account' => fake()->userName(),
            'event_date' => fake()->dateTimeBetween('+1 week', '+6 months'),
            'shipping_address' => fake()->address(),
            'shipping_method' => 'JNE Reguler / YES',
            'promo_id' => null,
            'promo_code' => null,
            'subtotal' => 1500000,
            'discount_total' => 0,
            'total_price' => 1500000,
            'notes' => fake()->sentence(),
            'status' => 'lead',
            'whatsapp_message' => 'Halo Admin...',
        ];
    }
}
