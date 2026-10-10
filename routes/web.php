<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Publik
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

// Katalog Produk
Route::get('/katalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog', fn () => redirect()->route('catalog.index'));

// Detail Produk
Route::get('/produk/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/product/{slug}', fn ($slug) => redirect()->route('product.show', $slug));

// Halaman Statis Pendukung
Route::get('/cara-pesan', fn () => view('pages.how-to-order'))->name('how-to-order');
Route::get('/how-to-order', fn () => redirect()->route('how-to-order'));

Route::get('/kontak', fn () => view('pages.contact'))->name('contact');
Route::get('/contact', fn () => redirect()->route('contact'));

// Placeholder Keranjang & Checkout untuk Fase 3
Route::get('/keranjang', function () {
    return redirect()->route('catalog.index')->with('info', 'Sistem Keranjang & Checkout akan diaktifkan penuh pada Fase 3.');
})->name('cart');
Route::get('/cart', fn () => redirect()->route('cart'));

Route::post('/keranjang', function () {
    return redirect()->route('cart')->with('info', 'Produk berhasil dipilih! Alur keranjang & checkout lengkap akan diaktifkan pada Fase 3.');
});

/*
|--------------------------------------------------------------------------
| Member Dashboard & Profile
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Panel (Terlindungi Middleware 'admin')
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Kerangka route kosong fase 1 & 2 (akan diimplementasikan di Fase 4)
    Route::get('/products', fn () => redirect()->route('admin.dashboard')->with('info', 'Manajemen Produk akan diaktifkan pada Fase 4.'));
    Route::get('/categories', fn () => redirect()->route('admin.dashboard')->with('info', 'Manajemen Kategori akan diaktifkan pada Fase 4.'));
    Route::get('/promos', fn () => redirect()->route('admin.dashboard')->with('info', 'Manajemen Promo akan diaktifkan pada Fase 4.'));
    Route::get('/banners', fn () => redirect()->route('admin.dashboard')->with('info', 'Manajemen Banner akan diaktifkan pada Fase 4.'));
    Route::get('/orders', fn () => redirect()->route('admin.dashboard')->with('info', 'Manajemen Pesanan akan diaktifkan pada Fase 4.'));
    Route::get('/members', fn () => redirect()->route('admin.dashboard')->with('info', 'Manajemen Member akan diaktifkan pada Fase 4.'));
    Route::get('/settings', fn () => redirect()->route('admin.dashboard')->with('info', 'Pengaturan akan diaktifkan pada Fase 4.'));
});

require __DIR__.'/auth.php';
