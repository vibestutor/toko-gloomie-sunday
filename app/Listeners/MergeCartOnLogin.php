<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Services\CartService;

class MergeCartOnLogin
{
    public function __construct(protected CartService $cart) {}

    public function handle(Login $event): void
    {
        $token = request()->cookie(CartService::COOKIE);
        if ($token) {
            $this->cart->mergeGuestIntoUser($event->user->id, $token);
        }
    }
}
