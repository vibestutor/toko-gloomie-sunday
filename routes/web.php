<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PreferenceController;

// =====================
// PUBLIC PAGES
// =====================
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::post('/pref', [PreferenceController::class, 'update'])->name('pref.update');

// =====================
// CART
// =====================
Route::get('/cart', [CartController::class, 'view'])->name('cart.view');
Route::post('/add-to-cart', [CartController::class, 'store'])->name('cart.store');

// Optional alias (kalau dipakai di front-end)
Route::get('/cart/index', [CartController::class, 'view'])->name('cart.index');
Route::post('/cart',      [CartController::class, 'store'])->name('cart.add');

// RESTful item ops (Route Model Binding: {item} -> CartItem)
Route::patch('/cart/item/{item}',  [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/item/{item}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear',       [CartController::class, 'clear'])->name('cart.clear');

// =====================
// CHECKOUT / ORDERS
// =====================
Route::get('/checkout',  [OrderController::class, 'checkoutView'])->name('checkout.view');
Route::post('/checkout', [OrderController::class, 'store'])->name('order.store');
Route::post('/buy-now',  [OrderController::class, 'buyNow'])->name('order.buyNow');

// =====================
// DASHBOARD (opsional)
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
// AUTH ROUTES (Breeze)
// =====================
require __DIR__.'/auth.php';
