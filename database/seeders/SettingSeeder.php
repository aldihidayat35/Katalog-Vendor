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
            'site_name' => 'Undangin',
            'site_tagline' => 'Katalog Undangan Cetak & Digital Eksklusif',
            'contact_email' => 'halo@katalogundangan.com',
            'instagram_url' => 'https://instagram.com/galeria.invitation',
            'hero_pill_text' => '✦ Katalog Undangan Cetak & Digital Eksklusif',
            'hero_title' => 'Rayakan Setiap Cerita',
            'hero_subtitle_italic' => 'dengan Undangan yang Berkesan',
            'hero_subtitle' => 'Pilihan lengkap undangan cetak fisik berkualitas tinggi hingga undangan digital interaktif yang elegan untuk setiap momen spesial Anda.',
            'cta_title' => 'Wujudkan Undangan Impian Anda',
            'cta_subtitle' => 'Pilih desain favorit Anda, dari paket cetak fisik eksklusif hingga undangan digital modern.',
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
            'consultation_message' => 'Halo Admin Galeria Invitation, saya tertarik untuk konsultasi pemesanan undangan (cetak / digital). Mohon informasi katalog dan ketersediaan jadwal ya.',
            'popup_enabled' => '1',
            'popup_title' => 'Promo Spesial Musim Undangan',
            'popup_image' => 'images/landing/promo-poster.svg',
            'popup_link_type' => 'catalog',
            'popup_custom_url' => '',
            'popup_whatsapp_message' => 'Halo Admin Galeria Invitation, saya melihat promo spesial di website dan ingin konsultasi promo ini.',
            'popup_delay_seconds' => '1.5',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }
    }
}
