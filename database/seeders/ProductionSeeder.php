<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProductionSeeder extends Seeder
{
    /**
     * Run production seeds: Initial Admin user, System Settings, and Product Categories.
     * Does NOT include demo products, demo users, or dummy orders.
     */
    public function run(): void
    {
        // 1. Administrator Account for Production
        $adminEmail = env('ADMIN_DEFAULT_EMAIL', 'admin@katalogundangan.com');
        $adminPassword = env('ADMIN_DEFAULT_PASSWORD', 'AdminSecure2026!');

        User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Administrator',
                'password' => Hash::make($adminPassword),
                'phone' => env('WHATSAPP_ADMIN_NUMBER', '6281234567890'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 2. Default System Settings
        $this->call(SettingSeeder::class);

        // 3. Base Product Categories
        $this->call(CategorySeeder::class);
    }
}
