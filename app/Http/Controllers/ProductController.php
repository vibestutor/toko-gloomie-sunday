<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Katalog produk (grid).
     *
     * Catatan:
     * - Tidak pakai ->select([...]) demi kompatibilitas schema (image_url vs image).
     * - View bisa tetap akses $product->image_url atau $product->image sesuai tabel kamu.
     */
    public function index()
    {
        $products = Product::query()
            ->latest()
            ->paginate(12);

        return view('products.index', compact('products'));
    }

    /**
     * Detail produk.
     *
     * - Mengambil varian jika tabel/relasi ada (aman kalau belum ada).
     * - Related products berdasar category_id (aman kalau null).
     */
    public function show(Product $product)
    {
        // Ambil varian kalau tabel & relasi tersedia
        $variants = collect();
        if (Schema::hasTable('product_variants') && method_exists($product, 'variants')) {
            // Tidak pakai select spesifik agar kompatibel ke semua skema
            $variants = $product->variants()->get();
        }

        // Produk terkait (aman bila category_id null)
        $relatedProducts = Product::query()
            ->when(!is_null($product->category_id), function ($q) use ($product) {
                $q->where('category_id', $product->category_id);
            })
            ->whereKeyNot($product->getKey())
            ->inRandomOrder()
            ->take(8)
            ->get();

        return view('products.show', [
            'product'         => $product,
            'variants'        => $variants,
            'relatedProducts' => $relatedProducts,
        ]);
    }

    /**
     * AJAX Search untuk header (script.js -> GET /search?q=...)
     * Mengembalikan JSON array: [{ slug, name, image, price }, ...]
     * - price: base dalam IDR (integer), untuk dirender client (currency-lang.js)
     * - image: full URL (prioritas image_url; fallback image dari storage)
     */
    public function search(Request $request)
    {
        $q = trim($request->query('q', ''));

        if ($q === '') {
            return response()->json([]);
        }

        // Query sederhana + limit; tambahkan index di migrations (name, sku, slug) agar cepat
        $products = Product::query()
            ->where(function ($qq) use ($q) {
                $qq->where('name', 'like', "%{$q}%")
                   ->orWhere('sku', 'like', "%{$q}%")
                   ->orWhere('slug', 'like', "%{$q}%");
            })
            ->limit(10)
            ->get();

        $out = $products->map(function ($p) {
            return [
                'slug'  => (string) $p->slug,
                'name'  => (string) $p->name,
                // base price dalam IDR; cast ke int supaya aman di klien
                'price' => (int) round((float) $p->price ?? 0),
                // Full URL gambar yang aman (lihat helper di bawah)
                'image' => $this->productPrimaryImage($p),
            ];
        })->values();

        return response()->json($out);
    }

    /* =========================================================
     * Helpers — resolusi URL gambar yang fleksibel & aman
     * ========================================================= */

    /**
     * Ambil URL gambar utama untuk kartu/search:
     * - Prioritas: image_url (bila sudah URL penuh), atau image_url sebagai path relatif (dibungkus asset()).
     * - Fallback: kolom "image" (umum dipakai untuk path file di storage).
     * - Jika tetap null, return null (JS akan pakai placeholder).
     */
    protected function productPrimaryImage(Product $p): ?string
    {
        // Prioritas pakai image_url kalau ada
        if (!empty($p->image_url)) {
            return $this->resolveImageUrl($p->image_url);
        }

        // Fallback: kolom "image" (biasanya path relatif storage)
        if (!empty($p->image)) {
            return $this->resolveImageUrl($p->image);
        }

        // Fallback lain yang umum dipakai di beberapa skema
        if (!empty($p->image_path)) {
            return $this->resolveImageUrl($p->image_path);
        }

        return null;
    }

    /**
     * Ambil URL gambar hover (opsional), default ke primary jika tidak ada.
     */
    protected function productHoverImage(Product $p): ?string
    {
        if (!empty($p->image_hover_url)) {
            return $this->resolveImageUrl($p->image_hover_url);
        }
        if (!empty($p->hover_image)) {
            return $this->resolveImageUrl($p->hover_image);
        }
        return $this->productPrimaryImage($p);
    }

    /**
     * Normalisasi path gambar menjadi URL absolut:
     * - Jika sudah http(s):// → kembalikan apa adanya.
     * - Jika diawali "/" → bungkus asset('/...').
     * - Selain itu → anggap path relatif storage → asset('storage/...').
     */
    protected function resolveImageUrl($path): ?string
    {
        if (empty($path)) {
            return null;
        }

        $s = (string) $path;

        // Sudah absolute URL?
        if (preg_match('#^https?://#i', $s)) {
            return $s;
        }

        // Path absolute di public (misal "/storage/xxx.jpg" atau "/uploads/xxx.jpg")
        if (Str::startsWith($s, '/')) {
            return asset(ltrim($s, '/'));
        }

        // Path relatif (umumnya file disimpan di storage/app/public) => publish ke /storage
        return asset('storage/' . ltrim($s, '/'));
    }
}
