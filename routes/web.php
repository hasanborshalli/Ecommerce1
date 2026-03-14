<?php

use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;

use Illuminate\Support\Facades\Route;
// ═══════════════════════════════════════════════════════
//  STOREFRONT
// ═══════════════════════════════════════════════════════

Route::get('/',               [HomeController::class, 'index'])->name('home');
Route::get('/about',          [HomeController::class, 'about'])->name('about');

// Shop
Route::get('/shop',                        [ProductController::class, 'index'])->name('shop');
Route::get('/shop/category/{category:slug}', [ProductController::class, 'category'])->name('shop.category');
Route::get('/shop/{product:slug}',         [ProductController::class, 'show'])->name('product.show');

// Cart (session-based)
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/',              [CartController::class, 'index'])->name('index');
    Route::post('/add',          [CartController::class, 'add'])->name('add');
    Route::patch('/update',      [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{rowId}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear',      [CartController::class, 'clear'])->name('clear');
    Route::get('/count',         [CartController::class, 'count'])->name('count');
});

// Checkout
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/',       [CheckoutController::class, 'index'])->name('index');
    Route::post('/place', [CheckoutController::class, 'place'])->name('place');
});
Route::get('/order/confirmation/{orderNumber}', [CheckoutController::class, 'confirmation'])->name('order.confirmation');

// Contact
Route::get('/contact',        [ContactController::class, 'index'])->name('contact');
Route::post('/contact/send',  [ContactController::class, 'send'])->name('contact.send');

// ═══════════════════════════════════════════════════════
//  ADMIN PANEL
// ═══════════════════════════════════════════════════════

Route::prefix('admin')->name('admin.')->group(function () {

    // Auth (no middleware)
    Route::get('/',         [AdminAuthController::class, 'showLogin']);
    Route::get('/login',         [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login',        [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout',       [AdminAuthController::class, 'logout'])->name('logout');

    // Protected admin routes
    Route::middleware('admin.auth')->group(function () {
        
        Route::get('/dashboard',  [AdminDashboardController::class, 'index'])->name('dashboard');

        // Products
        Route::resource('products', AdminProductController::class);
        Route::post('products/{product}/toggle-active', [AdminProductController::class, 'toggleActive'])->name('products.toggle-active');

        // Categories
        Route::resource('categories', AdminCategoryController::class);

        // Orders
        Route::get('orders',                         [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}',                 [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status',        [AdminOrderController::class, 'updateStatus'])->name('orders.status');

        // Settings
        Route::get('settings',  [AdminSettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [AdminSettingController::class, 'update'])->name('settings.update');

        // Messages
        Route::get('messages',              [AdminSettingController::class, 'messages'])->name('messages.index');
        Route::patch('messages/{message}/read', [AdminSettingController::class, 'markRead'])->name('messages.read');
    });
});