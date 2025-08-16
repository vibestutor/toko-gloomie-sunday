<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PreferenceController extends Controller
{
    public function update(Request $request)
    {
        // 1) Validasi longgar: masing-masing boleh kosong
        $data = $request->validate([
            'locale'   => 'nullable|string',
            'currency' => 'nullable|string',
        ]);

        // 2) Whitelist (sesuaikan dengan yang kamu sediakan di UI)
        $allowedLocales   = ['id','en','ms','th','ja','ko','de','fr','ms-BN'];
        $allowedCurrencies= ['IDR','USD','MYR','THB','JPY','KRW','EUR','GBP','BND'];

        // 3) Normalize & simpan kalau ada
        if (!empty($data['locale'])) {
            $loc = strtolower(trim($data['locale']));
            if (in_array($loc, array_map('strtolower', $allowedLocales), true)) {
                session(['locale' => $loc]);
                app()->setLocale($loc); // biar request ini juga langsung ikut berubah
            }
        }

        if (!empty($data['currency'])) {
            $cur = strtoupper(trim($data['currency']));
            if (in_array($cur, $allowedCurrencies, true)) {
                session(['currency' => $cur]);
            }
        }

        // 4) PRG: redirect 303 biar aman dari resubmit saat refresh
        return back(status: 303);
    }
}
