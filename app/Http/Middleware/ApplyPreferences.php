<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplyPreferences
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Terapkan locale jika ada di session
        if ($loc = session('locale')) {
            app()->setLocale($loc);
        }

        // (Opsional) Share currency ke view kalau mau dipakai global
        if ($cur = session('currency')) {
            view()->share('activeCurrency', $cur);
        }

        return $next($request);
    }
}
