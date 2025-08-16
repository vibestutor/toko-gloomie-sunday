<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocaleCurrency
{
    public function handle(Request $request, Closure $next)
    {
        // Ambil dari query (?lang=...&currency=...)
        if ($request->has('lang')) {
            App::setLocale($request->get('lang'));
            session(['locale' => $request->get('lang')]);
        } elseif (session()->has('locale')) {
            App::setLocale(session('locale'));
        }

        if ($request->has('currency')) {
            session(['currency' => strtoupper($request->get('currency'))]);
        }

        return $next($request);
    }
}
