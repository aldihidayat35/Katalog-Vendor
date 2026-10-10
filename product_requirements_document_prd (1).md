# Product Requirements Document (PRD)
**Nama Produk:** Web Katalog Undangan (Referensi: Galeria Invitation)
**Platform:** Web Application
**Versi:** 1.0

## 1. Pendahuluan
### 1.1 Ringkasan Produk
Aplikasi web ini adalah sebuah platform katalog online yang menawarkan produk undangan (cetak dan digital). Aplikasi ini dirancang untuk memudahkan calon pengantin melihat koleksi undangan, melihat detail harga dan spesifikasi, memasukkan ke keranjang, dan melakukan proses pemesanan akhir melalui integrasi langsung ke WhatsApp admin.

### 1.2 Target Audiens
Seseorang atau pasangan yang sedang merencanakan acara (khususnya pernikahan) dan membutuhkan layanan pembuatan undangan cetak maupun digital dengan proses yang mudah dan personal (dibantu admin).

### 1.3 Teknologi yang Digunakan
*   **Backend:** Fullstack Laravel 11 atau 12
*   **Frontend:** Tailwind CSS (dikombinasikan dengan Blade template Laravel)
*   **UI/UX Design:** Stitch (untuk referensi komponen/desain antarmuka)
*   **Database:** MySQL / PostgreSQL

---

## 2. Peran Pengguna (User Roles)
1.  **Guest (Pengunjung):** Dapat melihat halaman utama, melihat katalog produk, dan detail produk.
2.  **Member (Pengguna Terdaftar):** Dapat melakukan login/register, menambahkan produk ke keranjang, mengisi form checkout, dan riwayat pesanan (opsional).
3.  **Admin:** Mengelola keseluruhan sistem melalui dashboard khusus, termasuk produk, kategori, promo, dan data member.

---

## 3. Fitur Utama (Fitur Fungsional)

### 3.1 Sisi Pengguna (User / Member)
*   **Halaman Utama (Homepage):**
    *   Banner promosi utama (Carousel/Slide).
    *   Informasi "3 Langkah Mudah" pemesanan.
    *   Tombol "Konsultasi" yang mengarah ke WhatsApp.
    *   Highlight kategori produk (Bronze, Silver, Gold, Diamond).
    *   Daftar produk terbaru atau terpopuler.
*   **Katalog Produk:**
    *   Menampilkan daftar produk dengan gambar, nama produk, harga asli, harga diskon, dan label (contoh: Premium, Efisien).
    *   Filter dan pencarian produk berdasarkan kategori.
*   **Detail Produk:**
    *   Galeri foto produk (bisa digeser/di-swipe).
    *   Informasi harga detail (Harga DP, Harga Full Payment).
    *   Deskripsi isi produk (misal: 1 undangan utama, 1 pocket, dll) dan spesifikasi bahan.
    *   Input jumlah pesanan (Quantity).
    *   Tombol "Tambahkan Keranjang".
    *   Tombol "Chat Admin" (langsung ke WA).
*   **Sistem Keranjang & Checkout:**
    *   Melihat ringkasan pesanan di keranjang.
    *   Formulir Checkout yang mencakup: Email, Nama, Akun Instagram, Tanggal Acara, Alamat Pengiriman, dan Metode Pengiriman.
    *   Perhitungan total biaya (berdasarkan DP atau Full Payment).
    *   Tombol "Lanjutkan" yang akan mengarahkan pengguna ke WhatsApp Admin dengan membawa format teks berisi data pesanan lengkap.
*   **Sistem Member:**
    *   Registrasi dan Login.
    *   Manajemen Profil (Data diri dasar untuk auto-fill saat checkout).

### 3.2 Sisi Admin (Dashboard)
*   **Otentikasi:** Login khusus admin.
*   **Manajemen Produk (CRUD):** 
    *   Tambah, edit, hapus produk.
    *   Upload foto galeri produk.
    *   Atur harga, spesifikasi, dan stok/status pre-order.
*   **Manajemen Kategori:** Membuat dan mengelola kategori undangan.
*   **Manajemen Promo & Diskon:** Membuat kode kupon diskon atau mengatur periode promo (misal: Diskon 25% periode tertentu).
*   **Manajemen Pesanan (Leads):** 
    *   Melihat data checkout user sebelum mereka lari ke WhatsApp (sebagai backup data pesanan/leads).
*   **Manajemen Member:** 
    *   Melihat daftar member yang terdaftar (nama, email, no HP).
    *   Fitur ini berguna untuk follow-up marketing di kemudian hari.

---

## 4. Alur Pengguna (User Flow) Utama
1.  **Eksplorasi:** User membuka website dan melihat-lihat produk di Homepage atau halaman Katalog.
2.  **Pemilihan:** User mengeklik salah satu produk untuk melihat detail, spesifikasi, dan harga.
3.  **Keranjang:** User memasukkan jumlah pesanan dan mengeklik "Tambahkan Keranjang".
4.  **Checkout:** User membuka keranjang, mengeklik "Checkout", dan mengisi formulir data diri serta detail pengiriman.
5.  **Konversi (WhatsApp):** Saat formulir disubmit, sistem menyimpan data leads ke database (opsional), lalu mengalihkan user ke WhatsApp Admin dengan pesan otomatis yang sudah terformat rapi sesuai isi keranjang dan formulir.

---

## 5. Rancangan Struktur Database (Awal)

1.  **`users`**: id, name, email, password, phone, role (admin/member), dll.
2.  **`categories`**: id, name, slug, description.
3.  **`products`**: id, category_id, name, slug, description, specifications, price, dp_price, is_active.
4.  **`product_images`**: id, product_id, image_path, is_primary.
5.  **`promos`**: id, code, discount_amount, start_date, end_date.
6.  **`carts`**: id, user_id, product_id, quantity.
7.  **`orders` (Leads)**: id, user_id, total_price, status, event_date, shipping_address, instagram_account. (Catatan: Tabel ini menyimpan data checkout sebelum diarahkan ke WA agar admin punya rekap data di web).