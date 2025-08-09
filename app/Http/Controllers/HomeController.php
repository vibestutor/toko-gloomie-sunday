<?php

namespace App\Http\Controllers;

use App\Models\Product; // <-- Memanggil Model Product
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama (homepage).
     */
    public function index()
    {
        // Ambil 4 produk terbaru dari database
        $featuredProducts = Product::latest()->take(16)->get();

        // Tampilkan view 'home.blade.php' dan kirim data produk unggulan ke sana
        return view('home', ['featuredProducts' => $featuredProducts]);
    }
}