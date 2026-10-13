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
                'subtitle' => 'Pilihan lengkap undangan cetak fisik mewah dan undangan digital interaktif untuk momen terbaik Anda.',
                'image_filename' => 'banners/banner-1.svg',
                'link_url' => '/catalog',
                'sort_order' => 1,
                'is_active' => true,
                'tag' => 'KOLEKSI LENGKAP',
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
            // Regenerate decorative background SVG
            $svg = $this->createBannerSvg($data['sort_order']);
            File::put($filePath, $svg);

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

    private function createBannerSvg(int $idx): string
    {
        $palettes = [
            1 => [
                'bg1' => '#FBF8F3',
                'bg2' => '#F4EDE2',
                'bg3' => '#EAE0D0',
                'accent' => '#C4975D',
                'glow' => '#DFC59D',
            ],
            2 => [
                'bg1' => '#F8F5F0',
                'bg2' => '#EEE7DD',
                'bg3' => '#DFD5C6',
                'accent' => '#B8860B',
                'glow' => '#D4AF37',
            ],
            3 => [
                'bg1' => '#FAF5F2',
                'bg2' => '#F2E8E2',
                'bg3' => '#E5D6CD',
                'accent' => '#B5734C',
                'glow' => '#E2AC8D',
            ],
        ];

        $p = $palettes[$idx] ?? $palettes[1];

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1600 650" width="100%" height="100%">
  <defs>
    <linearGradient id="bgGrad_{$idx}" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:{$p['bg1']};stop-opacity:1" />
      <stop offset="50%" style="stop-color:{$p['bg2']};stop-opacity:1" />
      <stop offset="100%" style="stop-color:{$p['bg3']};stop-opacity:1" />
    </linearGradient>
    <radialGradient id="glowL_{$idx}" cx="15%" cy="30%" r="55%">
      <stop offset="0%" style="stop-color:{$p['glow']};stop-opacity:0.25" />
      <stop offset="100%" style="stop-color:{$p['glow']};stop-opacity:0" />
    </radialGradient>
    <radialGradient id="glowR_{$idx}" cx="85%" cy="35%" r="50%">
      <stop offset="0%" style="stop-color:{$p['glow']};stop-opacity:0.3" />
      <stop offset="100%" style="stop-color:{$p['glow']};stop-opacity:0" />
    </radialGradient>
    <pattern id="dotPattern_{$idx}" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
      <circle cx="20" cy="20" r="1" fill="{$p['accent']}" opacity="0.12" />
    </pattern>
  </defs>

  <!-- Base Gradient Background -->
  <rect width="1600" height="650" fill="url(#bgGrad_{$idx})" />

  <!-- Subtle Pattern Overlay -->
  <rect width="1600" height="650" fill="url(#dotPattern_{$idx})" />

  <!-- Ambient Glows -->
  <circle cx="240" cy="200" r="380" fill="url(#glowL_{$idx})" />
  <circle cx="1360" cy="220" r="420" fill="url(#glowR_{$idx})" />

  <!-- Decorative Frame Borders -->
  <rect x="28" y="24" width="1544" height="602" rx="20" fill="none" stroke="{$p['accent']}" stroke-width="1.2" opacity="0.25" />
  <rect x="36" y="32" width="1528" height="586" rx="16" fill="none" stroke="{$p['accent']}" stroke-width="0.8" stroke-dasharray="6,4" opacity="0.2" />

  <!-- Corner Ornaments (Luxury Arch & Filigree) -->
  <!-- Top Left -->
  <g transform="translate(48, 44)" stroke="{$p['accent']}" stroke-width="1.2" fill="none" opacity="0.45">
    <path d="M 0,40 L 0,0 L 40,0" />
    <path d="M 12,30 L 12,12 L 30,12" opacity="0.6" />
    <circle cx="12" cy="12" r="3" fill="{$p['accent']}" />
    <path d="M 0,0 L 22,22" />
  </g>
  <!-- Top Right -->
  <g transform="translate(1552, 44) scale(-1, 1)" stroke="{$p['accent']}" stroke-width="1.2" fill="none" opacity="0.45">
    <path d="M 0,40 L 0,0 L 40,0" />
    <path d="M 12,30 L 12,12 L 30,12" opacity="0.6" />
    <circle cx="12" cy="12" r="3" fill="{$p['accent']}" />
    <path d="M 0,0 L 22,22" />
  </g>
  <!-- Bottom Left -->
  <g transform="translate(48, 606) scale(1, -1)" stroke="{$p['accent']}" stroke-width="1.2" fill="none" opacity="0.45">
    <path d="M 0,40 L 0,0 L 40,0" />
    <path d="M 12,30 L 12,12 L 30,12" opacity="0.6" />
    <circle cx="12" cy="12" r="3" fill="{$p['accent']}" />
    <path d="M 0,0 L 22,22" />
  </g>
  <!-- Bottom Right -->
  <g transform="translate(1552, 606) scale(-1, -1)" stroke="{$p['accent']}" stroke-width="1.2" fill="none" opacity="0.45">
    <path d="M 0,40 L 0,0 L 40,0" />
    <path d="M 12,30 L 12,12 L 30,12" opacity="0.6" />
    <circle cx="12" cy="12" r="3" fill="{$p['accent']}" />
    <path d="M 0,0 L 22,22" />
  </g>

  <!-- Side Arch & Botanical Watermarks (Left & Right) -->
  <g opacity="0.18" stroke="{$p['accent']}" stroke-width="1.5" fill="none">
    <!-- Left Ornamental Arches -->
    <path d="M 140,500 C 140,250 260,140 380,140 C 440,140 490,190 490,270 C 490,400 360,510 240,540" />
    <circle cx="280" cy="220" r="70" />
    <circle cx="280" cy="220" r="110" stroke-dasharray="4,4" />
    <!-- Right Ornamental Arches -->
    <path d="M 1460,500 C 1460,250 1340,140 1220,140 C 1160,140 1110,190 1110,270 C 1110,400 1240,510 1360,540" />
    <circle cx="1320" cy="220" r="70" />
    <circle cx="1320" cy="220" r="110" stroke-dasharray="4,4" />
  </g>

  <!-- Delicate Stars/Sparkles -->
  <g fill="{$p['accent']}" opacity="0.35">
    <!-- Sparkle 1 -->
    <path d="M 320,110 Q 320,125 335,125 Q 320,125 320,140 Q 320,125 305,125 Q 320,125 320,110 Z" />
    <!-- Sparkle 2 -->
    <path d="M 1280,115 Q 1280,130 1295,130 Q 1280,130 1280,145 Q 1280,130 1265,130 Q 1280,130 1280,115 Z" />
    <!-- Sparkle 3 -->
    <path d="M 1390,480 Q 1390,492 1402,492 Q 1390,492 1390,504 Q 1390,492 1378,492 Q 1390,492 1390,480 Z" />
    <!-- Sparkle 4 -->
    <path d="M 210,470 Q 210,482 222,482 Q 210,482 210,494 Q 210,482 198,482 Q 210,482 210,470 Z" />
  </g>
</svg>
SVG;
    }
}
