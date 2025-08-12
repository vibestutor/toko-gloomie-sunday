<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image_url',
        'image_hover_url',
    ];

    /**
     * Bind route model ke slug, bukan ID.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Mendefinisikan bahwa satu Produk milik satu Kategori (Inverse One-to-Many).
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Varian milik produk (warna, dsb) dengan default urutan alfabet.
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class)
                    ->orderByRaw('LOWER(color)');
    }
}
