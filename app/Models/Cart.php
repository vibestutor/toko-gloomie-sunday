<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'cart_token', 'expires_at'];
    protected $casts = ['expires_at' => 'datetime'];

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /* ---------- SCOPES ---------- */

    // Ambil cart berdasarkan user atau token (prioritas user_id)
    public function scopeForIdentity($q, ?int $userId, ?string $token)
    {
        return $q->when($userId, fn ($qq) => $qq->where('user_id', $userId))
                 ->when(!$userId && $token, fn ($qq) => $qq->where('cart_token', $token));
    }

    // Hindari cart kadaluarsa
    public function scopeActive($q)
    {
        return $q->where(function ($qq) {
            $qq->whereNull('expires_at')
               ->orWhere('expires_at', '>', now());
        });
    }

    /* ---------- HELPERS ---------- */

    // Ambil cart existing atau create baru (guest akan auto dapet token)
    public static function resolve(?int $userId = null, ?string $token = null): self
    {
        $query = static::active()->forIdentity($userId, $token);

        $cart = $query->first();
        if ($cart) return $cart;

        return static::create([
            'user_id'    => $userId,
            'cart_token' => $userId ? null : ($token ?: Str::uuid()->toString()),
            'expires_at' => $userId ? null : now()->addDays(7), // guest cart 7 hari (opsional)
        ]);
    }

    // Tambah/akumulasi item ke cart (no duplikat product di cart yang sama)
    public function addItem(int $productId, int $qty = 1, ?int $price = null): void
    {
        $item = $this->items()->firstOrNew(['product_id' => $productId]);
        $item->qty = ($item->qty ?? 0) + max(1, $qty);
        if (!is_null($price)) {
            $item->price = $price; // atau ambil dari DB Product saat checkout
        }
        $item->save();
    }

    // Merge dari array session cart: [['product_id'=>X,'qty'=>Y,'price'=>Z], ...]
    public function mergeFromArray(array $rows): void
    {
        foreach ($rows as $r) {
            $this->addItem(
                (int)($r['product_id'] ?? 0),
                (int)($r['qty'] ?? 1),
                isset($r['price']) ? (int)$r['price'] : null
            );
        }
    }

    // Refresh expiry buat guest tiap ada aktivitas (opsional)
    public function touchExpiry(int $days = 7): void
    {
        if (is_null($this->user_id)) {
            $this->expires_at = Carbon::now()->addDays($days);
            $this->save();
        }
    }

    /* ---------- CLEANUP (fallback kalau FK cascade belum di-set) ---------- */

    protected static function booted(): void
    {
        static::deleting(function (self $cart) {
            // kalau FK cascade sudah aktif di DB, ini nggak kepake—tetep aman
            $cart->items()->delete();
        });
    }
}
