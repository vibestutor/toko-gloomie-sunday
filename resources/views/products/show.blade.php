@extends('layouts.app')

@section('title', $product->name) {{-- Judul tab browser menjadi nama produk --}}

@section('content')
    <main class="product-detail-container">

        {{-- BAGIAN KIRI: GALERI GAMBAR --}}
        <section class="pd-gallery">
            {{-- Menampilkan gambar utama dari database --}}
            <img id="product-image" src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" class="pd-main-image">

            <div id="product-thumbnails" class="pd-thumbnail-images">
                {{-- Nanti kita bisa tambahkan logika untuk menampilkan banyak thumbnail di sini --}}
            </div>
        </section>

        {{-- BAGIAN KANAN: DETAIL & AKSI --}}
        <section class="pd-details">
            <p id="product-brand" class="pd-brand">GLOOMIE SUNDAY</p>
            <h1 id="product-title" class="pd-title">{{ $product->name }}</h1>
            <p id="product-price" class="pd-price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

            <div class="pd-selection-group">
                <div class="pd-size-selector">
                    <h4>SELECT SIZE</h4>
                    <div class="pd-size-options">
                        {{-- Pilihan ukuran masih statis untuk saat ini --}}
                        <div class="pd-size-option">M</div>
                        <div class="pd-size-option">L</div>
                        <div class="pd-size-option">XL</div>
                    </div>
                </div>

                <div class="pd-size-selector">
                    <h4>COLOR</h4>
                    <div class="pd-size-options" id="color-options">
                        @foreach(($variants ?? []) as $variant)
                            <button type="button" class="pd-size-option color-option" data-color="{{ $variant->color }}" data-image="{{ asset($variant->image_url ?: $product->image_url) }}" data-price="{{ $variant->price ?? $product->price }}">
                                {{ ucfirst($variant->color) }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="pd-quantity-selector">
                    <h4>Qty</h4>
                    <div class="quantity-input">
                        <button class="quantity-btn minus-btn">-</button>
                        <input type="number" class="quantity-value" value="1" min="1">
                        <button class="quantity-btn plus-btn">+</button>
                    </div>
                </div>
            </div>

            <div class="pd-action-buttons">
                <button class="btn-add-to-cart">
                    <i class="fas fa-shopping-cart"></i> Add To Cart
                </button>
                <button class="btn-buy-now">Buy Now</button>
            </div>

            <div class="pd-accordion">
                <div class="pd-accordion-item">
                    <h3>Description</h3>
                    {{-- Menampilkan deskripsi dari database --}}
                    <p id="product-description">{{ $product->description }}</p>
                </div>
                <div class="pd-accordion-item">
                    <h3>Sizing</h3>
                    <p>Informasi detail ukuran dan tabel ukuran akan ditampilkan di sini.</p>
                </div>
            </div>
        </section>
    </main>

    {{-- BAGIAN BAWAH: PRODUK TERKAIT (RELATED PRODUCTS) --}}
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
                        <span class="price">Rp {{ number_format($related->price, 0, ',', '.') }}</span>
                    </div>
                </div>
            @empty
                <p style="grid-column: 1 / -1; text-align: center;">Tidak ada produk terkait.</p>
            @endforelse

        </div>
    </div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const mainImg = document.getElementById('product-image');
  const priceEl = document.getElementById('product-price');
  const colorButtons = document.querySelectorAll('.color-option');
  colorButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const img = btn.getAttribute('data-image');
      const price = btn.getAttribute('data-price');
      if (img && mainImg) mainImg.src = img;
      if (price && priceEl) {
        const formatted = new Intl.NumberFormat('id-ID').format(parseInt(price, 10));
        priceEl.textContent = `Rp ${formatted}`;
      }
      colorButtons.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
    });
  });
  if (colorButtons.length > 0) colorButtons[0].click();
});
</script>
@endpush
@endsection