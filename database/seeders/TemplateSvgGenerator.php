<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\File;

class TemplateSvgGenerator
{
    public static function generateAll(): void
    {
        $dir = public_path('images/landing/templates');
        if (! File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $templates = [
            'neraya' => [
                'name' => 'Neraya',
                'category' => 'Pernikahan',
                'palette' => ['#FBF7F2', '#EEDCC8', '#935006', '#4A2A0C'],
                'icon' => 'flower',
                'monogram' => 'NY',
                'tag' => 'FLORAL ELEGANCE',
            ],
            'lumina' => [
                'name' => 'Lumina',
                'category' => 'Pernikahan',
                'palette' => ['#1E1C1A', '#2D2925', '#D4AF37', '#FAF7F2'],
                'icon' => 'moon',
                'monogram' => 'LM',
                'tag' => 'DARK LUXURY',
            ],
            'sakura' => [
                'name' => 'Sakura',
                'category' => 'Khitanan',
                'palette' => ['#FDF7F8', '#F5DDE3', '#C75B7A', '#522230'],
                'icon' => 'blossom',
                'monogram' => 'SK',
                'tag' => 'ISLAMI BERKAH',
            ],
            'velora' => [
                'name' => 'Velora',
                'category' => 'Tunangan',
                'palette' => ['#F9F6F0', '#EFE4D6', '#B76E79', '#3D2529'],
                'icon' => 'heart',
                'monogram' => 'VL',
                'tag' => 'SWEET ENGAGEMENT',
            ],
            'madani' => [
                'name' => 'Madani',
                'category' => 'Aqiqah',
                'palette' => ['#F5F8F6', '#DDEAE2', '#2E6F56', '#123326'],
                'icon' => 'crescent',
                'monogram' => 'MD',
                'tag' => 'TASYAKURAN AQIQAH',
            ],
            'aluna' => [
                'name' => 'Aluna',
                'category' => 'Pernikahan',
                'palette' => ['#FAF9F6', '#EDE8DF', '#8C6239', '#302318'],
                'icon' => 'leaf',
                'monogram' => 'AL',
                'tag' => 'CLASSIC MINIMALIST',
            ],
            'flore' => [
                'name' => 'Flore',
                'category' => 'Pernikahan',
                'palette' => ['#FAF6F5', '#EEDCDA', '#A35353', '#3B1F20'],
                'icon' => 'bouquet',
                'monogram' => 'FL',
                'tag' => 'ROMANTIC BOTANICAL',
            ],
            'savana' => [
                'name' => 'Savana',
                'category' => 'Aqiqah',
                'palette' => ['#F9F8F5', '#EBE7D8', '#6A7B54', '#262E1C'],
                'icon' => 'olive',
                'monogram' => 'SV',
                'tag' => 'EARTHY ISLAMI',
            ],
            'aura-party' => [
                'name' => 'Aura Party',
                'category' => 'Ulang Tahun',
                'palette' => ['#FFF9F2', '#FDE4C8', '#D4732A', '#4A2308'],
                'icon' => 'cake',
                'monogram' => 'AP',
                'tag' => 'BIRTHDAY BASH',
            ],
            'caelum-graduation' => [
                'name' => 'Caelum',
                'category' => 'Wisuda',
                'palette' => ['#F5F7FA', '#DCE4F0', '#2E5B88', '#0F2236'],
                'icon' => 'grad',
                'monogram' => 'CG',
                'tag' => 'GRADUATION DAY',
            ],
            'serenata-gathering' => [
                'name' => 'Serenata',
                'category' => 'Acara Lainnya',
                'palette' => ['#F8F5FA', '#E8DEEF', '#76528E', '#2B1C36'],
                'icon' => 'star',
                'monogram' => 'SG',
                'tag' => 'FAMILY GATHERING',
            ],
        ];

        foreach ($templates as $slug => $item) {
            $svgContent = self::createSvg($item);
            $dest = public_path("images/landing/templates/{$slug}.svg");
            File::put($dest, $svgContent);

            // Also update Product primary image to point to this svg
            $product = Product::where('slug', $slug)->first();
            if ($product) {
                ProductImage::updateOrCreate(
                    ['product_id' => $product->id, 'sort_order' => 1],
                    [
                        'image_path' => "images/landing/templates/{$slug}.svg",
                        'is_primary' => true,
                    ]
                );
            }
        }
    }

    private static function createSvg(array $data): string
    {
        $bgLight = $data['palette'][0];
        $bgAccent = $data['palette'][1];
        $colorMain = $data['palette'][2];
        $colorDark = $data['palette'][3];
        $name = htmlspecialchars($data['name']);
        $cat = htmlspecialchars($data['category']);
        $mono = htmlspecialchars($data['monogram']);
        $tag = htmlspecialchars($data['tag']);

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 520" width="100%" height="100%">
  <defs>
    <linearGradient id="bg_{$mono}" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="{$bgLight}"/>
      <stop offset="100%" stop-color="{$bgAccent}"/>
    </linearGradient>
    <filter id="shadow_{$mono}" x="-10%" y="-10%" width="120%" height="120%">
      <feDropShadow dx="0" dy="10" stdDeviation="14" flood-color="{$colorDark}" flood-opacity="0.12"/>
    </filter>
  </defs>

  <!-- Canvas Background -->
  <rect width="600" height="520" fill="url(#bg_{$mono})"/>

  <!-- Border Ornament -->
  <rect x="24" y="24" width="552" height="472" fill="none" stroke="{$colorMain}" stroke-width="1.2" stroke-dasharray="8,6" opacity="0.35" rx="10"/>

  <!-- Main Card -->
  <g transform="translate(90, 55)" filter="url(#shadow_{$mono})">
    <rect width="420" height="410" rx="14" fill="#FFFFFF" stroke="{$bgAccent}" stroke-width="1.5"/>
    <rect x="14" y="14" width="392" height="382" fill="none" stroke="{$colorMain}" stroke-width="1" stroke-dasharray="4,4" opacity="0.45" rx="10"/>

    <!-- Arch Header Motif -->
    <path d="M 150 14 Q 210 -6 270 14" stroke="{$colorMain}" stroke-width="1.5" fill="none" opacity="0.7"/>

    <!-- Monogram Seal -->
    <circle cx="210" cy="115" r="38" fill="{$bgLight}" stroke="{$colorMain}" stroke-width="2"/>
    <circle cx="210" cy="115" r="32" fill="none" stroke="{$colorMain}" stroke-width="1" stroke-dasharray="3,2" opacity="0.6"/>
    <text x="210" y="123" font-family="'Playfair Display', Georgia, serif" font-size="22" font-weight="700" fill="{$colorMain}" text-anchor="middle">{$mono}</text>

    <!-- Category Tag -->
    <rect x="120" y="180" width="180" height="24" rx="12" fill="{$bgLight}" stroke="{$colorMain}" stroke-width="0.8"/>
    <text x="210" y="196" font-family="'DM Sans', sans-serif" font-size="9" font-weight="700" fill="{$colorMain}" letter-spacing="2" text-anchor="middle">{$tag}</text>

    <!-- Template Name -->
    <text x="210" y="245" font-family="'Playfair Display', Georgia, serif" font-size="28" font-weight="600" fill="{$colorDark}" text-anchor="middle">{$name}</text>
    <text x="210" y="272" font-family="'DM Sans', sans-serif" font-size="12" font-weight="500" fill="#7a7067" text-anchor="middle">Undangan Digital {$cat}</text>

    <!-- Decorative Divider Line -->
    <path d="M 130 300 L 290 300" stroke="{$colorMain}" stroke-width="1" stroke-dasharray="5,4" opacity="0.5"/>
    <circle cx="210" cy="300" r="3" fill="{$colorMain}"/>

    <!-- Live Preview Button Motif -->
    <rect x="135" y="330" width="150" height="36" rx="18" fill="{$colorMain}"/>
    <text x="210" y="352" font-family="'DM Sans', sans-serif" font-size="11" font-weight="700" fill="#FFFFFF" text-anchor="middle">Preview Template</text>
  </g>
</svg>
SVG;
    }
}
