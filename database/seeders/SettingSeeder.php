<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'whatsapp_number' => '6281234567890',
            'site_name' => 'Katalog Undangan',
            'site_tagline' => 'Spesialis Undangan Pernikahan Cetak & Digital Eksklusif',
            'contact_email' => 'halo@katalogundangan.com',
            'instagram_url' => 'https://instagram.com/galeria.invitation',
            'shipping_methods' => json_encode([
                [
                    'id' => 'jne',
                    'name' => 'JNE Reguler / YES',
                    'desc' => 'Pengiriman ekspedisi terpercaya ke seluruh pelosok Indonesia.',
                ],
                [
                    'id' => 'jnt',
                    'name' => 'J&T Express',
                    'desc' => 'Layanan cepat pick-up dan tracking pengiriman akurat.',
                ],
                [
                    'id' => 'instant',
                    'name' => 'Gojek / Grab (Instan)',
                    'desc' => 'Khusus wilayah Jabodetabek saat pesanan cetak telah selesai.',
                ],
                [
                    'id' => 'pickup',
                    'name' => 'Ambil di Tempat (Self-Pickup)',
                    'desc' => 'Ambil langsung di workshop Galeria Invitation sesuai jadwal.',
                ],
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            'consultation_message' => 'Halo Admin Galeria Invitation, saya tertarik untuk konsultasi desain undangan pernikahan saya. Mohon informasi katalog dan ketersediaan jadwal ya.',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }
    }
}
