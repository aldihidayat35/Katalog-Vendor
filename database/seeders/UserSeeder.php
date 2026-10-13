<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator Katalog',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'phone' => '081234567890',
                'instagram' => '@katalog.admin',
                'address' => 'Studio Katalog Undangan, Jl. Senopati No. 88, Jakarta Selatan',
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 2. Demo Member 1
        User::updateOrCreate(
            ['email' => 'budi@example.com'],
            [
                'name' => 'Budi Pratama',
                'username' => 'budi_pratama',
                'password' => Hash::make('password'),
                'phone' => '081298765431',
                'instagram' => '@budipratama',
                'address' => 'Jl. Melati No. 12, RT 02/05, Gandaria Selatan, Jakarta Selatan',
                'role' => 'member',
                'email_verified_at' => now(),
            ]
        );

        // 3. Demo Member 2
        User::updateOrCreate(
            ['email' => 'siti@example.com'],
            [
                'name' => 'Siti Rahmawati',
                'username' => 'siti_rahma',
                'password' => Hash::make('password'),
                'phone' => '081345678912',
                'instagram' => '@sitirahma',
                'address' => 'Jl. Anggrek No. 45, Coblong, Kota Bandung, Jawa Barat',
                'role' => 'member',
                'email_verified_at' => now(),
            ]
        );

        // 4. Demo Member 3
        User::updateOrCreate(
            ['email' => 'dimas@example.com'],
            [
                'name' => 'Dimas Wicaksono',
                'username' => 'dimas_wicak',
                'password' => Hash::make('password'),
                'phone' => '081567890123',
                'instagram' => '@dimaswicak',
                'address' => 'Jl. Pahlawan No. 88, Tegalsari, Kota Surabaya, Jawa Timur',
                'role' => 'member',
                'email_verified_at' => now(),
            ]
        );
    }
}
