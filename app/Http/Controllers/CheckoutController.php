<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function view(Request $request)
    {
        // view akan hitung lagi subtotal/discount/total dari session di Blade
        return view('checkout');
    }

    public function applyDiscount(Request $request)
    {
        $code = trim($request->input('code',''));

        // Contoh rule diskon: DEMO10 = 10% dari subtotal (base IDR)
        if ($code === '') {
            session()->forget(['checkout_discount_code','checkout_discount_amount','checkout_discount_msg']);
            return back();
        }

        // Hitung subtotal base dari session cart/buy_now
        $buyNow = session('buy_now');
        $cart   = session('cart', []);
        $items  = $buyNow ? [ $buyNow ] : $cart;

        $subtotal = 0;
        foreach ($items as $it) {
            $qty   = (int)($it['qty'] ?? 1);
            $price = (float)($it['price'] ?? 0);
            $subtotal += ($qty * $price);
        }

        $discountAmount = 0;
        $msg = '';

        if (strcasecmp($code, 'DEMO10') === 0) {
            $discountAmount = round($subtotal * 0.10); // 10%
            $msg = 'Discount 10% applied.';
        } elseif (strcasecmp($code, 'DEMO50K') === 0) {
            $discountAmount = 50000; // potongan 50k
            $msg = 'Discount Rp 50.000 applied.';
        } else {
            session([
                'checkout_discount_code'   => $code,
                'checkout_discount_amount' => 0,
                'checkout_discount_msg'    => 'Invalid discount code.',
            ]);
            return back();
        }

        session([
            'checkout_discount_code'   => $code,
            'checkout_discount_amount' => max(0, min($discountAmount, $subtotal)),
            'checkout_discount_msg'    => $msg,
        ]);

        return back();
    }

    public function success(Request $request, $orderId)
    {
        // TODO: Tandai order paid (verifikasi dari webhook lebih aman)
        return view('checkout-success', ['orderId' => $orderId]);
    }

    public function failed(Request $request, $orderId = null)
    {
        return view('checkout-failed', ['orderId' => $orderId]);
    }
}
