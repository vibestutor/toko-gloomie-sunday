<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkoutView()
    {
        $cart   = session('cart', []);
        $buyNow = session('buy_now');

        // Susun items yang akan ditampilkan di halaman checkout
        $items = $buyNow
            ? [$this->mapBuyNowToItem($buyNow)]
            : $cart;

        $subtotal = collect($items)->sum(fn ($i) => (int) $i['qty'] * (float) $i['price']);

        return view('checkout', compact('items', 'subtotal'));
    }

    public function buyNow(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'color'      => 'required|string',
            'size'       => 'required|string',
            'qty'        => 'required|integer|min:1',
        ]);

        // Simpan minimal info di session (akan diperkaya saat render/checkout)
        session()->put('buy_now', $data);

        return response()->json(['ok' => true]);
    }

    public function store(Request $request)
    {
        // Data customer versi ringkas; tambah field sesuai form-mu
        $payload = $request->validate([
            'name'    => 'required|string|max:150',
            'email'   => 'required|email',
            'phone'   => 'required|string|max:30',
            'address' => 'required|string|max:500',
        ]);

        $buyNow = session('buy_now');
        $cart   = session('cart', []);

        // Ambil items dari buy_now atau cart
        $items = $buyNow
            ? [$this->mapBuyNowToItem($buyNow)]
            : $cart;

        if (empty($items)) {
            return back()->withErrors('Keranjang kosong. Tambahkan produk terlebih dahulu.');
        }

        $subtotal = collect($items)->sum(fn ($i) => (int) $i['qty'] * (float) $i['price']);

        // === OPSIONAL: Simpan ke database jika model tersedia ===
        // - Tidak akan error kalau model belum kamu buat, karena dicek dengan class_exists.
        if (class_exists('\\App\\Models\\Order') && class_exists('\\App\\Models\\OrderItem')) {
            DB::transaction(function () use ($payload, $items, $subtotal) {
                /** @var \App\Models\Order $order */
                $order = \App\Models\Order::create([
                    'name'    => $payload['name'],
                    'email'   => $payload['email'],
                    'phone'   => $payload['phone'],
                    'address' => $payload['address'],
                    'total'   => $subtotal,
                    'status'  => 'pending',
                ]);

                foreach ($items as $it) {
                    \App\Models\OrderItem::create([
                        'order_id'  => $order->id,
                        'product_id'=> $it['id'] ?? null,
                        'name'      => $it['name'],
                        'color'     => $it['color'] ?? null,
                        'size'      => $it['size'] ?? null,
                        'qty'       => (int) $it['qty'],
                        'price'     => (float) $it['price'],
                        'image_url' => $it['image_url'] ?? null,
                    ]);
                }
            });
        } else {
            // Kalau belum ada model, simpan ringkasan order ke session saja (sementara)
            session()->put('last_order_preview', [
                'customer' => $payload,
                'items'    => $items,
                'total'    => $subtotal,
                'status'   => 'pending',
            ]);
        }

        // Bersihkan session sumber data
        if ($buyNow) {
            session()->forget('buy_now');
        } else {
            session()->forget('cart');
        }

        // Redirect ke halaman sukses/checkout (sesuaikan)
        return redirect()->route('checkout.view')->with('success', 'Order berhasil dibuat. Lanjutkan pembayaran.');
    }

    /**
     * Ubah session buy_now (product_id, color, size, qty) -> item lengkap
     */
    private function mapBuyNowToItem(array $data): array
    {
        $p = Product::find($data['product_id']);

        return [
            'id'        => $p?->id,
            'name'      => $p?->name ?? 'Unknown Product',
            'color'     => $data['color'] ?? null,
            'size'      => $data['size'] ?? null,
            'qty'       => (int) ($data['qty'] ?? 1),
            'price'     => (float) ($p?->price ?? 0),
            'image_url' => $p?->image_url,
        ];
    }
}
