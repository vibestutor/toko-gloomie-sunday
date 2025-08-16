<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
      'code','status','name','email','phone','address','total','currency',
      'external_id','invoice_id','invoice_url','payment_channel','paid_at'
    ];

    public function items() { return $this->hasMany(OrderItem::class); }
}