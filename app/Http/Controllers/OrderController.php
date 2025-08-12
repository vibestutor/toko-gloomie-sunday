<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function checkoutView()
    {
        $buyNow = session()->get('buy_now'); // optional, kalau ada flow buy now
        return view('checkout', compact('buyNow'));
    }

    public function buyNow(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer',
            'color'      => 'required|string',
            'size'       => 'required|string',
            'qty'        => 'required|integer|min:1',
        ]);

        session()->put('buy_now', $data);

        // JS kita expect JSON, lalu redirect di sisi front-end
        return response()->json(['ok' => true]);
    }
}
