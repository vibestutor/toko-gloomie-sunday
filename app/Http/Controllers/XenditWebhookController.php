<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Carbon;

class XenditWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // verifikasi token (opsional namun disarankan)
        $token = $request->header('x-callback-token');
        $expected = config('services.xendit.webhook_token');
        if ($expected && $token !== $expected) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        $payload = $request->all();
        // Xendit invoice callback biasanya punya 'status', 'external_id', 'id', 'payment_channel'
        $status = strtoupper($payload['status'] ?? '');
        $externalId = $payload['external_id'] ?? null;
        $invoiceId  = $payload['id'] ?? null;

        $order = Order::where('external_id', $externalId)
                      ->orWhere('invoice_id', $invoiceId)
                      ->first();

        if (!$order) {
            return response()->json(['ok' => true]); // ignore unknown
        }

        if (in_array($status, ['PAID','SETTLED'])) {
            $order->status = 'paid';
            $order->paid_at = Carbon::now();
            $order->payment_channel = $payload['payment_channel'] ?? $order->payment_channel;
            $order->save();
        } elseif ($status === 'EXPIRED') {
            $order->status = 'expired';
            $order->save();
        } elseif ($status === 'FAILED') {
            $order->status = 'failed';
            $order->save();
        }

        return response()->json(['ok' => true]);
    }
}
