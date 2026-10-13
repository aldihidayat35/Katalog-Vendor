<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all()->keyBy('slug');

        $storagePath = storage_path('app/public/products');
        if (! File::isDirectory($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
        }

        $productsData = [
            // BRONZE CATEGORY (3 products)
            [
                'category_slug' => 'bronze',
                'name' => 'Arunika Minimalist Suite',
                'slug' => 'arunika-minimalist-suite',
                'short_description' => 'Undangan single board minimalis dengan amplop kraft bertekstur modern.',
                'description' => "Paket undangan Arunika menghadirkan keanggunan dalam kesederhanaan. Mengusung gaya modern minimalist dengan tata letak tipografi bersih dan berkarakter.\n\nIsi Paket:\n• 1 Kartu Undangan Utama (Single Board)\n• 1 Amplop Kraft Premium Bertekstur\n• 1 Kartu Ucapan Terima Kasih (Souvenir Card)\n• 1 Plastik Pembungkus Bening (OPP per pcs)\n• Label Nama Tamu (opsional print)",
                'specifications' => "• Bahan Kartu: Kertas Aster White 230 gsm bertekstur halus\n• Bahan Amplop: Kraft Paper 180 gsm earthy tone\n• Ukuran Kartu: 14.5 x 20.5 cm\n• Ukuran Amplop: 15.5 x 21.5 cm\n• Finishing: Tinta Doff Premium, Die-cut presisi\n• Estimasi Pengerjaan: 7 - 10 hari kerja",
                'type' => 'printed',
                'theme_color' => 'terracotta',
                'price' => 3500,
                'discount_price' => 2900,
                'dp_price' => 150000,
                'min_order' => 100,
                'badge' => 'Efisien',
                'stock_status' => 'ready',
                'is_featured' => true,
            ],
            [
                'category_slug' => 'bronze',
                'name' => 'Kirana Botanical Single Card',
                'slug' => 'kirana-botanical-single-card',
                'short_description' => 'Desain ilustrasi dedaunan botanik berpadu warna netral hangat.',
                'description' => "Nuansa alam yang hangat dengan ilustrasi dedaunan cat air nan manis. Sangat ideal bagi konsep pesta intimate atau garden party.\n\nIsi Paket:\n• 1 Kartu Undangan Utama\n• 1 Amplop Polos Ivory\n• 1 Kartu Souvenir / Penukaran Souvenir\n• Plastik pelindung sealable",
                'specifications' => "• Bahan Kartu: Jasmine Paper 210 gsm (berkilau mutiara halus)\n• Bahan Amplop: Concorde Ivory 150 gsm\n• Ukuran: 15 x 20 cm\n• Cetak: Full Color Digital Offset High Definition\n• Estimasi Pengerjaan: 7 hari kerja",
                'type' => 'printed',
                'theme_color' => 'sage',
                'price' => 3800,
                'discount_price' => null,
                'dp_price' => 150000,
                'min_order' => 100,
                'badge' => null,
                'stock_status' => 'ready',
                'is_featured' => false,
            ],
            [
                'category_slug' => 'bronze',
                'name' => 'Lestari Digital Web Mini',
                'slug' => 'lestari-digital-web-mini',
                'short_description' => 'Undangan digital berbasis link website ringan, cepat, dan responsif.',
                'description' => "Solusi praktis menyapa kerabat jarak jauh tanpa batasan jarak. Undangan website estetik dengan animasi transisi lembut dan navigasi ramah smartphone.\n\nFitur Termasuk:\n• Custom Nama Tamu Tanpa Batas\n• Countdown Timer & Google Calendar Sync\n• Navigasi Google Maps Lokasi Acara\n• Galeri Foto hingga 5 Foto\n• Background Music Pilihan",
                'specifications' => "• Tipe: Website Responsive (HTML5 & Mobile First)\n• Masa Aktif: 6 Bulan sejak publikasi\n• Waktu Pembuatan: 1 - 2 hari kerja setelah data lengkap\n• Revisi: Maksimal 2 kali minor revisi",
                'type' => 'digital',
                'theme_color' => 'dusty-blue',
                'price' => 99000,
                'discount_price' => 75000,
                'dp_price' => 50000,
                'min_order' => 1,
                'badge' => 'Populer',
                'stock_status' => 'ready',
                'is_featured' => true,
            ],

            // SILVER CATEGORY (3 products)
            [
                'category_slug' => 'silver',
                'name' => 'Amora Foil Envelope Suite',
                'slug' => 'amora-foil-envelope-suite',
                'short_description' => 'Undangan lipat dua dengan foil emas berkilau dan amplop kalkir elegan.',
                'description' => "Paket favorit calon pengantin! Perpaduan kertas linen berkarakter dengan aksen hot-stamping foil emas pada inisial nama, dibalut amplop kalkir transparan premium.\n\nIsi Paket:\n• 1 Undangan Utama Lipat Dua (Bifold)\n• 1 Amplop Kalkir Transparan Mewah (Vellum Envelope)\n• 1 Pita Chiffon / Wax Seal Sticker\n• 1 Kartu Doa & Souvenir\n• Plastik pelindung transparan",
                'specifications' => "• Bahan Kartu: Linen Import 250 gsm (serat kain nyata)\n• Bahan Amplop: Vellum Paper / Kalkir 150 gsm\n• Finishing: Hot-stamp Gold Foil pada inisial dan judul\n• Ukuran Terbuka: 20 x 28 cm | Ukuran Lipat: 14 x 20 cm\n• Estimasi Pengerjaan: 10 - 14 hari kerja",
                'type' => 'printed',
                'theme_color' => 'gold',
                'price' => 6500,
                'discount_price' => 5800,
                'dp_price' => 300000,
                'min_order' => 100,
                'badge' => 'Favorit',
                'stock_status' => 'ready',
                'is_featured' => true,
            ],
            [
                'category_slug' => 'silver',
                'name' => 'Sasmita Arch Geometric Line',
                'slug' => 'sasmita-arch-geometric-line',
                'short_description' => 'Potongan bentuk kubah lengkung (Arch die-cut) modern nan trendi.',
                'description' => "Tampil beda dengan lekukan kubah estetis. Desain modern kontemporer yang memberikan kesan dinamis dan kekinian bagi tamu undangan Anda.\n\nIsi Paket:\n• 1 Kartu Lengkung (Arch Shape Card)\n• 1 Kartu Detail Acara / RSVP Mini\n• 1 Amplop Sage Green Custom Pocket\n• 1 Klip Gold Pengikat Kartu\n• Plastik OPP pelindung",
                'specifications' => "• Bahan Kartu: Fedrigoni Materica Clay 250 gsm impor\n• Bentuk: Arch Die Cut Computerized Cutting\n• Amplop: Jasmine Dusty Green 210 gsm\n• Ukuran: 13 x 19 cm\n• Estimasi Pengerjaan: 12 hari kerja",
                'type' => 'printed',
                'theme_color' => 'sage',
                'price' => 7200,
                'discount_price' => null,
                'dp_price' => 350000,
                'min_order' => 100,
                'badge' => null,
                'stock_status' => 'ready',
                'is_featured' => false,
            ],
            [
                'category_slug' => 'silver',
                'name' => 'Kalandra Interactive Web Suite',
                'slug' => 'kalandra-interactive-web-suite',
                'short_description' => 'Undangan digital website interaktif lengkap RSVP WhatsApp & Love Story.',
                'description' => "Paket undangan digital kaya fitur dengan interaktivitas menyeluruh. Memudahkan tamu memberikan konfirmasi kehadiran langsung ke WhatsApp pengantin.\n\nFitur Utama:\n• Form RSVP Terintegrasi WhatsApp & Rekap Spreadsheet\n• Fitur Amplop Digital (QRIS, Bank Transfer, Kirim Kado)\n• Love Story Timeline Interaktif\n• Galeri Foto 15 Foto + 1 Video Embed\n• Filter Instagram Story Khusus (Opsional Add-on)",
                'specifications' => "• Teknologi: Vue/Alpine Modern Web App + CDN Cepat\n• Masa Aktif: 1 Tahun Penuh\n• Waktu Pengerjaan: 2 - 3 hari kerja\n• Kompatibilitas: Optimal di iOS, Android, Desktop",
                'type' => 'digital',
                'theme_color' => 'rose-gold',
                'price' => 180000,
                'discount_price' => 149000,
                'dp_price' => 80000,
                'min_order' => 1,
                'badge' => 'Best Seller',
                'stock_status' => 'ready',
                'is_featured' => true,
            ],

            // GOLD CATEGORY (3 products)
            [
                'category_slug' => 'gold',
                'name' => 'Maharani Velvet & Wax Seal',
                'slug' => 'maharani-velvet-wax-seal',
                'short_description' => 'Undangan hard cover semi-board dengan ornamen wax seal cap lilin mewah.',
                'description' => "Sentuhan bangsawan terpancar dari tekstur lilin stempel (wax seal) asli dan ornamen pita beledu. Tamu kehormatan Anda akan merasakan kemewahan sejak amplop pertama dibuka.\n\nIsi Paket:\n• 1 Undangan Utama Hard Cover Kokoh\n• 1 Pocket Amplop Berlapis Foil\n• 1 Segel Cap Lilin (Authentic Wax Seal) Custom Monogram\n• 1 Pita Beludru (Velvet Ribbon) Premium\n• 1 Kartu Doa & Souvenir Eksklusif\n• Plastik pelindung premium",
                'specifications' => "• Konstruksi: Greyboard No. 40 tebal dilapisi Art Carton 190 gsm Laminasi Doff\n• Amplop: Ivory Carton 260 gsm dengan Rose Gold Hot Foil\n• Wax Seal: Lilin fleksibel anti-pecah warna Champagne Gold\n• Ukuran: 15.5 x 21.5 cm\n• Estimasi Pengerjaan: 14 - 18 hari kerja",
                'type' => 'printed',
                'theme_color' => 'burgundy',
                'price' => 11500,
                'discount_price' => 9900,
                'dp_price' => 500000,
                'min_order' => 100,
                'badge' => 'Premium',
                'stock_status' => 'ready',
                'is_featured' => true,
            ],
            [
                'category_slug' => 'gold',
                'name' => 'Diva Pocket Ribbon Deluxe',
                'slug' => 'diva-pocket-ribbon-deluxe',
                'short_description' => 'Model kantong pocket tiga lipat berbalut pita satin elegan.',
                'description' => "Paket undangan pocket fold dengan kompartemen rapi untuk menyisipkan kartu acara resepsi, akad, maupun peta denah lokasi.\n\nIsi Paket:\n• 1 Pocket Jacket Tri-fold Embossed\n• 1 Kartu Utama Main Invitation\n• 2 Kartu Tambahan (Resepsi & Protokol/Souvenir)\n• 1 Pita Satin Lembut dengan Bros Logam Mini\n• Plastik pelindung tebal",
                'specifications' => "• Bahan Jacket: Sirio Pearl Shiny Card 300 gsm\n• Bahan Isi: Aster Paper 250 gsm dengan Golden Border Foil\n• Ukuran Lipat: 15 x 21 cm\n• Estimasi Pengerjaan: 14 hari kerja",
                'type' => 'printed',
                'theme_color' => 'champagne',
                'price' => 12800,
                'discount_price' => null,
                'dp_price' => 600000,
                'min_order' => 100,
                'badge' => 'Premium',
                'stock_status' => 'ready',
                'is_featured' => false,
            ],
            [
                'category_slug' => 'gold',
                'name' => 'Nirwana 3D Digital Panorama Web',
                'slug' => 'nirwana-3d-digital-panorama-web',
                'short_description' => 'Undangan web dengan efek parallax 3D interaktif dan buku tamu digital.',
                'description' => "Inovasi digital masa depan dengan kedalaman visual parallax 3D. Menghadirkan kesan visual sinematik layaknya melihat majalah pernikahan interaktif berkelas.\n\nFitur Eksklusif:\n• Efek Scroll 3D Parallax & Partikel Cahaya\n• Live Guestbook dengan moderasi ucapan\n• QR Code Check-in untuk Penerima Tamu di Lokasi Acara\n• Integrasi Live Streaming YouTube/Zoom\n• Galeri Foto 30 Foto + Audio Visualizer",
                'specifications' => "• Fitur: Unlimited Tamu, High-speed Hosting Cloudflare\n• Masa Aktif: Selamanya (Lifetime Archive)\n• Waktu Pengerjaan: 3 - 4 hari kerja",
                'type' => 'digital',
                'theme_color' => 'emerald',
                'price' => 250000,
                'discount_price' => 199000,
                'dp_price' => 100000,
                'min_order' => 1,
                'badge' => 'Signature',
                'stock_status' => 'ready',
                'is_featured' => true,
            ],

            // DIAMOND CATEGORY (3 products)
            [
                'category_slug' => 'diamond',
                'name' => 'Royal Palace Acrylic Hardcover',
                'slug' => 'royal-palace-acrylic-hardcover',
                'short_description' => 'Kombinasi plat akrilik kristal transparan dengan kotak box beludru mewah.',
                'description' => "Kemewahan tanpa kompromi. Menggunakan lempengan akrilik bening setebal 2mm yang dicetak dengan teknik UV Printing emas metalik, disajikan di dalam kotak box hardcover berlapis kain suede lembut.\n\nIsi Paket:\n• 1 Plat Undangan Akrilik Kristal 2mm (Beveled Edge)\n• 1 Exclusive Hardcover Gift Box Berbalut Suede\n• 1 Kartu Informasi Acara Tambahan\n• 1 Wax Seal Logam Monogram Khusus\n• 1 Kantong Dust Bag Pelindung Box",
                'specifications' => "• Material Utama: Akrilik Bening Kristal Grade A (Tebal 2 mm)\n• Teknik Cetak: UV Print Flatbed High Resolution Scratch-Resistant\n• Box: Karton Hardboard No. 30 lapis Kain Suede/Velvet Lembut\n• Ukuran Akrilik: 14 x 20 cm | Ukuran Box: 16 x 22 x 2.5 cm\n• Estimasi Pengerjaan: 18 - 25 hari kerja",
                'type' => 'printed',
                'theme_color' => 'navy',
                'price' => 24500,
                'discount_price' => 22000,
                'dp_price' => 1000000,
                'min_order' => 100,
                'badge' => 'Masterpiece',
                'stock_status' => 'preorder',
                'is_featured' => true,
            ],
            [
                'category_slug' => 'diamond',
                'name' => 'Sultanate Laser Cut Hardbox',
                'slug' => 'sultanate-laser-cut-hardbox',
                'short_description' => 'Kotak keras ukiran laser presisi tinggi dengan sentuhan kain songket emas.',
                'description' => "Mahakarya seni ukir laser modern yang memadukan warisan ornamen klasik dengan kemewahan aristokrat modern.\n\nIsi Paket:\n• 1 Kotak Hardbox Pembuka Magnetik\n• 1 Undangan Utama Tebal dengan Border Laser Cutting Halus\n• 1 Lapisan Kertas Vellum Emas Pelindung\n• 2 Kartu Aksesoris Tambahan\n• Pita Tassel Emas Kerajaan",
                'specifications' => "• Bahan Box: Greyboard 40 lapis Metallic Linen Paper\n• Laser Cutting: Akurasi toleransi 0.1 mm detail renda rumit\n• Dimensi: 17 x 23 x 3 cm\n• Estimasi Pengerjaan: 20 - 25 hari kerja",
                'type' => 'printed',
                'theme_color' => 'gold',
                'price' => 29000,
                'discount_price' => 26500,
                'dp_price' => 1200000,
                'min_order' => 100,
                'badge' => 'Eksklusif',
                'stock_status' => 'preorder',
                'is_featured' => false,
            ],
            [
                'category_slug' => 'diamond',
                'name' => 'Imperial VIP Web & Video 4K Suite',
                'slug' => 'imperial-vip-web-video-4k-suite',
                'short_description' => 'Paket digital terlengkap: Website kustom domain (.com) + Video Teaser 4K.',
                'description' => "Paket pamungkas undangan digital. Bukan sekadar website, namun pengalaman digital komprehensif lengkap dengan domain kustom (contoh: andi-rina.com) dan video animasi sinematik resolusi tinggi 4K untuk Instagram Reels & WhatsApp Story.\n\nFasilitas VIP:\n• Custom Domain Pribadi (.com / .id selama 1 tahun)\n• Desain Web Custom Tailored (Bebas Request Font & Nuansa)\n• 1 Video Undangan Animasi Vertikal 4K (Durasi 60 detik)\n• Sistem Check-in Tamu Terpadu dengan QR Code Scanner App\n• Prioritas Revisi & VIP Support 24/7",
                'specifications' => "• Paket: Website Private Domain + Video 4K UHD + Scanner System\n• Pengerjaan: 5 - 7 hari kerja\n• Masa Aktif Web & Domain: 1 Tahun Penuh",
                'type' => 'digital',
                'theme_color' => 'monochrome',
                'price' => 450000,
                'discount_price' => 399000,
                'dp_price' => 200000,
                'min_order' => 1,
                'badge' => 'VIP Suite',
                'stock_status' => 'ready',
                'is_featured' => true,
            ],
        ];

        foreach ($productsData as $data) {
            $catSlug = $data['category_slug'];
            unset($data['category_slug']);

            $category = $categories[$catSlug] ?? null;
            if (! $category) {
                continue;
            }

            $data['category_id'] = $category->id;

            $unitFinalPrice = ! empty($data['discount_price']) ? (int) $data['discount_price'] : (int) $data['price'];
            if (empty($data['dp_price']) || $data['dp_price'] > $unitFinalPrice) {
                $data['dp_price'] = (int) round($unitFinalPrice * 0.5);
            }

            $product = Product::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );

            // Generate 3 distinct local SVG images for each product
            $viewLabels = [
                1 => 'Tampak Depan Utama',
                2 => 'Detail Amplop & Tekstur',
                3 => 'Tampilan Aksesoris Lengkap',
            ];

            foreach ([1, 2, 3] as $index) {
                $imageFilename = "products/{$product->slug}-{$index}.svg";
                $filePath = storage_path("app/public/{$imageFilename}");

                if (! File::exists($filePath)) {
                    $svgContent = $this->createProductSvg(
                        $product->name,
                        $category->name,
                        $viewLabels[$index],
                        $product->type === 'digital' ? '#2A4365' : '#B8956A',
                        $index
                    );
                    File::put($filePath, $svgContent);
                }

                ProductImage::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'sort_order' => $index,
                    ],
                    [
                        'image_path' => $imageFilename,
                        'is_primary' => ($index === 1),
                    ]
                );
            }
        }
    }

    /**
     * Generate an aesthetic SVG illustration for a product
     */
    private function createProductSvg(string $name, string $category, string $view, string $accentColor, int $variant): string
    {
        $bg = match ($variant) {
            1 => '#FAF7F2',
            2 => '#F4EFE6',
            3 => '#EDE5D8',
            default => '#FAF7F2',
        };

        $escapedName = htmlspecialchars($name, ENT_QUOTES);
        $escapedCategory = htmlspecialchars($category, ENT_QUOTES);
        $escapedView = htmlspecialchars($view, ENT_QUOTES);

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 600" width="100%" height="100%">
  <defs>
    <linearGradient id="cardGrad_{$variant}" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:{$bg};stop-opacity:1" />
      <stop offset="100%" style="stop-color:#E3DAC9;stop-opacity:1" />
    </linearGradient>
    <filter id="shadow_{$variant}" x="-10%" y="-10%" width="120%" height="120%">
      <feDropShadow dx="0" dy="12" stdDeviation="16" flood-color="#000000" flood-opacity="0.08"/>
    </filter>
  </defs>

  <!-- Background -->
  <rect width="800" height="600" fill="url(#cardGrad_{$variant})" />

  <!-- Outer frame -->
  <rect x="30" y="30" width="740" height="540" fill="none" stroke="{$accentColor}" stroke-width="1.5" stroke-dasharray="8,6" opacity="0.35" rx="8" />

  <!-- Center Card Illustration -->
  <g transform="translate(180, 70)" filter="url(#shadow_{$variant})">
    <rect width="440" height="420" rx="12" fill="#FFFFFF" stroke="#E2DCD5" stroke-width="1.5"/>
    <rect x="16" y="16" width="408" height="388" fill="none" stroke="{$accentColor}" stroke-width="1" stroke-dasharray="4,4" opacity="0.5" rx="8"/>

    <!-- Foil Monogram Accent -->
    <circle cx="220" cy="110" r="38" fill="#FBF8F3" stroke="{$accentColor}" stroke-width="2"/>
    <text x="220" y="118" font-family="'Playfair Display', serif, Georgia" font-size="24" font-weight="bold" fill="{$accentColor}" text-anchor="middle">GI</text>

    <!-- Title & Text -->
    <text x="220" y="180" font-family="'Playfair Display', serif, Georgia" font-size="19" font-weight="600" fill="#2C241E" text-anchor="middle">{$escapedName}</text>
    <text x="220" y="210" font-family="'Inter', sans-serif" font-size="12" font-weight="600" fill="{$accentColor}" letter-spacing="3" text-anchor="middle">KATEGORI {$escapedCategory}</text>

    <!-- Ornamental divider -->
    <path d="M 120 235 Q 220 240 320 235" stroke="{$accentColor}" stroke-width="1" fill="none" opacity="0.6"/>
    <circle cx="220" cy="235" r="4" fill="{$accentColor}"/>

    <!-- View Badge -->
    <rect x="130" y="275" width="180" height="32" rx="16" fill="#F8F5F0" stroke="{$accentColor}" stroke-width="1"/>
    <text x="220" y="296" font-family="'Inter', sans-serif" font-size="11" font-weight="500" fill="#5A4E45" text-anchor="middle">{$escapedView}</text>

    <text x="220" y="340" font-family="'Inter', sans-serif" font-size="11" fill="#8C7E72" text-anchor="middle">Galeria Invitation • Foto {$variant} dari 3</text>
  </g>
</svg>
SVG;
    }
}
