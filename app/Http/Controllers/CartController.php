<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService; // <-- pakai service DB-based
use App\Models\CartItem;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    public function __construct(protected CartService $cart) {}

    // ==== VIEW CART ====
    public function view()
    {
        // migrasi sekali dari session ke DB (kalau masih ada sisa data lama)
        $this->migrateSessionCart();

        $cart = $this->cart->current()->load('items.product','items.variant');
        return view('cart', compact('cart'));
    }

    // ==== ADD TO CART (AJAX JSON) ====
    public function store(Request $request)
{
    $data = $request->validate([
        'product_id' => 'required|exists:products,id',
        'variant_id' => 'nullable|exists:product_variants,id',
        'qty'        => 'required|integer|min:1',
    ]);

    try {
        $this->cart->add(
            (int)$data['product_id'],
            $data['variant_id'] ?? null,
            (int)$data['qty']
        );

        // Jika AJAX/JSON:
        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        // Jika form biasa:
        return redirect()->route('cart.view')->with('success', 'Item ditambahkan ke cart.');

    } catch (ValidationException $e) {

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => false,
                'errors' => $e->errors(),
            ], 422);
        }

        return back()->withErrors($e->errors());
    }
}
// ==== UPDATE QTY ====
public function update(Request $request, CartItem $item)
{
    $data = $request->validate(['qty' => 'required|integer|min:1']);
    try {
        $this->cart->updateQty($item->id, (int)$data['qty']);  // <- perhatiin: id (huruf kecil)
        return redirect()->route('cart.view')->with('success','Quantity diupdate.');
    } catch (ValidationException $e) {
        return back()->withErrors($e->errors());
    }
}

public function remove(CartItem $item)
{
    $this->cart->remove($item->id); // <- id (huruf kecil)
    return redirect()->route('cart.view')->with('success','Item dihapus.');
}

// ==== CLEAR CART ====
public function clear()
{
    $this->cart->clear();
    return redirect()->route('cart.view')->with('success','Cart dikosongkan.');
}
    /**
     * Migrasi cart lama (session) ke DB sekali lalu hapus dari session.
     * Ini menjaga user yang sudah sempat add to cart sebelum upgrade.
     */
    protected function migrateSessionCart(): void
    {
        $legacy = session()->get('cart');
        if (!$legacy || !is_array($legacy)) return;

        foreach ($legacy as $row) {
            // kalau dulu simpan color/size string, kamu bisa map ke variant_id di sini.
            $variantId = $row['variant_id'] ?? null;
            $productId = (int)($row['id'] ?? $row['product_id'] ?? 0);
            $qty       = (int)($row['qty'] ?? 1);
            if ($productId > 0 && $qty > 0) {
                try {
                    $this->cart->add($productId, $variantId, $qty);
                } catch (\Throwable $e) {
                    // skip baris yang tidak valid (stok/harga berubah, dsb)
                }
            }
        }
        session()->forget('cart');
    }
}
