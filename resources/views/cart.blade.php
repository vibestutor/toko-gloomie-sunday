@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<main class="container" style="padding-top: 100px; max-width: 1200px; margin: auto;">
    <h1>Shopping Cart</h1>

    @if(session('cart') && count(session('cart')) > 0)
        <table class="cart-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="text-align:left;">Product</th>
                    <th>Color</th>
                    <th>Size</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach(session('cart') as $item)
                    @php
                        $total = $item['price'] * $item['qty'];
                        $grandTotal += $total;
                    @endphp
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['color'] }}</td>
                        <td>{{ $item['size'] }}</td>
                        <td>{{ $item['qty'] }}</td>
                        <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h3 style="margin-top: 20px;">Grand Total: Rp {{ number_format($grandTotal, 0, ',', '.') }}</h3>

        <a href="{{ route('checkout.view') }}" class="btn-buy-now" style="display:inline-block; padding:10px 20px; background:#0034b7; color:white; border-radius:4px; text-decoration:none;">Proceed to Checkout</a>
    @else
        <p>Your cart is empty.</p>
    @endif
</main>
@endsection
