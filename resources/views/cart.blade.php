{{-- resources/views/cart.blade.php --}}
@extends('layouts.app')

@section('title', 'YOUR CART')

@section('content')
<main class="cart-container">
  <div class="cart-header">
    <h1>YOUR CART</h1>
  </div>

  <section class="cart-items-section">
    <div class="cart-table-header">
      <span class="header-produk">PRODUCT</span>
      <span class="header-jumlah">AMOUNT</span>
      <span class="header-total">TOTAL PRICE</span>
    </div>

    @php
      $items = $cart->items ?? collect();
      $subtotal = $items->sum(fn($i) => $i->qty * $i->price_snapshot);
    @endphp

    <div class="cart-items-list">
      @forelse($items as $item)
        <div class="cart-item">
          <div class="item-details">
            <img src="{{ $item->image_snapshot ?: asset('img/placeholder.png') }}" alt="">
            <div class="item-info">
              <h4>GLOOMIE SUNDAY</h4>
              <p>
                {{ $item->name_snapshot }}
                @if(optional($item->variant)->name)
                  - {{ $item->variant->name }}
                @endif
              </p>
              <p class="item-price">Rp {{ number_format($item->price_snapshot,0,',','.') }}</p>
              @if(optional($item->variant)->size)
                <p class="item-size">Ukuran: {{ $item->variant->size }}</p>
              @endif
            </div>
          </div>

<form method="POST" action="{{ route('cart.update', $item) }}" class="quantity-input">
  @csrf
  @method('PATCH')

  <button type="button" class="quantity-btn js-minus">-</button>
  <input class="js-qty" type="number" name="qty" value="{{ $item->qty }}" min="1">
  <button type="button" class="quantity-btn js-plus">+</button>

  {{-- tombol submit beneran, disembunyiin --}}
  <button type="submit" class="js-submit-qty" style="display:none"></button>
</form>


<form method="POST" id="remove-{{ $item->id }}" action="{{ route('cart.remove', $item) }}">
  @csrf
  @method('DELETE')
  <button class="remove-btn" type="button" data-remove="remove-{{ $item->id }}">
    <i class="fas fa-trash-alt"></i>
  </button>
</form>
          </div>

          <div class="item-total">
            Rp {{ number_format($item->qty * $item->price_snapshot,0,',','.') }}
          </div>
        </div>
      @empty
        <p class="empty">Cart kamu masih kosong.</p>
      @endforelse
    </div>
  </section>

  <div class="cart-summary">
    <div class="summary-box">
      <div class="subtotal-line">
        <span class="label">Subtotal</span>
        <span class="value" id="subtotal-value">Rp {{ number_format($subtotal,0,',','.') }}</span>
      </div>
      <p class="summary-note">Taxes and shipping costs are calculated at checkout.</p>
      <div class="cart-action-buttons">
        <a href="{{ route('products.index') }}" class="continue-shopping-btn">Continue Shopping</a>

        {{-- ke halaman checkout --}}
        <form method="GET" action="{{ route('checkout.view') }}" style="display:inline;">
          <button type="submit" class="checkout-btn">Check out</button>
        </form>
      </div>
    </div>
  </div>

  {{-- modal konfirmasi hapus (markup kamu tetap) --}}
  <div id="modal-overlay" class="hidden"></div>
  <div id="confirmation-modal" class="hidden">
    <h3>Remove Item?</h3>
    <p id="modal-text">Are you sure you want to remove this item from your cart</p>
    <div class="modal-buttons">
      <button id="cancel-btn">Cancel</button>
      <button id="confirm-delete-btn">Yes, Remove</button>
    </div>
  </div>

  {{-- FEATURED COLLECTION (biarin markup lama; opsional ganti href ke route produk) --}}
  <div id="home-featured">
    <h2 class="title">FEATURED COLLECION</h2>
    <div class="featured-grid">
      {{-- contoh ganti 1 link ke route produk by slug (opsional): --}}
      {{-- <a href="{{ route('products.show', ['product' => 'crewneck-breaker-1']) }}"> --}}
      {{-- sisanya boleh kamu biarkan dulu sesuai HTML lama --}}
      {!! /* Tempel blok featured product HTML kamu di sini tanpa perubahan */ '' !!}
    </div>
    <div class="view-all-container">
      <a href="{{ route('products.index') }}" class="btn-view-all">See More Product</a>
    </div>
  </div>
</main>
@endsection
