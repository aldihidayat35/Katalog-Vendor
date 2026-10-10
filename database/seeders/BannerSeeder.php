<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $storagePath = storage_path('app/public/banners');
        if (! File::isDirectory($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
        }

        $banners = [
            [
                'title' => 'Koleksi Undangan Eksklusif 2026',
                'subtitle' => 'Sentuhan keanggunan abadi untuk mengawali kisah cinta terindah Anda.',
                'image_filename' => 'banners/banner-1.svg',
                'link_url' => '/catalog',
                'sort_order' => 1,
                'is_active' => true,
                'tag' => 'KOLEKSI TERBARU',
            ],
            [
                'title' => 'Undangan Digital Interaktif Modern',
                'subtitle' => 'Praktis, elegan, dilengkapi RSVP langsung ke WhatsApp & peta navigasi instan.',
                'image_filename' => 'banners/banner-2.svg',
                'link_url' => '/catalog?type=digital',
                'sort_order' => 2,
                'is_active' => true,
                'tag' => 'DIGITAL SUITE',
            ],
            [
                'title' => 'Diskon Spesial Musim Pernikahan 25%',
                'subtitle' => 'Gunakan kode kupon DISKON25 untuk pesanan paket Diamond & Gold pilihan.',
                'image_filename' => 'banners/banner-3.svg',
                'link_url' => '/catalog',
                'sort_order' => 3,
                'is_active' => true,
                'tag' => 'PENAWARAN TERBATAS',
            ],
        ];

        foreach ($banners as $data) {
            $filePath = storage_path("app/public/{$data['image_filename']}");

            if (! File::exists($filePath)) {
                $svg = $this->createBannerSvg($data['title'], $data['subtitle'], $data['tag'], $data['sort_order']);
                File::put($filePath, $svg);
            }

            Banner::updateOrCreate(
                ['title' => $data['title']],
                [
                    'subtitle' => $data['subtitle'],
                    'image_path' => $data['image_filename'],
                    'link_url' => $data['link_url'],
                    'sort_order' => $data['sort_order'],
                    'is_active' => $data['is_active'],
                ]
            );
        }
    }

    private function createBannerSvg(string $title, string $subtitle, string $tag, int $idx): string
    {
        $bgGradients = [
            1 => ['#26201B', '#382E26', '#1E1915'],
            2 => ['#1A2634', '#243447', '#121C26'],
            3 => ['#2F1F1D', '#422C2A', '#1F1413'],
        ];

        $colors = $bgGradients[$idx] ?? $bgGradients[1];
        $escapedTitle = htmlspecialchars($title, ENT_QUOTES);
        $escapedSubtitle = htmlspecialchars($subtitle, ENT_QUOTES);
        $escapedTag = htmlspecialchars($tag, ENT_QUOTES);

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1600 650" width="100%" height="100%">
  <defs>
    <linearGradient id="bgGrad_{$idx}" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:{$colors[0]};stop-opacity:1" />
      <stop offset="50%" style="stop-color:{$colors[1]};stop-opacity:1" />
      <stop offset="100%" style="stop-color:{$colors[2]};stop-opacity:1" />
    </linearGradient>
    <radialGradient id="glow_{$idx}" cx="75%" cy="40%" r="50%">
      <stop offset="0%" style="stop-color:#B8956A;stop-opacity:0.35" />
      <stop offset="100%" style="stop-color:#B8956A;stop-opacity:0" />
    </radialGradient>
  </defs>

  <rect width="1600" height="650" fill="url(#bgGrad_{$idx})" />
  <circle cx="1200" cy="280" r="420" fill="url(#glow_{$idx})" />

  <!-- Borders -->
  <rect x="40" y="40" width="1520" height="570" rx="16" fill="none" stroke="#B8956A" stroke-width="1.5" stroke-dasharray="10,6" opacity="0.35" />

  <!-- Content -->
  <g transform="translate(100, 240)">
    <!-- Tag Badge -->
    <rect x="0" y="0" width="220" height="36" rx="18" fill="#B8956A" opacity="0.2" />
    <text x="110" y="23" font-family="'Inter', sans-serif" font-size="12" font-weight="700" fill="#E8D7B8" letter-spacing="3" text-anchor="middle">{$escapedTag}</text>

    <!-- Title -->
    <text x="0" y="90" font-family="'Playfair Display', Georgia, serif" font-size="48" font-weight="700" fill="#FAF7F2">{$escapedTitle}</text>

    <!-- Subtitle -->
    <text x="0" y="140" font-family="'Inter', sans-serif" font-size="20" fill="#C9BCB1" max-width="800">{$escapedSubtitle}</text>

    <!-- CTA Button Mockup -->
    <g transform="translate(0, 185)">
      <rect width="210" height="48" rx="24" fill="#B8956A" />
      <text x="105" y="30" font-family="'Inter', sans-serif" font-size="14" font-weight="600" fill="#FFFFFF" text-anchor="middle">Lihat Koleksi &rarr;</text>
    </g>
  </g>
</svg>
SVG;
    }
}
