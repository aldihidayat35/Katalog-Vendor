<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderHistoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
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

// XML Sitemap SEO
Route::get('/sitemap.xml', function () {
    $products = \App\Models\Product::active()
        ->whereHas('category', fn ($q) => $q->where('is_active', true))
        ->select(['slug', 'updated_at'])
        ->get();

    $categories = \App\Models\Category::active()
        ->select(['slug', 'updated_at'])
        ->get();

    $content = view('sitemap', compact('products', 'categories'))->render();

    return response($content, 200, [
        'Content-Type' => 'application/xml; charset=UTF-8',
    ]);
})->name('sitemap');

// Helper route for guest add-to-cart prompt
Route::get('/keranjang/masuk', function (Request $request) {
    session()->put('url.intended', $request->query('redirect', route('home')));
    session()->flash('info', 'Silakan login untuk menambahkan ke keranjang');
    return redirect()->route('login');
})->name('cart.login-prompt');

/*
|--------------------------------------------------------------------------
| Member Routes (Middleware: auth)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Keranjang Belanja
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::get('/cart', fn () => redirect()->route('cart.index'))->name('cart');
    Route::post('/keranjang', [CartController::class, 'store'])->middleware('throttle:30,1')->name('cart.store');
    Route::patch('/keranjang/{cart}', [CartController::class, 'update'])->middleware('throttle:30,1')->name('cart.update');
    Route::delete('/keranjang/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('/keranjang/promo', [CartController::class, 'applyPromo'])->middleware('throttle:15,1')->name('cart.promo.apply');
    Route::delete('/keranjang/promo', [CartController::class, 'removePromo'])->name('cart.promo.remove');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->middleware('throttle:10,1')->name('checkout.store');
    Route::get('/checkout/sukses/{order_number}', [CheckoutController::class, 'success'])->name('checkout.success');

    // Riwayat Pesanan
    Route::get('/pesanan-saya', [OrderHistoryController::class, 'index'])->name('order.index');
    Route::get('/pesanan-saya/{order}', [OrderHistoryController::class, 'show'])->name('order.show');

    // Profil Member
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit.id');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profil', [ProfileController::class, 'update'])->name('profile.update.id');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::put('/profil/password', [ProfileController::class, 'updatePassword']);
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profil', [ProfileController::class, 'destroy'])->name('profile.destroy.id');

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

require __DIR__.'/auth.php';
