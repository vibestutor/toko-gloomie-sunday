<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sinilah Anda bisa mendaftarkan semua alamat URL untuk website Anda.
|
*/

// Rute untuk Halaman Utama (Homepage)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rute untuk Halaman Katalog (Semua Produk)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Rute untuk Halaman Detail Produk (Satu Produk)
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');