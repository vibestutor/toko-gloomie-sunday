<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    // kalau mau jaga2 dari mass-assign: pilih salah satu, fillable atau guarded.
    protected $fillable = [
        'cart_id', 'product_id', 'product_variant_id', 'qty',
        'name_snapshot', 'image_snapshot', 'price_snapshot'
    ];

    protected $casts = [
        'qty' => 'integer',
        'price_snapshot' => 'integer', // simpan dalam smallest unit (mis. rupiah, bukan 12.34)
    ];

    // Biar updated_at Cart ikut ke-refresh saat item berubah
    protected $touches = ['cart'];

    protected $appends = ['subtotal'];

    /* -------- Relations -------- */
    public function cart()    { return $this->belongsTo(Cart::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function variant() { return $this->belongsTo(ProductVariant::class, 'product_variant_id'); }

    /* -------- Accessors -------- */
    public function getSubtotalAttribute(): int
    {
        return (int) $this->qty * (int) ($this->price_snapshot ?? 0);
    }

    /* -------- Mutators / Helpers -------- */
    public function addQty(int $amount = 1): self
    {
        $this->qty = max(1, (int)$this->qty + max(1, $amount));
        return tap($this)->save();
    }

    public function setQty(int $qty): self
    {
        $this->qty = max(1, $qty);
        return tap($this)->save();
    }

    // Optional: sinkron harga snapshot dari product/variant (panggil saat add-to-cart)
    public function syncSnapshotFromCatalog(): self
    {
        $source = $this->variant ?? $this->product;
        if ($source) {
            $this->name_snapshot  = $this->name_snapshot  ?: ($source->name ?? $this->product->name ?? null);
            $this->image_snapshot = $this->image_snapshot ?: ($source->image ?? $this->product->image ?? null);
            // pastiin source punya field price (int). Kalau decimal, convert ke int (rupiah).
            if (is_null($this->price_snapshot) && isset($source->price)) {
                $this->price_snapshot = (int) $source->price;
            }
        }
        return tap($this)->save();
    }
}
