@extends('layouts.app')

@section('title', 'All Products - GLOOMIE SUNDAY')

@section('content')
    {{-- Kita tambahkan padding-top agar konten tidak tertutup oleh header fixed Anda --}}
    <main id="home-featured" style="padding-top: 100px; min-height: 80vh;">
        <h2 class="title">ALL PRODUCT</h2>
        <div class="featured-grid">

            {{-- Di sinilah "sihir" Laravel dimulai. Kita akan mengulang untuk setiap produk. --}}
            @forelse ($products as $product)
                <div class="featured-item">
                    <div class="Product-image-">
                        {{-- Link dinamis ke halaman detail produk berdasarkan slug --}}
                        <a href="{{ route('products.show', $product) }}">
                            
                            {{-- Gambar utama dari database --}}
                            <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}">
                            
                            {{-- Gambar hover dari database, hanya ditampilkan jika ada --}}
                            @if($product->image_hover_url)
                                <img class="product-hoover" src="{{ asset($product->image_hover_url) }}" alt="{{ $product->name }} hover">
                            @endif
                        </a>
                    </div>
                    <div class="product-description">
                        {{-- Nama produk dari database --}}
                        <h3>{{ $product->name }}</h3>
                        {{-- Harga dari database, diformat ke Rupiah --}}
                        <span class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    </div>
                </div>
            @empty
                {{-- Bagian ini akan tampil jika tidak ada produk sama sekali di database --}}
                <div style="grid-column: 1 / -1; text-align: center;">
                    <p>Maaf, belum ada produk yang tersedia.</p>
                </div>
            @endforelse

        </div>
    </main>
@endsection