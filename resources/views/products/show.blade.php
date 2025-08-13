@extends('layouts.app')

@section('title', $product->name)

@section('content')
<main class="product-detail-container" data-product-id="{{ $product->id }}">

    {{-- BAGIAN KIRI: GALERI GAMBAR --}}
    <section class="pd-gallery">
        <img id="product-image"
             src="{{ asset($product->image_url) }}"
             alt="{{ $product->name }}"
             class="pd-main-image">

        <div id="product-thumbnails" class="pd-thumbnail-images">
            <img class="pd-thumbnail" data-thumb src="{{ asset($product->image_url) }}" alt="thumb-1">
            @if($product->image_hover_url && $product->image_hover_url !== $product->image_url)
                <img class="pd-thumbnail" data-thumb src="{{ asset($product->image_hover_url) }}" alt="thumb-2">
            @endif
            @php $thumbs = []; @endphp
            @foreach(($variants ?? []) as $v)
                @php $src = $v->image_url ?: $product->image_url; @endphp
                @if($src && !in_array($src, $thumbs))
                    @php $thumbs[] = $src; @endphp
                    <img class="pd-thumbnail" data-thumb src="{{ asset($src) }}" alt="thumb-variant">
                @endif
            @endforeach
        </div>
    </section>

    {{-- BAGIAN KANAN: DETAIL --}}
    <section class="pd-details">
        <p id="product-brand" class="pd-brand">GLOOMIE SUNDAY</p>
        <h1 id="product-title" class="pd-title">{{ $product->name }}</h1>
        <p id="product-price" class="pd-price">Rp {{ number_format((float)$product->price, 0, ',', '.') }}</p>

<div class="pd-selection-group">

    {{-- COLOR --}}
    @if(($variants ?? collect())->count())
        <div class="pd-color-selector">
            <h4>COLOR</h4>
            <div class="pd-color-options" id="color-options">
                @foreach($variants as $variant)
                    <button
                        type="button"
                        class="pd-color-option"
                        data-color="{{ $variant->color }}"
                        data-image="{{ asset($variant->image_url ?: $product->image_url) }}"
                        data-price="{{ $variant->price ?? $product->price }}">
                        {{ ucfirst(strtolower($variant->color)) }}
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    {{-- SIZE --}}
@php
    $sizes = ['M','L','XL'];
    if (\Illuminate\Support\Str::contains(strtolower($product->name), 'xxl')) {
        $sizes[] = 'XXL';
    }
@endphp
<div class="pd-size-selector">
    <h4>SELECT SIZE</h4>
    <div class="pd-size-options">
        @foreach($sizes as $s)
            <div class="pd-size-option" role="button">{{ $s }}</div>
        @endforeach
    </div>
</div>

    {{-- QTY --}}
    <div class="pd-quantity-selector">
        <h4>Qty</h4>
        <div class="quantity-input">
            <button class="quantity-btn minus-btn" type="button">-</button>
            <input type="number" class="quantity-value" value="1" min="1">
            <button class="quantity-btn plus-btn" type="button">+</button>
        </div>
    </div>


        {{-- TOMBOL AKSI --}}
        <div class="pd-action-buttons">
            <button class="btn-add-to-cart" type="button">
                <i class="fas fa-shopping-cart"></i> Add To Cart
            </button>
            <button class="btn-buy-now" type="button">Buy Now</button>
        </div>

        {{-- DESCRIPTION --}}
        <div class="pd-accordion">
            <div class="pd-accordion-item">
                <h3>Description</h3>
                <p id="product-description">{{ $product->description }}</p>
            </div>
        </div>
    </section>
</main>

{{-- PRODUK TERKAIT --}}
<div id="home-featured">
    <h2 class="title">Related Product</h2>
    <div class="featured-grid">
        @forelse ($relatedProducts as $related)
            <div class="featured-item">
                <div class="Product-image-">
                    <a href="{{ route('products.show', $related) }}">
                        <img src="{{ asset($related->image_url) }}" alt="{{ $related->name }}">
                        @if($related->image_hover_url)
                            <img class="product-hoover" src="{{ asset($related->image_hover_url) }}" alt="{{ $related->name }} hover">
                        @endif
                    </a>
                </div>
                <div class="product-description">
                    <h3>{{ $related->name }}</h3>
                    <span class="price">Rp {{ number_format((float)$related->price, 0, ',', '.') }}</span>
                </div>
            </div>
        @empty
            <p style="grid-column: 1 / -1; text-align: center;">Tidak ada produk terkait.</p>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
  <script src="{{ asset('js/product-show.js') }}" defer></script>
@endpush

