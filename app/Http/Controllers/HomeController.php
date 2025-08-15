<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil semua kolom yang ada (tanpa 'select' manual, jadi gak error walau 'image' gak ada)
        $featuredProducts = Product::latest()->take(16)->get();

        return view('home', compact('featuredProducts'));
    }
}