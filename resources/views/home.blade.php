@extends('layouts.app')

@section('title', 'Selamat Datang di GLOOMIE SUNDAY')

@section('content')

  <!-- 🟢 SLIDER BANNER -->
  <div class="banner-container swiper" id="home">
    <div class="swiper-wrapper">
      <div class="swiper-slide"><img src="{{ asset('img/banner/unusual.jpg') }}" alt="Banner 1"></div>
      <div class="swiper-slide"><img src="{{ asset('img/banner/zipper misty.jpg') }}" alt="Banner 2"></div>
      <div class="swiper-slide"><img src="{{ asset('img/banner/kaos boxy h.jpg') }}" alt="Banner 3"></div>
    </div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
  </div>

  <!--/#home - featured (4 gambar) -->
  <section id="home-feature">
    <h2 class="title">FEATURED</h2>
    <div class="featured-grid">
      <a href="#" class="featured-item">
        <img src="{{ asset('img/banner/banner zipper.jpg') }}" alt="Banner Zipper">
      </a>
      <a href="#" class="featured-item">
        <img src="{{ asset('img/banner/banner kaos.jpg') }}" alt="Banner Kaos">
      </a>
      <a href="#" class="featured-item">
        <img src="{{ asset('img/banner/growups-01.png') }}" alt="Boxy 330">
      </a>
      <a href="#" class="featured-item">
        <img src="{{ asset('img/banner/growups-01-03.png') }}" alt="Sweetpants">
      </a>
    </div>
  </section>

  <!-- Home - Produk Unggulan (DINAMIS DARI DATABASE) -->
  <div id="home-featured">
    <h2 class="title">Featured Product</h2>
    <div class="featured-grid">
      @forelse ($featuredProducts as $product)
        <div class="featured-item">
          <div class="Product-image-">
            <a href="{{ route('products.show', ['product' => $product->slug]) }}">
              <img
                src="{{ asset($product->image_url ?? $product->image ?? 'img/placeholder.png') }}"
                alt="{{ $product->name }}"
              >
              @if(!empty($product->image_hover_url ?? null))
                <img class="product-hoover"
                     src="{{ asset($product->image_hover_url) }}"
                     alt="{{ $product->name }} hover">
              @endif
            </a>
          </div>
          <div class="product-description">
            <h3>{{ $product->name }}</h3>
            <span class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
          </div>
        </div>
      @empty
        <p>Tidak ada produk unggulan saat ini.</p>
      @endforelse
    </div>
  </div>

@endsection

@push('scripts')
<script>
  // Init Swiper (pakai CDN yang udah kamu include di layout)
  (function(){
    if (typeof Swiper !== 'undefined') {
      new Swiper('#home', {
        loop: true,
        autoplay: { delay: 3500, disableOnInteraction: false },
        navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' }
      });
    }
  })();
</script>
@endpush
