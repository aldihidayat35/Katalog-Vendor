# 💍 Web Katalog Undangan Pernikahan (Galeria Invitation)

Platform katalog undangan pernikahan (cetak eksklusif & digital modern) berbasis **Laravel 12**, **Blade**, **Tailwind CSS**, dan **Alpine.js**. Sistem ini mengusung alur transaksi praktis langsung terintegrasi dengan **WhatsApp Admin** (tanpa ketergantungan payment gateway pihak ketiga) dengan skema pembayaran DP (Uang Muka) maupun Full Payment.

---

## ✨ Fitur Utama

### 1. Pengunjung & Publik
- **Beranda Interaktif**: Carousel banner promosi dinamis (Swiper.js), 3 langkah mudah pemesanan, produk unggulan, grid kategori bertingkat (Bronze, Silver, Gold, Diamond), dan floating WhatsApp button.
- **Katalog & Filter Fleksibel**: Pencarian live, filter kategori, filter tipe undangan (cetak / digital), pengurutan harga/terbaru, pagination rapi, dan responsif di mobile.
- **Detail Desain Kaya**: Galeri foto multislide thumbnail, badge diskon & status stok, kalkulasi DP vs Full Payment interaktif, deskripsi spesifikasi bahan & bonus cetak.
- **Keranjang Belanja Realtime**: Penyesuaian kuantitas dengan batas minimal pesanan (`min_order`), penghapusan item, validasi stok habis.
- **Kupon Promo & Diskon**: Dukungan promo tipe persentase (`percentage`) maupun potongan nominal (`fixed`), validasi tanggal berlaku & kuota pemakaian.
- **SEO & Aksesibilitas**: Dynamic Meta Tags, Open Graph / Twitter Card preview, Canonical URL, `sitemap.xml` dinamis, dan panduan bot pada `robots.txt`.

### 2. Member Area
- **Autentikasi Aman**: Login, registrasi, forgot password terproteksi rate limiting.
- **Riwayat Pesanan**: Pelacakan status pesanan (Draft, Menunggu Konfirmasi, Dikonfirmasi, Diproses, Selesai, Dibatalkan).
- **Detail Pesanan & Rekap Format WA**: Melihat ringkasan data calon pengantin, tanggal acara, alamat pengiriman, serta tombol pembuatan ulang link WhatsApp.
- **Kelola Profil**: Pembaruan nama, nomor WhatsApp, akun Instagram, dan alamat pengiriman default.

### 3. Panel Administrasi (Admin Dashboard)
- **Ringkasan Statistik**: Total pendapatan (dari pesanan dikonfirmasi/selesai), total pesanan, produk aktif, dan konversi lead.
- **Manajemen Pesanan**: Filter status, detail data pemesan, modal edit status pesanan, pencatatan catatan internal admin (`admin_notes`), dan direct link chat pemesan.
- **Katalog Produk & Galeri Multi-Upload**: Manajemen produk dengan gambar utama dan multiple gambar galeri, diskon, stok status, dan pembersihan file otomatis saat dihapus.
- **Kategori & Banner Promo**: Pengaturan banner promosi homepage dengan urutan tampilan (`sort_order`).
- **Kupon Diskon (Promo)**: Pembuatan kupon, pengaturan tanggal kedaluwarsa, batas minimum belanja, dan kuota.
- **Pengaturan Toko**: Konfigurasi nomor WhatsApp admin, nama situs, persentase DP default, dan template pesan pemesanan.

---

## 🛠️ Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+), Eloquent ORM, MySQL (kompatibel PostgreSQL).
- **Frontend**: Blade Templating, Tailwind CSS v3 via Vite, Alpine.js v3 (interaktivitas & modal), Swiper.js v11 (carousel).
- **Icons & Typography**: Heroicons (inline SVG), Font *Playfair Display* & *Plus Jakarta Sans*.
- **Testing**: PHPUnit / Laravel Test Suite (70+ automated feature tests).

---

## 📋 Persyaratan Sistem

- **PHP**: Versi `>= 8.2` (ekstensi: `pdo_mysql`, `mbstring`, `fileinfo`, `openssl`, `curl`, `gd` atau `imagick`)
- **Composer**: Versi `>= 2.2`
- **Node.js**: Versi `>= 18.x` & **npm**
- **Database**: MySQL `>= 8.0` atau MariaDB `>= 10.4`

---

## 🚀 Langkah Instalasi & Menjalankan Lokal

Ikuti langkah-langkah berikut untuk menginstal proyek dari awal:

### 1. Clone Repositori
```bash
git clone https://github.com/aldihidayat35/Katalog-Vendor.git
cd Katalog-Vendor
```

### 2. Install Dependensi PHP & Node.js
```bash
composer install
npm install
```

### 3. Konfigurasi Environment (`.env`)
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Buka file `.env` dan sesuaikan kredensial database lokal Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=katalog_undangan
DB_USERNAME=root
DB_PASSWORD=

WHATSAPP_ADMIN_NUMBER=6281234567890
FILESYSTEM_DISK=public
```

### 4. Generate Application Key & Symlink Storage
```bash
php artisan key:generate
php artisan storage:link
```

### 5. Migrasi & Seeding Database
Untuk lingkungan **Pengembangan / Demo** (dengan produk demo dan sampel pesanan):
```bash
php artisan migrate:fresh --seed
```

Untuk lingkungan **Produksi Bersih** (hanya Admin, Kategori, dan Pengaturan dasar):
```bash
php artisan migrate:fresh
php artisan db:seed --class=ProductionSeeder
```

### 6. Build Aset Frontend
```bash
# Untuk mode development (hot-reload):
npm run dev

# Atau kompilasi mode produksi:
npm run build
```

### 7. Jalankan Server Lokal
```bash
php artisan serve
```
Buka browser Anda di `http://127.0.0.1:8000`.

---

## 🔑 Akun Demo Pengujian

Setelah menjalankan `php artisan migrate:fresh --seed`, Anda dapat masuk dengan akun demo:

| Role | Email | Password | Keterangan |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin@example.com` | `password` | Akses penuh Admin Panel (`/admin`) |
| **Member 1** | `budi@example.com` | `password` | Akses Member, Keranjang, Pesanan |
| **Member 2** | `siti@example.com` | `password` | Akses Member |

> ⚠️ **Penting untuk Produksi**: Saat meluncurkan ke server live publik, segera ubah email dan password akun administrator atau jalankan `ProductionSeeder` dengan env `ADMIN_DEFAULT_PASSWORD`.

---

## 📱 Mengatur Nomor WhatsApp Admin

Nomor WhatsApp Admin digunakan untuk menerima draf pesanan otomatis dari pembeli serta tombol floating konsultasi.

1. **Via Panel Admin**: Masuk ke `/admin/settings`, ubah kolom **Nomor WhatsApp Admin**, lalu simpan.
2. **Via File `.env`**: Ubah variabel `WHATSAPP_ADMIN_NUMBER=6281234567890` (gunakan format nomor internasional diawali `62` tanpa tanda `+` atau spasi).

---

## 📁 Struktur Folder Utama

```text
app/
├── Helpers/              # format.php: rupiah(), wa_link(), setting(), clean_phone_number()
├── Http/
│   ├── Controllers/
│   │   ├── Admin/        # Dashboard, Product, Category, Promo, Order, Banner, Setting
│   │   ├── CartController.php
│   │   ├── CheckoutController.php
│   │   ├── CatalogController.php
│   │   └── OrderHistoryController.php
│   └── Middleware/       # AdminMiddleware (proteksi role admin)
├── Models/               # Product, Category, Order, OrderItem, Cart, Promo, Banner, Setting
└── Services/             # CartService, CheckoutService (single source of truth perhitungan)
database/
├── migrations/           # Skema tabel database & indeks performa
└── seeders/              # DatabaseSeeder, ProductionSeeder, ProductSeeder, dll.
resources/views/
├── admin/                # Antarmuka Admin Panel
├── errors/               # Halaman kustom 404, 403, 419, 429, 500
├── layouts/              # app.blade.php (publik) & admin.blade.php (admin)
└── [catalog, cart, checkout, order, home]
```

---

## 🚦 Rute-Rute Penting

| Endpoint | Method | Keterangan | Akses |
| :--- | :--- | :--- | :--- |
| `/` | `GET` | Beranda (Cached, Hero, Langkah, Rekomendasi) | Publik |
| `/katalog` | `GET` | Eksplorasi Katalog & Filter | Publik |
| `/produk/{slug}` | `GET` | Detail Undangan, Galeri, & Opsi DP/Full | Publik |
| `/keranjang` | `GET, POST, PATCH, DELETE`| Pengelolaan Keranjang Belanja | Auth (Member) |
| `/checkout` | `GET, POST` | Checkout Pemesanan & Pengalihan WA | Auth (Member) |
| `/pesanan-saya` | `GET, POST` | Riwayat & Detail Pesanan Member | Auth (Member) |
| `/admin/*` | `GET, POST, ...`| Dashboard, Produk, Kategori, Pesanan, Settings | Admin Only |
| `/sitemap.xml` | `GET` | Dynamic XML Sitemap untuk Search Engine | Publik |
| `/robots.txt` | `GET` | Panduan Web Crawler & Proteksi Rute Privat | Publik |

---

## 🧪 Menjalankan Automated Tests

Aplikasi dilengkapi suite pengujian otomatis untuk memvalidasi alur kritis transaksi, kalkulasi harga, proteksi IDOR, dan keamanan middleware:

```bash
php artisan test
```

Cakupan pengujian mencakup:
- `CartAndCheckoutTest`: Penambahan keranjang, validasi min order, diskon promo, skema DP vs Full Payment, dan manipulasi client price prevention.
- `SecurityAndPerformanceTest`: Proteksi IDOR (User A tidak bisa memanipulasi keranjang/order User B), dynamic `sitemap.xml`, proteksi `robots.txt`, verifikasi `ProductionSeeder`, serta custom error 404 page.
- `AdminMiddlewareTest` & `AdminPanelTest`: Isolasi otorisasi hak akses dashboard admin.
- `CatalogTest` & `ProfileTest`: Pengujian filter pencarian dan pembaruan profil member.

---

## 🌐 Panduan Deployment Produksi

### 1. Rekomendasi Pengaturan Server
- Web Server: Nginx atau Apache (arah DocumentRoot ke folder `/public`)
- PHP 8.2+ FPM dengan OPcache aktif
- MySQL 8.0+

### 2. Perintah Optimasi Pasca Deploy
Jalankan perintah ini di server produksi untuk performa maksimal:
```bash
# 1. Pastikan mode produksi aktif di .env:
# APP_ENV=production
# APP_DEBUG=false

# 2. Cache konfigurasi, route, dan view:
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Buat symlink publik:
php artisan storage:link

# 4. Jalankan migrasi database produksi:
php artisan migrate --force
php artisan db:seed --class=ProductionSeeder --force

# 5. Build aset terkompresi:
npm run build
```

---

## 💡 Keputusan Desain & Asumsi Arsitektur

1. **Alur Transaksi Tanpa Payment Gateway**:
   - Pemesanan diproses langsung via WhatsApp resmi untuk mendukung personalisasi pesanan undangan (pengantin sering membutuhkan konsultasi teks, revisi redaksi akad/resepsi, dan penyesuaian denah lokasi).
2. **Kalkulasi Server-Side & Integer Arithmetic**:
   - Seluruh angka nominal rupiah disimpan dan dihitung dalam tipe data integer murni (tanpa floating point rounding issue).
   - Logika perhitungan diisolasi di `CartService` dan `CheckoutService` sebagai *Single Source of Truth*. Nilai dari sisi client tidak pernah dipercaya.
3. **Skema Pembayaran Fleksibel**:
   - Konsumen dapat memilih pembayaran **Uang Muka (DP)** untuk mengunci antrean produksi atau **Full Payment** dengan potongan kupon diskon promo.
4. **Keamanan & Pencegahan IDOR**:
   - Keranjang, data checkout, dan riwayat pesanan diverifikasi terhadap kepemilikan `auth()->id()`. Pengguna lain yang mencoba mengakses URL item pengguna lain secara langsung mendapatkan respon HTTP 403 Forbidden.
5. **Caching & Invalidation Strategi**:
   - Data homepage (banner, kategori, produk unggulan) di-cache selama 1 jam (`Cache::remember('homepage_data', 3600, ...)`), dan secara otomatis di-invalidate (`Cache::forget('homepage_data')`) setiap kali Administrator melakukan penambahan, pengubahan, atau penghapusan produk, kategori, atau banner.

---
**Galeria Invitation** &mdash; *Luxury & Elegance for Your Special Wedding Moments.*
