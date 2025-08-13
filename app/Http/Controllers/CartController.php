<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService; // <-- pakai service DB-based
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
            'variant_id' => 'nullable|exists:product_variants,id', // ganti “color/size” jadi variant_id bila perlu
            'qty'        => 'required|integer|min:1',
        ]);

        try {
            $this->cart->add(
                (int)$data['product_id'],
                $data['variant_id'] ?? null,
                (int)$data['qty']
            );
            return response()->json(['ok' => true]);
        } catch (ValidationException $e) {
            return response()->json([
                'ok' => false,
                'errors' => $e->errors(),
            ], 422);
        }
    }

    // ==== UPDATE QTY ====
    public function update(Request $request, int $itemId)
    {
        $data = $request->validate(['qty' => 'required|integer|min:1']);
        try {
            $cart = $this->cart->updateQty($itemId, (int)$data['qty']);
            return back()->with('ok','Quantity updated');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }

    // ==== REMOVE ITEM ====
    public function remove(int $itemId)
    {
        $this->cart->remove($itemId);
        return back()->with('ok','Item removed');
    }

    // ==== CLEAR CART ====
    public function clear()
    {
        $this->cart->clear();
        return back()->with('ok','Cart cleared');
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
