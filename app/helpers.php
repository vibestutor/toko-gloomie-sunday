<?php

if (! function_exists('money')) {
    /**
     * Format harga sesuai session('currency').
     * $amount = angka dari DB dalam BASE currency (IDR).
     */
    function money($amount, ?string $to = null): string
    {
        $base     = config('currency.base', 'IDR');
        $to       = $to ?: session('currency', $base);

        $rates    = config('currency.rates', []);
        $symbols  = config('currency.symbols', []);
        $formats  = config('currency.format', []);

        $rateTo   = $rates[$to]   ?? 1;
        $rateBase = $rates[$base] ?? 1;

        // konversi base -> target
        $value    = ($rateBase == 0) ? (float) $amount : (float) $amount * ($rateTo / $rateBase);

        $fmt      = $formats[$to] ?? ['thousand' => ',', 'decimal' => '.', 'precision' => 2];
        $value    = round($value, (int) $fmt['precision']);

        $num = number_format($value, (int) $fmt['precision'], $fmt['decimal'], $fmt['thousand']);
        $sym = $symbols[$to] ?? '';

        // Indo style
        if ($to === 'IDR') {
            return $sym.' '.$num;
        }
        return $sym.' '.$num;
    }
}
