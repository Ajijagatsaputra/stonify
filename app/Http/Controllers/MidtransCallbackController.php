<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Midtrans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        Log::info('Midtrans Callback', $payload);

        $orderId = $payload['order_id'];
        $transactionStatus = $payload['transaction_status'];
        $fraudStatus = $payload['fraud_status'];

        $order = Order::whereHas('midtrans', function($query) use ($orderId) {
            $query->where('order_id', $orderId);
        })->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $midtrans = $order->midtrans;

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $order->status = 'processing';
                $midtrans->transaction_status = 'challenge';
            } else if ($fraudStatus == 'accept') {
                $order->status = 'success';
                $midtrans->transaction_status = 'success';
            }
        } else if ($transactionStatus == 'settlement') {
            $order->status = 'success';
            $midtrans->transaction_status = 'settlement';
        } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            $order->status = 'failed';
            $midtrans->transaction_status = $transactionStatus;
        } else if ($transactionStatus == 'pending') {
            $order->status = 'pending';
            $midtrans->transaction_status = 'pending';
        }

        // Update payment details
        $midtrans->payment_type = $payload['payment_type'] ?? $midtrans->payment_type;
        $midtrans->transaction_time = $payload['transaction_time'] ?? $midtrans->transaction_time;
        $midtrans->gross_amount = $payload['gross_amount'] ?? $midtrans->gross_amount;

        if (isset($payload['va_numbers'][0])) {
            $midtrans->bank = $payload['va_numbers'][0]['bank'];
            $midtrans->va_number = $payload['va_numbers'][0]['va_number'];
        }

        if (isset($payload['pdf_url'])) {
            $midtrans->pdf_url = $payload['pdf_url'];
        }

        $midtrans->save();
        $order->save();

        return response()->json(['message' => 'Success']);
    }
}
