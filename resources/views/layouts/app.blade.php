<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'GLOOMIE SUNDAY - UNUSUAL CLUB'))</title>

    {{-- Prefs (locale/currency) buat JS --}}
    <meta name="app-locale" content="{{ app()->getLocale() }}">
    <meta name="app-currency" content="{{ session('currency','IDR') }}">
    <meta name="app-rates" content='@json(config("currency.rates", ["IDR"=>1]))'>

    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Vendor CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    {{-- Cache-busting versi file public/ --}}
    @php
        $ver = fn($p) => file_exists(public_path($p)) ? filemtime(public_path($p)) : 1;
        $v_css   = $ver('css/style.css');
        $v_prod  = $ver('js/products.js');
        $v_show  = $ver('js/product-show.js');
        $v_app   = $ver('js/script.js');
    @endphp

    {{-- App CSS (public/) --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ $v_css }}" />
</head>

<body class="font-sans antialiased">
    {{-- Header --}}
    @include('layouts.partials.header')

    <main>
        @include('partials.prefs')
        {{-- Section-based / slot-based --}}
        @hasSection('content')
            @yield('content')
        @else
            {{ $slot ?? '' }}
        @endif
    </main>

    {{-- Footer --}}
    @include('layouts.partials.footer')

    {{-- Vendor JS --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    {{-- App JS (public/) --}}
    <script src="{{ asset('js/products.js') }}?v={{ $v_prod }}" defer></script>
    <script src="{{ asset('js/product-show.js') }}?v={{ $v_show }}" defer></script>
    <script src="{{ asset('js/script.js') }}?v={{ $v_app }}" defer></script>

    {{-- Page-specific scripts --}}
    @stack('scripts')
</body>
</html>
