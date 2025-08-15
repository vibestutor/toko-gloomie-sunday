<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Session;

class MergeCartOnLogin
{
    public function handle(Login $event): void
    {
        $sessionCart = Session::get('cart', []); // contoh: [['product_id'=>1,'qty'=>2], ...]
        if (empty($sessionCart)) return;

        $user = $event->user;

        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        foreach ($sessionCart as $row) {
            $item = $cart->items()->firstOrNew(['product_id' => $row['product_id']]);
            $item->qty = ($item->qty ?? 0) + (int)($row['qty'] ?? 1);
            $item->save();
        }

        Session::forget('cart');
    }
}
