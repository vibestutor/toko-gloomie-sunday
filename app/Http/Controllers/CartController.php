<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function view()
    {
        $cart = session()->get('cart', []);
        return view('cart', compact('cart'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'color'      => 'required|string',
            'size'       => 'required|string',
            'qty'        => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($data['product_id']);

        $cart = session()->get('cart', []);
        $cart[] = [
            'id'        => $product->id,
            'name'      => $product->name,
            'color'     => $data['color'],
            'size'      => $data['size'],
            'qty'       => $data['qty'],
            'price'     => $product->price,
            'image_url' => $product->image_url,
        ];
        session()->put('cart', $cart);

        // JS kita expect JSON, lalu redirect di sisi front-end
        return response()->json(['ok' => true]);
    }
}
