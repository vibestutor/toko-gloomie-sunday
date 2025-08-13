@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
@php
  // Ambil data dari session
  $buyNow = session('buy_now');                 // single item (opsional)
  $cart   = session('cart', []);               // array items
  // Bentuk array items yang akan ditampilkan
  $items  = $buyNow ? [ (object) [
      'name'      => $buyNow['name']      ?? ($buyNow['product_name'] ?? 'Item'),
      'price'     => (float)($buyNow['price'] ?? 0),
      'qty'       => (int)($buyNow['qty'] ?? 1),
      'color'     => $buyNow['color']     ?? null,
      'size'      => $buyNow['size']      ?? null,
      'image_url' => $buyNow['image_url'] ?? null,
  ] ] : collect($cart)->map(function($c){
      return (object)[
        'name'      => $c['name']      ?? 'Item',
        'price'     => (float)($c['price'] ?? 0),
        'qty'       => (int)($c['qty'] ?? 1),
        'color'     => $c['color']     ?? null,
        'size'      => $c['size']      ?? null,
        'image_url' => $c['image_url'] ?? null,
      ];
  })->all();

  $subtotal = 0;
  foreach ($items as $it) { $subtotal += ($it->price * $it->qty); }
@endphp

<main class="checkout-container page-checkout" style="padding-top: 90px;">
  {{-- Kolom kiri: Form data penerima --}}
  <section class="customer-info">
    <h1 class="title" style="border-bottom:none; padding-bottom:0; margin-bottom:20px;">Checkout</h1>

    {{-- Notifikasi error validasi --}}
    @if ($errors->any())
      <div class="notice-box" style="margin-bottom:15px;">
        <strong>Periksa kembali isianmu:</strong>
        <ul style="margin:8px 0 0 18px; text-align:left;">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('order.store') }}" method="POST">
      @csrf

      <div class="form-section">
        <h2>Shipping Information</h2>
        <div class="form-group">
          <label for="name">Full Name</label>
          <input id="name" name="name" type="text" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
          <label for="address">Shipping Address</label>
          <textarea id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="phone">Phone Number</label>
            <input id="phone" name="phone" type="text" value="{{ old('phone') }}" required>
          </div>
          <div class="form-group">
            <label for="postal_code">Postal Code (optional)</label>
            <input id="postal_code" name="postal_code" type="text" value="{{ old('postal_code') }}">
          </div>
        </div>
      </div>

      <div class="form-section">
        <h2>Payment Method</h2>
        {{-- Gaya sesuai CSS kamu (radio-group) --}}
        <div class="radio-group">
          <div class="radio-option {{ old('payment_method','xendit')==='xendit' ? 'selected' : '' }}">
            <label>
              <input type="radio" name="payment_method" value="xendit" {{ old('payment_method','xendit')==='xendit' ? 'checked' : '' }}>
              Xendit Payment
            </label>
            <div class="payment-description">
              <small>Pembayaran via virtual account/e-wallet yang disediakan Xendit.</small>
            </div>
          </div>

          <div class="radio-option {{ old('payment_method')==='cod' ? 'selected' : '' }}">
            <label>
              <input type="radio" name="payment_method" value="cod" {{ old('payment_method')==='cod' ? 'checked' : '' }}>
              Cash on Delivery (COD)
            </label>
            <div class="payment-description">
              <small>Bayar saat pesanan diterima (tersedia di area tertentu).</small>
            </div>
          </div>
        </div>
      </div>

      {{-- Tombol submit versi mobile (desktop tombolnya di kanan - order summary) --}}
      <div class="form-actions mobile-summary">
        <button type="submit" class="checkout-btn">Place Order</button>
      </div>
    </form>
  </section>

  {{-- Kolom kanan: Ringkasan pesanan --}}
  <aside class="order-summary">
    <h3 style="margin-top:0;">Order Summary</h3>

    @forelse ($items as $it)
      <div class="product-item">
        <div class="product-image">
          <img src="{{ $it->image_url ? asset($it->image_url) : asset('img/placeholder.png') }}" alt="{{ $it->name }}">
          <span class="product-quantity">{{ $it->qty }}</span>
        </div>
        <div class="product-info">
          <div style="font-weight:600;">{{ $it->name }}</div>
          @if($it->color)<div>Color: {{ $it->color }}</div>@endif
          @if($it->size)<div>Size: {{ $it->size }}</div>@endif
        </div>
        <div class="product-price">
          Rp {{ number_format($it->price * $it->qty, 0, ',', '.') }}
        </div>
      </div>
    @empty
      <div class="notice-box" style="margin-top:10px;">Keranjang kosong.</div>
    @endforelse

    <div class="totals-section">
      <div class="total-line">
        <span>Subtotal</span>
        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
      </div>
      <div class="total-line">
        <span>Shipping (est.)</span>
        <span>-</span>
      </div>
      <div class="total-line total">
        <span>Total</span>
        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
      </div>
    </div>

    <div class="form-actions">
      {{-- Tombol submit utama (desktop) --}}
      <form action="{{ route('order.store') }}" method="POST">
        @csrf
        <button type="submit" class="checkout-btn">Place Order</button>
      </form>
      <div class="mobile-totals-summary"></div>
    </div>
  </aside>
</main>

{{-- Sedikit JS agar .radio-option kasih/tarik class .selected saat dipilih --}}
@push('scripts')
<script>
  document.querySelectorAll('.radio-option input[type="radio"]').forEach(r => {
    r.addEventListener('change', () => {
      document.querySelectorAll('.radio-option').forEach(op => op.classList.remove('selected'));
      r.closest('.radio-option').classList.add('selected');
    });
  });
</script>
@endpush
@endsection