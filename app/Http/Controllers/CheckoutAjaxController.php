<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutAjaxController extends Controller
{
    public function applyDiscount(Request $r)
    {
        $data = $r->validate(['code' => 'nullable|string']);
        $code = strtoupper(trim($data['code'] ?? ''));

        // Ambil items dari session (buy_now atau cart)
        $items   = session('buy_now')
            ? [ $this->mapBuyNowToItem(session('buy_now')) ]
            : (array) session('cart', []);
        $subtotal = collect($items)->sum(fn($i)=> (int)$i['qty'] * (float)$i['price']);

        // Contoh aturan diskon (ganti sesuai bisnis)
        $discount = 0;
        if ($code === 'GLOOMIE10') {
            $discount = (int) round($subtotal * 0.10);
        } elseif ($code === 'FREESHIP') {
            // biarkan discount 0; nanti ongkir di set 0 kalau dipakai bersamaan
        }

        session(['checkout_discount_amount' => $discount]);

        $shipping = (int) session('checkout_shipping_amount', 0);
        if ($code === 'FREESHIP') $shipping = 0;

        $total = max(0, $subtotal - $discount + $shipping);

        return response()->json(compact('subtotal','discount','shipping','total'));
    }

    public function applyShipping(Request $r)
    {
        // Bisa dikembangkan panggil agregator di sini.
        $data = $r->validate([
            'service' => 'required|string',
            'amount'  => 'required|integer|min:0', // base IDR
        ]);

        session(['checkout_shipping_amount' => (int)$data['amount']]);

        // hitung ulang
        $items   = session('buy_now')
            ? [ $this->mapBuyNowToItem(session('buy_now')) ]
            : (array) session('cart', []);
        $subtotal = collect($items)->sum(fn($i)=> (int)$i['qty'] * (float)$i['price']);
        $discount = (int) session('checkout_discount_amount', 0);
        $shipping = (int) session('checkout_shipping_amount', 0);
        $total    = max(0, $subtotal - $discount + $shipping);

        return response()->json(compact('subtotal','discount','shipping','total'));
    }

    private function mapBuyNowToItem(array $data): array
    {
        $p = \App\Models\Product::find($data['product_id']);
        return [
            'id'        => $p?->id,
            'name'      => $p?->name ?? 'Unknown Product',
            'color'     => $data['color'] ?? null,
            'size'      => $data['size'] ?? null,
            'qty'       => (int) ($data['qty'] ?? 1),
            'price'     => (float) ($p?->price ?? 0), // BASE IDR
            'image_url' => $p?->image_url,
        ];
    }
}
