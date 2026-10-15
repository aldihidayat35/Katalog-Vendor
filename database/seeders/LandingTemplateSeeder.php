<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class LandingTemplateSeeder extends Seeder
{
    public function run(): void
    {
        LandingImageSaver::extractImages();

        $dir = public_path('images/landing');
        if (! File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        // Set default settings
        $settings = [
            'site_name' => 'Undangin',
            'site_tagline' => 'Undangan Digital untuk Setiap Cerita',
            'hero_bg_image' => 'images/landing/hero-bg.jpg',
            'hero_pill_text' => '✦ Undangan Digital untuk Momen Spesial',
            'hero_title' => 'Rayakan Setiap Cerita',
            'hero_subtitle_italic' => 'dengan Undangan yang Berkesan',
            'hero_subtitle' => 'Pilih dari ratusan template undangan digital yang elegan, modern, dan mudah disesuaikan untuk setiap momen spesial Anda.',
            'cta_bg_image' => 'images/landing/cta-bg.jpg',
            'cta_title' => 'Wujudkan Undangan Impian Anda',
            'cta_subtitle' => 'Mulai dari template favorit dan buat undangan digital yang tak terlupakan.',
        ];

        foreach ($settings as $key => $val) {
            Setting::set($key, $val);
        }

        // Categories
        $categoriesData = [
            ['name' => 'Pernikahan', 'slug' => 'pernikahan', 'description' => 'Desain undangan pernikahan digital & fisik yang anggun dan berkesan.', 'sort_order' => 10],
            ['name' => 'Ulang Tahun', 'slug' => 'ulang-tahun', 'description' => 'Template ulang tahun ceria, modern, dan penuh warna.', 'sort_order' => 11],
            ['name' => 'Aqiqah', 'slug' => 'aqiqah', 'description' => 'Undangan tasyakuran aqiqah bernuansa islami hangat.', 'sort_order' => 12],
            ['name' => 'Tunangan', 'slug' => 'tunangan', 'description' => 'Desain lamaran dan pertunangan manis bernuansa intimate.', 'sort_order' => 13],
            ['name' => 'Khitanan', 'slug' => 'khitanan', 'description' => 'Undangan walimatul khitan dengan ornamen islami elegan.', 'sort_order' => 14],
            ['name' => 'Wisuda', 'slug' => 'wisuda', 'description' => 'Momen perayaan kelulusan spesial dan prestisius.', 'sort_order' => 15],
            ['name' => 'Acara Lainnya', 'slug' => 'acara-lainnya', 'description' => 'Undangan syukuran, reuni, dan acara keluarga spesial.', 'sort_order' => 16],
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $categories[$c['slug']] = Category::updateOrCreate(
                ['slug' => $c['slug']],
                array_merge($c, ['is_active' => true])
            );
        }

        // Products
        $products = [
            [
                'name' => 'Neraya',
                'slug' => 'neraya',
                'category_slug' => 'pernikahan',
                'short_description' => 'Undangan pernikahan dengan detail floral klasik bernuansa hangat.',
                'description' => 'Template Neraya menghadirkan nuansa floral klasik yang elegan, cocok untuk tema pernikahan indoor maupun garden party modern.',
                'price' => 150000,
                'discount_price' => 129000,
                'type' => 'digital',
                'badge' => 'Terlaris',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Lumina',
                'slug' => 'lumina',
                'category_slug' => 'pernikahan',
                'short_description' => 'Nuansa romantis dengan warna gelap yang elegan dan tipografi anggun.',
                'description' => 'Template Lumina mengusung konsep dark luxury dengan perpaduan tipografi serif tegas dan ornamen emas kontras.',
                'price' => 175000,
                'discount_price' => 149000,
                'type' => 'digital',
                'badge' => 'Pilihan',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Sakura',
                'slug' => 'sakura',
                'category_slug' => 'khitanan',
                'short_description' => 'Tema bunga cerah dan ornamen islami untuk momen khitanan berkah.',
                'description' => 'Template Sakura dirancang khusus untuk acara walimatul khitan dengan palet cerah, bersih, dan ornamen lembut.',
                'price' => 125000,
                'discount_price' => 99000,
                'type' => 'digital',
                'badge' => 'Favorit',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Velora',
                'slug' => 'velora',
                'category_slug' => 'tunangan',
                'short_description' => 'Undangan tunangan dengan ornamen lembut dan sentuhan minimalis.',
                'description' => 'Template Velora bernuansa hangat dan intimate, ideal untuk membagikan kabar bahagia pertunangan kepada keluarga dan kerabat.',
                'price' => 140000,
                'discount_price' => 119000,
                'type' => 'digital',
                'badge' => 'Eksklusif',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Madani',
                'slug' => 'madani',
                'category_slug' => 'aqiqah',
                'short_description' => 'Sentuhan arsitektur islami untuk perayaan aqiqah buah hati.',
                'description' => 'Template Madani menghadirkan kehangatan doa dan syukur kelahiran dengan siluet islami berpadu tipografi modern.',
                'price' => 135000,
                'discount_price' => 109000,
                'type' => 'digital',
                'badge' => 'Islami',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Aluna',
                'slug' => 'aluna',
                'category_slug' => 'pernikahan',
                'short_description' => 'Desain minimalis dengan sentuhan klasik yang elegan.',
                'description' => 'Template Aluna memadukan kesederhanaan modern dengan kemewahan tipografi serif berkelas tinggi.',
                'price' => 160000,
                'discount_price' => 139000,
                'type' => 'digital',
                'badge' => 'Populer',
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Flore',
                'slug' => 'flore',
                'category_slug' => 'pernikahan',
                'short_description' => 'Nuansa floral yang lembut dan romantis untuk hari bahagia Anda.',
                'description' => 'Template Flore menonjolkan ilustrasi botani cat air anggun untuk undangan pernikahan yang tak lekang waktu.',
                'price' => 155000,
                'discount_price' => 129000,
                'type' => 'digital',
                'badge' => 'Baru',
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Savana',
                'slug' => 'savana',
                'category_slug' => 'aqiqah',
                'short_description' => 'Desain modern dengan nuansa islami yang hangat dan ramah keluarga.',
                'description' => 'Template Savana cocok untuk aqiqah anak laki-laki maupun perempuan dengan tata letak informasi doa yang rapi.',
                'price' => 130000,
                'discount_price' => 105000,
                'type' => 'digital',
                'badge' => 'Rekomendasi',
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Aura Party',
                'slug' => 'aura-party',
                'category_slug' => 'ulang-tahun',
                'short_description' => 'Undangan ulang tahun ceria, interaktif, dan penuh keceriaan.',
                'description' => 'Template pesta ulang tahun dengan musik latar, countdown interaktif, dan galeri foto kenangan.',
                'price' => 120000,
                'discount_price' => 95000,
                'type' => 'digital',
                'badge' => 'Ceria',
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Caelum Graduation',
                'slug' => 'caelum-graduation',
                'category_slug' => 'wisuda',
                'short_description' => 'Perayaan wisuda dan syukuran kelulusan berkesan.',
                'description' => 'Bagikan momen kelulusan dengan desain elegan dan tautan streaming acara wisuda.',
                'price' => 125000,
                'discount_price' => 99000,
                'type' => 'digital',
                'badge' => 'Wisuda',
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Serenata Gathering',
                'slug' => 'serenata-gathering',
                'category_slug' => 'acara-lainnya',
                'short_description' => 'Undangan serbaguna untuk reuni, syukuran, dan arisan keluarga.',
                'description' => 'Desain fleksibel yang dapat disesuaikan untuk berbagai acara berkumpul bersama teman dan keluarga.',
                'price' => 115000,
                'discount_price' => 89000,
                'type' => 'digital',
                'badge' => 'Serbaguna',
                'is_featured' => false,
                'is_active' => true,
            ],
        ];

        foreach ($products as $pData) {
            $cat = $categories[$pData['category_slug']] ?? null;
            if (! $cat) {
                continue;
            }

            $product = Product::updateOrCreate(
                ['slug' => $pData['slug']],
                [
                    'category_id' => $cat->id,
                    'name' => $pData['name'],
                    'short_description' => $pData['short_description'],
                    'description' => $pData['description'],
                    'specifications' => "• Format: Undangan Digital Interaktif & Mobile Responsive\n• Fitur: Musik Latar, Hitung Mundur Acara, Navigasi Peta Google, Konfirmasi RSVP WhatsApp\n• Durasi Aktif: Selamanya (Lifetime Access)\n• Waktu Pengerjaan: 1 - 2 hari kerja",
                    'price' => $pData['price'],
                    'discount_price' => $pData['discount_price'],
                    'type' => $pData['type'],
                    'badge' => $pData['badge'],
                    'is_featured' => $pData['is_featured'],
                    'is_active' => $pData['is_active'],
                    'stock_status' => 'ready',
                    'min_order' => 1,
                ]
            );

            // Create image placeholder if not exists
            $imgFilename = "images/landing/templates/{$pData['slug']}.jpg";
            ProductImage::updateOrCreate(
                ['product_id' => $product->id, 'sort_order' => 1],
                [
                    'image_path' => $imgFilename,
                    'is_primary' => true,
                ]
            );
        }
    }
}
