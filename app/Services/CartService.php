<?php

namespace App\Services;

use App\Models\{Cart, CartItem, Product, ProductVariant};
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CartService
{
    const COOKIE = 'cart_token';
    const COOKIE_MINUTES = 60*24*30; // 30 hari
    const MAX_QTY = 10;

    public function current(): Cart
    {
        $userId = auth()->id();
        $token  = request()->cookie(self::COOKIE);

        $cart = Cart::forIdentity($userId, $token)->first();

        if (!$cart) {
            $cart = Cart::create([
                'user_id'    => $userId,
                'cart_token' => $userId ? null : $this->ensureToken($token),
            ]);
        }

        // pastikan cookie ada utk guest
        if (!$userId && !$token) {
            Cookie::queue(Cookie::make(self::COOKIE, $cart->cart_token, self::COOKIE_MINUTES, null, null, true, true, false, 'Strict'));
        }

        return $cart->load('items');
    }

    protected function ensureToken(?string $token): string
    {
        return $token ?: Str::random(48);
    }

    public function add(int $productId, ?int $variantId, int $qty): Cart
    {
        $cart = $this->current();

        $qty = max(1, min(self::MAX_QTY, $qty));

        // validasi produk & stok/price live
        $product = Product::findOrFail($productId);
        $variant = $variantId ? ProductVariant::where('product_id',$productId)->findOrFail($variantId) : null;

        // ambil stok & harga saat ini
        $price = (int) round(($variant->price ?? $product->price));
        $stock = (int) ($variant->stock ?? $product->stock);

        if ($stock < 1) {
            throw ValidationException::withMessages(['qty' => 'Stok tidak tersedia.']);
        }

        // gabung kalau sudah ada baris yang sama
        $item = $cart->items()->where([
            'product_id' => $productId,
            'product_variant_id' => $variantId,
        ])->first();

        $newQty = $qty + ($item->qty ?? 0);
        if ($newQty > self::MAX_QTY) $newQty = self::MAX_QTY;
        if ($newQty > $stock) {
            throw ValidationException::withMessages(['qty' => 'Melebihi stok tersedia.']);
        }

        $payload = [
            'qty' => $newQty,
            'name_snapshot'  => $product->name,
            'image_snapshot' => $variant->image_url ?? $product->image_url,
            'price_snapshot' => $price,
        ];

        if ($item) {
            $item->update($payload);
        } else {
            $cart->items()->create(array_merge($payload, [
                'product_id' => $productId,
                'product_variant_id' => $variantId,
            ]));
        }

        return $this->current();
    }

    public function updateQty(int $itemId, int $qty): Cart
    {
        $cart = $this->current();
        $item = $cart->items()->findOrFail($itemId);

        $qty = max(1, min(self::MAX_QTY, $qty));

        // cek stok live
        $stock = (int) (($item->variant->stock ?? $item->product->stock) ?? 0);
        if ($qty > $stock) {
            throw ValidationException::withMessages(['qty' => 'Melebihi stok tersedia.']);
        }

        $item->update(['qty' => $qty]);

        return $this->current();
    }

    public function remove(int $itemId): Cart
    {
        $cart = $this->current();
        $cart->items()->whereKey($itemId)->delete();
        return $this->current();
    }

    public function clear(): Cart
    {
        $cart = $this->current();
        $cart->items()->delete();
        return $this->current();
    }

    /** dipakai pada event login */
    public function mergeGuestIntoUser(int $userId, string $token): void
    {
        $guest = Cart::whereNull('user_id')->where('cart_token',$token)->first();
        if (!$guest) return;

        $userCart = Cart::firstOrCreate(['user_id'=>$userId], ['cart_token'=>null]);

        DB::transaction(function () use ($guest, $userCart) {
            foreach ($guest->items as $gi) {
                $existing = $userCart->items()
                    ->where('product_id',$gi->product_id)
                    ->where('product_variant_id',$gi->product_variant_id)
                    ->first();

                if ($existing) {
                    $sum = min(self::MAX_QTY, $existing->qty + $gi->qty);
                    $existing->update(['qty'=>$sum]);
                    $gi->delete();
                } else {
                    $gi->cart_id = $userCart->id;
                    $gi->save();
                }
            }
            $guest->delete();
        });

        // drop cookie token
        Cookie::queue(
    Cookie::make(
        self::COOKIE,
        $cart->cart_token,
        self::COOKIE_MINUTES,
        '/',         // path
        null,        // domain
        app()->environment('production'), // secure hanya di prod (HTTPS)
        true,        // httpOnly
        false,       // raw
        'Lax'        // SameSite Lax biar aman pas redirect pembayaran
    )
        );
    }

    /** hitung total live; dipakai di checkout */
    public function totalsForCheckout(Cart $cart): array
    {
        $items = $cart->items()->with(['product','variant'])->get();

        $lines = [];
        $subtotal = 0;

        foreach ($items as $it) {
            $price = (int) round(($it->variant->price ?? $it->product->price));
            $stock = (int) (($it->variant->stock ?? $it->product->stock) ?? 0);

            if ($it->qty > $stock) {
                throw ValidationException::withMessages([
                    'qty' => "Stok untuk {$it->name_snapshot} kurang. Tersisa {$stock}."
                ]);
            }
            $lineTotal = $price * $it->qty;
            $subtotal += $lineTotal;

            $lines[] = [
                'item' => $it,
                'unit_price' => $price,
                'line_total' => $lineTotal,
            ];
        }

        return compact('lines','subtotal');
    }
}
