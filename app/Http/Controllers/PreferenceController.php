<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PreferenceController extends Controller
{
    public function update(Request $request)
    {
        $data = $request->validate([
            'locale'   => 'required|string',
            'currency' => 'required|string',
        ]);

        app()->setLocale($data['locale']);
        session([
            'locale'   => $data['locale'],
            'currency' => $data['currency'],
        ]);

        return back();
    }
}
