<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Schema;

class ProductController extends Controller
{
    /**
     * Halaman katalog
     */
    public function index()
    {
        $products = Product::select(['id','name','slug','price','image_url','image_hover_url'])
            ->latest()
            ->paginate(12);

        return view('products.index', compact('products'));
    }

    /**
     * Halaman detail produk:
     * - Ambil SEMUA varian dari DB (hasil seeder)
     * - Related products berdasarkan kategori
     */
    public function show(Product $product)
{
    // Amanin kalau tabel varian belum ada
    if (\Illuminate\Support\Facades\Schema::hasTable('product_variants')) {
        // urutan sudah di-handle di relasi variants()
        $variants = $product->variants()
            ->select(['id','product_id','color','image_url','image_hover_url','price','stock'])
            ->get();
    } else {
        $variants = collect();
    }

    $relatedProducts = Product::where('category_id', $product->category_id)
        ->where('id', '!=', $product->id)
        ->select(['id','name','slug','price','image_url','image_hover_url'])
        ->inRandomOrder()
        ->take(8)
        ->get();

    return view('products.show', [
        'product'         => $product,
        'variants'        => $variants,
        'relatedProducts' => $relatedProducts,
    ]);
}
}
