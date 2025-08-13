<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id','cart_token','expires_at'];
    protected $casts = ['expires_at' => 'datetime'];

    public function items() {
        return $this->hasMany(CartItem::class);
    }

    public function scopeForIdentity($q, ?int $userId, ?string $token) {
        return $q->when($userId, fn($qq)=>$qq->where('user_id',$userId))
                 ->when(!$userId && $token, fn($qq)=>$qq->where('cart_token',$token));
    }
}