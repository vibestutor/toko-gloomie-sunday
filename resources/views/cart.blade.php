@extends('layouts.app')
@section('title','Your Cart')

@section('content')
<main class="cart-container halaman-keranjang">

  <div class="cart-header">
      <h1>YOUR CART</h1>
  </div>

  <section class="cart-items-section">
      <div class="cart-table-header">
          <span class="header-produk">PRODUCT</span>
          <span class="header-jumlah">AMOUNT</span>
          <span class="header-total">TOTAL PRICE</span>
      </div>

      <div class="cart-items-list">
        @forelse($cart->items as $it)
          <div class="cart-item">
            <div class="item-details">
              <img src="{{ asset($it->image_snapshot) }}" alt="">
              <div class="item-info">
                <h4>{{ $it->name_snapshot }}</h4>
                <p class="item-price">Rp {{ number_format($it->price_snapshot,0,',','.') }}</p>
                @if($it->variant) <p class="item-size">Ukuran: {{ $it->variant->size ?? '-' }}</p> @endif
              </div>
            </div>

            <div class="item-quantity">
              <form action="{{ route('cart.update',$it->id) }}" method="POST" class="quantity-input">
                @csrf @method('PATCH')
                <button class="quantity-btn" onclick="this.closest('form').querySelector('input[name=qty]').stepDown();">-</button>
                <input type="number" name="qty" value="{{ $it->qty }}" min="1">
                <button class="quantity-btn" onclick="this.closest('form').querySelector('input[name=qty]').stepUp();">+</button>
              </form>
              <form action="{{ route('cart.remove',$it->id) }}" method="POST">
                @csrf @method('DELETE')
                <button class="remove-btn"><i class="fas fa-trash-alt"></i></button>
              </form>
            </div>

            <div class="item-total">
              Rp {{ number_format($it->price_snapshot*$it->qty,0,',','.') }}
            </div>
          </div>
        @empty
          <p style="padding:20px 0;">Cart is empty.</p>
        @endforelse
      </div>
  </section>

  @php
    $subtotal = $cart->items->sum(fn($i)=>$i->price_snapshot*$i->qty);
  @endphp

  <div class="cart-summary">
    <div class="summary-box">
      <div class="subtotal-line">
        <span class="label">Subtotal</span>
        <span class="value" id="subtotal-value">Rp {{ number_format($subtotal,0,',','.') }}</span>
      </div>
      <p class="summary-note">Taxes and shipping costs are calculated at checkout.</p>
      <div class="cart-action-buttons">
        <a href="{{ route('products.index') }}" class="continue-shopping-btn">Continue Shopping</a>
        <form action="{{ route('checkout.index') }}" method="GET">
          <button type="submit" class="checkout-btn">Check out</button>
        </form>
      </div>
    </div>
  </div>

  {{-- modal konfirmasi (opsional, kalau mau dipakai JS) --}}
  <div id="modal-overlay" class="hidden"></div>
  <div id="confirmation-modal" class="hidden">
      <h3>Remove Item?</h3>
      <p id="modal-text">Are you sure you want to remove this item from your cart</p>
      <div class="modal-buttons">
          <button id="cancel-btn">Cancel</button>
          <button id="confirm-delete-btn">Yes, Remove</button>
      </div>
  </div>

  {{-- featured grid kamu bisa pakai komponen yang sudah ada --}}
  @include('partials.featured-grid')
</main>
@endsection
