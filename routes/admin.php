<?php

use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PromoController;
use App\Http\Controllers\Admin\PromotionPopupController;
use App\Http\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
| Middleware: web, auth, admin (Applied in bootstrap/app.php)
| Prefix: /admin, Name prefix: admin.
|--------------------------------------------------------------------------
*/

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('index');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/analytics', [DashboardController::class, 'analytics'])->name('dashboard.analytics');

// Products Management
Route::delete('/products/images/{productImage}', [ProductController::class, 'destroyImage'])->name('products.images.destroy');
Route::resource('products', ProductController::class);

// Categories Management
Route::resource('categories', CategoryController::class);

// Promos Management
Route::resource('promos', PromoController::class);

// Banners Management
Route::resource('banners', BannerController::class);

// Promotional Popup Management
Route::get('/popup-promotion', [PromotionPopupController::class, 'index'])->name('popup.index');
Route::put('/popup-promotion', [PromotionPopupController::class, 'update'])->name('popup.update');

// Orders / Leads Management
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/export', [OrderController::class, 'exportCsv'])->name('orders.export');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

// Members Management
Route::get('/members', [MemberController::class, 'index'])->name('members.index');
Route::get('/members/export', [MemberController::class, 'exportCsv'])->name('members.export');
Route::get('/members/{user}', [MemberController::class, 'show'])->name('members.show');

// Settings Management
Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
