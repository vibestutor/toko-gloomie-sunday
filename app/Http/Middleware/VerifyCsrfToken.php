<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * Webhook Xendit WAJIB dikecualikan dari CSRF.
     */
    protected $except = [
        'webhooks/xendit',
        'webhooks/xendit/*',
    ];
}
