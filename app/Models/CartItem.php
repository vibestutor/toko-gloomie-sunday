<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id','product_id','product_variant_id','qty',
        'name_snapshot','image_snapshot','price_snapshot'
    ];

    public function cart()   { return $this->belongsTo(Cart::class); }
    public function product(){ return $this->belongsTo(Product::class); }
    public function variant(){ return $this->belongsTo(ProductVariant::class, 'product_variant_id'); }
}
