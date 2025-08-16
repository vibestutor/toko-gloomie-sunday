<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\CheckoutController;                // applyDiscount/success/failed
use App\Http\Controllers\Webhook\XenditWebhookController;   // webhook

// =====================
// PUBLIC PAGES
// =====================

// Home (landing)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Listing & detail produk (detail pakai route model binding by slug)
Route::get('/products',               [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}',[ProductController::class, 'show'])->name('products.show');

// AJAX Search untuk header search bar (dipanggil di script.js -> /search?q=...)
// Controller harus return JSON array: [{ slug, name, image, price }, ...]
Route::get('/search', [ProductController::class, 'search'])->name('search');

// Simpan preferensi locale & currency (dipanggil currency-lang.js & FAB)
// Expect body JSON: { locale: 'id-ID', currency: 'IDR' }
Route::post('/pref', [PreferenceController::class, 'update'])->name('pref.update');


// =====================
// CART
// =====================

// Halaman cart
Route::get('/cart', [CartController::class, 'view'])->name('cart.view');

// Tambah ke cart (dipakai oleh script.js handleFormSubmit -> POST /add-to-cart)
Route::post('/add-to-cart', [CartController::class, 'store'])->name('cart.store');

// (Opsional) alias lama – tetap boleh dipertahankan bila Blade lama masih panggil ini
Route::get('/cart/index', [CartController::class, 'view'])->name('cart.index');
Route::post('/cart',      [CartController::class, 'store'])->name('cart.add');

// Operasi item cart (quantity update/remove) –
// Blade form harus pakai @method('PATCH') / @method('DELETE') + @csrf
Route::patch('/cart/item/{item}',  [CartController::class, 'update'])->name('cart.update'); // ubah qty
Route::delete('/cart/item/{item}', [CartController::class, 'remove'])->name('cart.remove'); // hapus 1 item
Route::delete('/cart/clear',       [CartController::class, 'clear'])->name('cart.clear');   // kosongkan cart


// =====================
// CHECKOUT / ORDERS
// =====================

// Halaman checkout (GET) + submit order (POST form -> route('order.store'))
Route::get('/checkout',  [OrderController::class, 'checkoutView'])->name('checkout.view');
Route::post('/checkout', [OrderController::class, 'store'])->name('order.store');

// Buy Now (dipanggil XHR dari script.js -> POST /buy-now)
Route::post('/buy-now',  [OrderController::class, 'buyNow'])->name('order.buyNow');

// Apply discount code (dipanggil XHR dari script.js -> POST /checkout/apply-discount)
Route::post('/checkout/apply-discount', [CheckoutController::class, 'applyDiscount'])->name('checkout.applyDiscount');

// Redirect page setelah pembayaran (gateway mengembalikan ke sini)
// NOTE: Kalau Order kamu pakai UUID/kode, bisa jadi {order:uuid}/{order:code}
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/failed/{order}',  [CheckoutController::class, 'failed'])->name('checkout.failed');


// =====================
// WEBHOOKS (Payment Gateway, dsb.)
// =====================

// Xendit webhook (pastikan dikecualikan dari CSRF di App\Http\Middleware\VerifyCsrfToken)
Route::post('/webhooks/xendit', [XenditWebhookController::class, 'handle'])->name('webhooks.xendit');


// =====================
// DASHBOARD (opsional, login required)
// =====================
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// =====================
// PROFILE (auth only)
// =====================
Route::middleware('auth')->group(function () {
    Route::get('/profile',   [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');
});


// =====================
// AUTH ROUTES (Breeze/Fortify/etc)
// =====================
require __DIR__.'/auth.php';
