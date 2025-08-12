<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

// Cart (view) + endpoint untuk JS
Route::get('/cart', [CartController::class, 'view'])->name('cart.view');
Route::post('/add-to-cart', [CartController::class, 'store'])->name('cart.store');

// Checkout (view) + endpoint untuk JS
Route::get('/checkout', [OrderController::class, 'checkoutView'])->name('checkout.view');
Route::post('/buy-now', [OrderController::class, 'buyNow'])->name('order.buyNow');
