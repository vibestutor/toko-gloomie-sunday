<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',            // rupiah (integer)
        'stock',
        'image_url',
        'image_hover_url',
        // 'sale_price',     // opsional: kalau nanti kamu tambah kolom ini
        // 'is_active',      // opsional: kalau nanti kamu tambah kolom ini
    ];

    protected $casts = [
        'price' => 'integer',
        'stock' => 'integer',
        // 'sale_price' => 'integer', // aktifkan kalau ada
        // 'is_active'  => 'boolean', // aktifkan kalau ada
    ];

    /**
     * Route model binding pakai slug.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Relasi.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class)
                    ->orderByRaw('LOWER(color)');
    }

    /**
     * Harga aktif (pakai sale_price kalau ada, else price).
     * Disimpan & dihitung dalam IDR (integer).
     */
    public function getActivePriceIdrAttribute(): int
    {
        // aman walau kolom sale_price belum ada (akan null)
        return (int) ($this->sale_price ?? $this->price ?? 0);
    }

    /**
     * URL gambar utama dengan fallback placeholder.
     */
    public function getImageFullUrlAttribute(): string
    {
        return asset($this->image_url ?: 'img/placeholder.png');
    }

    /**
     * URL gambar hover (kalau ada).
     */
    public function getImageHoverFullUrlAttribute(): ?string
    {
        return $this->image_hover_url ? asset($this->image_hover_url) : null;
    }

    /**
     * Scope search sederhana (nama & deskripsi).
     */
    public function scopeSearch($q, ?string $term)
    {
        $term = trim((string) $term);
        if ($term === '') return $q;
        return $q->where(function ($qq) use ($term) {
            $qq->where('name', 'like', "%{$term}%")
               ->orWhere('description', 'like', "%{$term}%");
        });
    }

    /**
     * Auto-slug saat create/update kalau slug kosong.
     */
    protected static function booted()
    {
        static::saving(function (self $product) {
            if (blank($product->slug) && filled($product->name)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }
}
