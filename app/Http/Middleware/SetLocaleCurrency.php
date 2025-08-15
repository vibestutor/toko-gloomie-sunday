<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocaleCurrency
{
    public function handle(Request $req, Closure $next)
    {
        app()->setLocale($req->cookie('locale', 'id'));
        session(['currency' => $req->cookie('currency', 'IDR')]);
        return $next($req);
    }
}
