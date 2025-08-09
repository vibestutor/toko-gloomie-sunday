<?php

namespace App\Http\Controllers;

use App\Models\Product; // Memanggil Model Product
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar semua produk (halaman katalog).
     */
    public function index()
    {
        // Ambil semua data dari tabel 'products'
        $products = Product::all();

        // Tampilkan view dan kirim data products ke dalamnya
        return view('products.index', ['products' => $products]);
    }

    /**
     * Menampilkan detail satu produk.
     */
    public function show(Product $product)
    {
        // Ambil varian warna untuk produk ini
        $variants = $product->variants()
            ->select(['id', 'color', 'image_url', 'image_hover_url', 'price', 'stock'])
            ->orderBy('color')
            ->get();

        // Ambil 4 produk lain dari kategori yang sama,
        // kecuali produk yang sedang dilihat saat ini.
        $relatedProducts = Product::where('category_id', $product->category_id)
                            ->where('id', '!=', $product->id)
                            ->select(['id', 'name', 'slug', 'price', 'image_url', 'image_hover_url']) // Pilih hanya kolom yang perlu
                            ->take(8)
                            ->inRandomOrder() // Bonus: Tampilkan produk terkait secara acak setiap kali halaman dimuat
                            ->get();

        // Kirim data produk utama DAN produk terkait ke view
        return view('products.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'variants' => $variants,
        ]);
    }
}