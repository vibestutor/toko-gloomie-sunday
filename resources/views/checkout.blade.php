@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<main class="container" style="padding-top: 100px; max-width: 800px; margin: auto;">
    <h1>Checkout</h1>

    <form action="{{ route('order.buyNow') }}" method="POST">
        @csrf
        {{-- Shipping Info --}}
        <div style="margin-bottom: 20px;">
            <label for="name">Full Name</label><br>
            <input type="text" id="name" name="name" required style="width:100%; padding:8px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="address">Shipping Address</label><br>
            <textarea id="address" name="address" required style="width:100%; padding:8px;"></textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="phone">Phone Number</label><br>
            <input type="text" id="phone" name="phone" required style="width:100%; padding:8px;">
        </div>

        {{-- Payment Info --}}
        <div style="margin-bottom: 20px;">
            <label for="payment_method">Payment Method</label><br>
            <select id="payment_method" name="payment_method" style="width:100%; padding:8px;">
                <option value="xendit">Xendit Payment</option>
                <option value="cod">Cash on Delivery</option>
            </select>
        </div>

        <button type="submit" class="btn-buy-now" style="padding:10px 20px; background:#0034b7; color:white; border:none; border-radius:4px;">Place Order</button>
    </form>
</main>
@endsection
