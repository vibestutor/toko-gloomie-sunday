<?php

return [
    // mata uang dasar yang dipakai di DB
    'base'  => 'IDR',

    // rate konversi dari BASE -> target (angka contoh)
    'rates' => [
        'IDR' => 1,
        'USD' => 0.000062, // 1 IDR -> USD
        'MYR' => 0.00029,
        'THB' => 0.0023,
        'JPY' => 0.0097,
        'KRW' => 0.083,
        'EUR' => 0.000057,
        'GBP' => 0.000048,
        'BND' => 0.000083,
    ],

    // simbol
    'symbols' => [
        'IDR' => 'Rp',
        'USD' => '$',
        'MYR' => 'RM',
        'THB' => '฿',
        'JPY' => '¥',
        'KRW' => '₩',
        'EUR' => '€',
        'GBP' => '£',
        'BND' => 'B$',
    ],

    // format angka per currency
    'format' => [
        'IDR' => ['thousand' => '.', 'decimal' => ',', 'precision' => 0],
        'USD' => ['thousand' => ',', 'decimal' => '.', 'precision' => 2],
        'MYR' => ['thousand' => ',', 'decimal' => '.', 'precision' => 2],
        'THB' => ['thousand' => ',', 'decimal' => '.', 'precision' => 2],
        'JPY' => ['thousand' => ',', 'decimal' => '.', 'precision' => 0],
        'KRW' => ['thousand' => ',', 'decimal' => '.', 'precision' => 0],
        'EUR' => ['thousand' => '.', 'decimal' => ',', 'precision' => 2],
        'GBP' => ['thousand' => ',', 'decimal' => '.', 'precision' => 2],
        'BND' => ['thousand' => ',', 'decimal' => '.', 'precision' => 2],
    ],
];
