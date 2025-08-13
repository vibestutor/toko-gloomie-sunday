<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

// =====================
// Home
// =====================
Route::get('/', [HomeController::class, 'index'])->name('home');

// =====================
// Products
// =====================
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

// =====================
// Cart (VIEW + ENDPOINT)
// =====================
Route::get('/cart', [CartController::class, 'view'])->name('cart.view');
Route::post('/add-to-cart', [CartController::class, 'store'])->name('cart.store');

// Optional alias (tidak wajib, tapi siap kalau mau dipakai di front-end)
Route::get('/cart/index', [CartController::class, 'view'])->name('cart.index');
Route::post('/cart',      [CartController::class, 'store'])->name('cart.add');

// RESTful opsional (pastikan method ada di CartController sebelum diaktifkan)
Route::patch('/cart/item/{item}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/item/{item}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// =====================
// Checkout
// =====================
Route::get('/checkout', [OrderController::class, 'checkoutView'])->name('checkout.view');
Route::post('/checkout', [OrderController::class, 'store'])->name('order.store'); // <-- ini penting
Route::post('/buy-now', [OrderController::class, 'buyNow'])->name('order.buyNow');
