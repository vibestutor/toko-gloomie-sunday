@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
@php
  $buyNow = session('buy_now');
  $cart   = session('cart', []);

  $items  = $buyNow ? [ (object) [
      'name'      => $buyNow['name']      ?? ($buyNow['product_name'] ?? 'Item'),
      'price'     => (int)($buyNow['price'] ?? 0),
      'qty'       => (int)($buyNow['qty'] ?? 1),
      'color'     => $buyNow['color']     ?? null,
      'size'      => $buyNow['size']      ?? null,
      'image_url' => $buyNow['image_url'] ?? null,
  ] ] : collect($cart)->map(fn($c) => (object)[
        'name'      => $c['name']      ?? 'Item',
        'price'     => (int)($c['price'] ?? 0),
        'qty'       => (int)($c['qty'] ?? 1),
        'color'     => $c['color']     ?? null,
        'size'      => $c['size']      ?? null,
        'image_url' => $c['image_url'] ?? null,
  ])->all();

  $subtotal       = 0;
  foreach ($items as $it) $subtotal += ($it->price * $it->qty);

  $discountAmount = (int) (session('checkout_discount_amount', 0));
  $discountCode   = session('checkout_discount_code');
  $shippingCost   = (int) (session('checkout_shipping_amount', 0));

  $totalBase      = max(0, $subtotal - $discountAmount + $shippingCost);
@endphp

<main>
  <div class="checkout-container">

    {{-- ===== MOBILE SUMMARY ===== --}}
    <div class="mobile-summary">
      <div class="mobile-summary-header">
        <i class="fas fa-shopping-cart"></i>
        <span>Show order summary</span>
        <i class="fas fa-chevron-down"></i>
      </div>
      <div class="mobile-summary-total price" data-price="{{ $totalBase }}">
        {{ money($totalBase) }}
      </div>

      <div class="mobile-summary-content">
        <div class="product-list">
          @foreach($items as $it)
            <div class="product-item">
              <div class="product-image">
                <img src="{{ $it->image_url ? asset($it->image_url) : asset('img/placeholder.png') }}" alt="{{ $it->name }}">
                <span class="product-quantity">{{ $it->qty }}</span>
              </div>
              <div class="product-info">
                <span class="product-name">{{ $it->name }}</span>
              </div>
              <div class="product-price price" data-price="{{ $it->price * $it->qty }}">
                {{ money($it->price * $it->qty) }}
              </div>
            </div>
          @endforeach
        </div>

        <div class="discount-section">
          <form class="form-group" method="POST" action="{{ route('checkout.applyDiscount') }}">
            @csrf
            <input type="text" name="code" placeholder="Discount code" id="discount-code-input" value="{{ old('code', $discountCode) }}">
            <button class="apply-btn" type="submit" id="apply-discount-btn">Apply</button>
          </form>
          @if(session('checkout_discount_msg'))
            <small style="display:block;margin-top:6px">{{ session('checkout_discount_msg') }}</small>
          @endif
        </div>

        <div class="totals-section">
          <div class="total-line">
            <span>Subtotal</span>
            <span id="summary-subtotal" class="price" data-price="{{ $subtotal }}">{{ money($subtotal) }}</span>
          </div>
          <div class="total-line">
            <span>Shipping</span>
            <span class="price" data-price="{{ $shippingCost }}">
              {{ $shippingCost>0 ? money($shippingCost) : 'Enter shipping address' }}
            </span>
          </div>
          <div class="total-line {{ $discountAmount>0 ? '' : 'hidden' }}" id="discount-line">
            <span>Discount{{ $discountCode ? " ($discountCode)" : '' }}</span>
            <span id="summary-discount-amount" class="price" data-price="{{ $discountAmount }}">
              {{ $discountAmount>0 ? '-'.money($discountAmount) : '' }}
            </span>
          </div>
          <div class="total-line total">
            <span>Total</span>
            <span id="summary-total" class="price" data-price="{{ $totalBase }}">
              {{ money($totalBase) }}
            </span>
          </div>
        </div>
      </div>
    </div>

    {{-- ===== CUSTOMER FORM ===== --}}
    <div class="customer-info">
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
          <h2>Contact</h2>
          <div class="form-group">
            <input type="email" name="email" placeholder="Email or mobile phone number" value="{{ old('email') }}" required>
          </div>
          <div class="checkbox-group">
            <input type="checkbox" id="newsletter-signup" name="newsletter" value="1">
            <label for="newsletter-signup">Email me with news and offers</label>
          </div>
        </div>

        <div class="form-section">
          <h2>Shipping address</h2>
          <div class="form-group">
            <select id="shipping-country" name="shipping_country" required>
              <option value="ID" selected>Indonesia</option>
              <option value="SG">Singapore</option>
              <option value="MY">Malaysia</option>
              <option value="TH">Thailand</option>
              <option value="PH">Philippines</option>
              <option value="VN">Vietnam</option>
              <option value="BN">Brunei</option>
              <option value="CN">China</option>
              <option value="HK">Hong Kong</option>
              <option value="JP">Japan</option>
              <option value="KR">South Korea</option>
              <option value="TW">Taiwan</option>
              <option value="AU">Australia</option>
              <option value="US">United States</option>
              <option value="GB">United Kingdom</option>
              <option value="DE">Germany</option>
            </select>
          </div>
          <div class="form-row name-row">
            <div class="form-group">
              <input type="text" name="first_name" placeholder="First name (optional)" value="{{ old('first_name') }}">
            </div>
            <div class="form-group">
              <input type="text" name="last_name" placeholder="Last name" value="{{ old('last_name') }}" required>
            </div>
          </div>
          <div class="form-group">
            <input type="text" name="address" placeholder="Address" value="{{ old('address') }}" required>
          </div>
          <div class="form-group">
            <input type="text" name="address2" placeholder="Apartment, suite, etc. (optional)" value="{{ old('address2') }}">
          </div>
          <div class="form-group">
            <input type="text" name="city" placeholder="City" value="{{ old('city') }}" required>
          </div>
          <div class="form-row">
            <div class="form-group">
              <select id="shipping-province" name="shipping_province" required></select>
            </div>
            <div class="form-group">
              <input type="text" name="postal_code" placeholder="Postal code" value="{{ old('postal_code') }}" required>
            </div>
          </div>
          <div class="form-group">
            <input type="tel" name="phone" placeholder="Phone" value="{{ old('phone') }}" required>
          </div>
          <div class="checkbox-group">
            <input type="checkbox" id="save-info" name="save_info" value="1">
            <label for="save-info">Save this information for next time</label>
          </div>
        </div>

        <div class="form-section">
          <h2>Shipping method</h2>
          <div class="notice-box">
            <p>Enter your shipping address to view available shipping methods</p>
          </div>
        </div>

        <div class="form-section">
          <h2>Payment</h2>
          <p class="section-subtitle">All transactions are secure and encrypted.</p>

          <div class="radio-group payment-methods-group">
            <div class="radio-option">
              <div class="radio-option-header">
                <input type="radio" name="payment_method" id="xendit" value="xendit" checked>
                <label for="xendit"><span>Payments By Xendit</span></label>
              </div>
              <div class="payment-description">
                <p>After clicking "Pay now", you will be redirected to Payments By Xendit to complete your purchase securely.</p>
              </div>
            </div>

            <div class="radio-option">
              <div class="radio-option-header">
                <input type="radio" name="payment_method" id="bca" value="bca">
                <label for="bca"><span>Bank Central Asia</span></label>
              </div>
              <div class="bank-details">
                <p>Please transfer the payment to the account below</p>
                <ul>
                  <li><strong>Account Holder Name:</strong> Rona Ahmad Faruqi</li>
                  <li><strong>Account Number:</strong> 3211119274</li>
                  <li><strong>Bank Name:</strong> Bank Central Asia (BCA)</li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <div class="form-section">
          <h2>Billing address</h2>
          <p class="section-subtitle">Select the address that matches your card or payment method.</p>
          <div class="radio-group" id="billing-address-group">
            <div class="radio-option selected">
              <input type="radio" name="billing_address" id="billing-same" value="same" checked>
              <label for="billing-same">Same as shipping address</label>
            </div>
            <div class="radio-option">
              <input type="radio" name="billing_address" id="billing-different" value="different">
              <label for="billing-different">Use a different billing address</label>
            </div>
          </div>

          <div id="billing-address-form" class="hidden">
            <div class="form-group" style="margin-top: 20px;">
              <select id="billing-country" name="billing_country" required>
                <option value="ID" selected>Indonesia</option>
                <option value="SG">Singapore</option>
                <option value="MY">Malaysia</option>
                <option value="TH">Thailand</option>
                <option value="PH">Philippines</option>
                <option value="VN">Vietnam</option>
                <option value="BN">Brunei</option>
                <option value="CN">China</option>
                <option value="HK">Hong Kong</option>
                <option value="JP">Japan</option>
                <option value="KR">South Korea</option>
                <option value="TW">Taiwan</option>
                <option value="AU">Australia</option>
                <option value="US">United States</option>
                <option value="GB">United Kingdom</option>
                <option value="DE">Germany</option>
              </select>
            </div>
            <div class="form-row name-row">
              <div class="form-group"><input type="text" placeholder="First name (optional)"></div>
              <div class="form-group"><input type="text" placeholder="Last name" required></div>
            </div>
            <div class="form-group"><input type="text" placeholder="Address" required></div>
            <div class="form-group"><input type="text" placeholder="Apartment, suite, etc. (optional)"></div>
            <div class="form-group"><input type="text" placeholder="City" required></div>
            <div class="form-row">
              <div class="form-group"><select id="billing-province" name="billing_province" required></select></div>
              <div class="form-group"><input type="text" placeholder="Postal code" required></div>
            </div>
            <div class="form-group"><input type="tel" placeholder="Phone" required></div>
          </div>
        </div>

        <div class="form-actions">
          <button type="submit" class="checkout-btn">Pay now</button>
        </div>
      </form>
    </div>

    {{-- ===== ORDER SUMMARY (desktop) ===== --}}
    <div class="order-summary">
      <div class="product-list">
        @foreach($items as $it)
          <div class="product-item">
            <div class="product-image">
              <img src="{{ $it->image_url ? asset($it->image_url) : asset('img/placeholder.png') }}" alt="{{ $it->name }}">
              <span class="product-quantity">{{ $it->qty }}</span>
            </div>
            <div class="product-info">
              <span class="product-name">{{ $it->name }}</span>
            </div>
            <div class="product-price">
              <span class="price" data-price="{{ $it->price * $it->qty }}">{{ money($it->price * $it->qty) }}</span>
            </div>
          </div>
        @endforeach
      </div>

      <div class="discount-section">
        <form class="form-group" method="POST" action="{{ route('checkout.applyDiscount') }}">
          @csrf
          <input type="text" placeholder="Discount code" name="code" id="discount-code-input-2" value="{{ old('code', $discountCode) }}">
          <button class="apply-btn" type="submit" id="apply-discount-btn-2">Apply</button>
        </form>
        @if(session('checkout_discount_msg'))
          <small style="display:block;margin-top:6px">{{ session('checkout_discount_msg') }}</small>
        @endif
      </div>

      <div class="totals-section">
        <div class="total-line">
          <span>Subtotal</span>
          <span id="summary-subtotal-2" class="price" data-price="{{ $subtotal }}">{{ money($subtotal) }}</span>
        </div>
        <div class="total-line">
          <span>Shipping</span>
          <span class="price" data-price="{{ $shippingCost }}">
            {{ $shippingCost>0 ? money($shippingCost) : 'Enter shipping address' }}
          </span>
        </div>
        <div class="total-line {{ $discountAmount>0 ? '' : 'hidden' }}" id="discount-line-2">
          <span>Discount{{ $discountCode ? " ($discountCode)" : '' }}</span>
          <span id="summary-discount-amount-2" class="price" data-price="{{ $discountAmount }}">
            {{ $discountAmount>0 ? '-'.money($discountAmount) : '' }}
          </span>
        </div>
        <div class="total-line total">
          <span>Total</span>
          <span id="summary-total-2">
            <strong class="price" data-price="{{ $totalBase }}">{{ money($totalBase) }}</strong>
          </span>
        </div>
      </div>
    </div>

  </div>
</main>
@endsection
