<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'GLOOMIE SUNDAY - UNUSUAL CLUB')</title>

    {{-- CSRF buat AJAX/fetch ke Laravel --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Fonts & Icons --}}
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Vendor CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    {{-- App CSS --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
</head>

<body>
    {{-- Header --}}
    @include('layouts.partials.header')

    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.partials.footer')

    {{-- Vendor JS (CDN) --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    {{-- Global app JS (punya kamu) --}}
    <script src="{{ asset('js/products.js') }}" defer></script>
  <script src="{{ asset('js/product-show.js') }}" defer></script>
    <script src="{{ asset('js/script.js') }}" defer></script>

    {{-- Page-specific scripts dari view (misal: product-show.js dipush dari show.blade) --}}
    @stack('scripts')
</body>
</html>
