<?php

namespace App\Http\Controllers\Midtrans;

use App\Http\Controllers\Controller;
use App\Models\carts;
use App\Models\Midtrans;
use App\Models\Order;
use App\Models\OrderAlamat;
use App\Models\OrderItem;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function bikinTransaksi(Request $request)
    {
        $validation = $request->validate([
            'c_fname' => 'required',
            'c_lname' => 'required',
            'c_address' => 'required',
            'c_state_country' => 'required',
            'c_postal_zip' => 'required',
            'c_email_address' => 'required|email',
            'c_phone' => 'required',
            'c_order_notes' => 'nullable',
            'payment_method' => 'required',
            'amount' => 'required',
        ]);

        $cart = carts::where('user_id', auth()->id())->get();
        $orderId = 'ORDER-' . time() . '-' . auth()->id();


        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $request->amount,
            ],
            'customer_details' => [
                'first_name' => $request->c_fname,
                'last_name' => $request->c_lname,
                'email' => $request->c_email_address,
                'phone' => $request->c_phone,
                'address' => $request->c_address,
                'city' => $request->c_state_country,
                'postal_code' => $request->c_postal_zip
            ],
            'enabled_payments' => [$request->payment_method],
            'item_details' => $cart->map(function($item) {
                return [
                    'id' => $item->id,
                    'price' => $item->product->harga,
                    'quantity' => $item->quantity,
                    'name' => $item->product->nama_produk,
                ];
            })->toArray(),
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            $Order = Order::create([
                'user_id' => auth()->id(),
                'total' => $request->amount,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'order_notes' => $request->c_order_notes
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $Order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->harga,
                    'subtotal' => $item->product->harga * $item->quantity
                ]);

                Produk::where('id', $item->product_id)->decrement('stok', $item->quantity);
            }

            OrderAlamat::create([
                'order_id' => $Order->id,
                'first_name' => $request->c_fname,
                'last_name' => $request->c_lname,
                'address_line1' => $request->c_address,
                'address_line2' => $request->c_order_notes,
                'state_country' => $request->c_state_country,
                'postal_code' => $request->c_postal_zip,
                'email' => $request->c_email_address,
                'phone' => $request->c_phone
            ]);

            Midtrans::create([
                'transaction_id' => $Order->id,
                'snap_token' => $snapToken,
                'order_id' => $params['transaction_details']['order_id']
            ]);

            carts::where('user_id', auth()->id())->delete();



            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $params['transaction_details']['order_id']
            ]);


        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle Midtrans notification callback
     */
    public function handleNotification(Request $request)
    {
        try {
            $notification = new Notification();

            $transactionStatus = $notification->transaction_status;
            $type = $notification->payment_type;
            $orderId = $notification->order_id;
            $fraudStatus = $notification->fraud_status ?? null;

            Log::info('Midtrans Notification Received', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'payment_type' => $type,
                'fraud_status' => $fraudStatus
            ]);

            $midtransRecord = Midtrans::where('order_id', $orderId)->first();

            if (!$midtransRecord) {
                Log::error('Midtrans record not found for order_id: ' . $orderId);
                return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
            }

            $order = Order::find($midtransRecord->transaction_id);

            if (!$order) {
                Log::error('Order not found for transaction_id: ' . $midtransRecord->transaction_id);
                return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
            }

            DB::beginTransaction();

            try {
                switch ($transactionStatus) {
                    case 'capture':
                        if ($type == 'credit_card') {
                            if ($fraudStatus == 'challenge') {
                                $this->updateOrderStatus($order, 'pending', 'Payment challenged, waiting for confirmation');
                            } else {
                                $this->updateOrderStatus($order, 'paid', 'Payment successful via credit card');
                            }
                        }
                        break;

                    case 'settlement':
                        $this->updateOrderStatus($order, 'paid', 'Payment settled successfully');
                        break;

                    case 'pending':
                        $this->updateOrderStatus($order, 'pending', 'Payment pending');
                        break;

                    case 'deny':
                        $this->handleFailedPayment($order, 'Payment denied');
                        break;

                    case 'expire':
                        $this->handleFailedPayment($order, 'Payment expired');
                        break;

                    case 'cancel':
                        $this->handleFailedPayment($order, 'Payment cancelled');
                        break;

                    case 'failure':
                        $this->handleFailedPayment($order, 'Payment failed');
                        break;

                    default:
                        Log::warning('Unhandled transaction status: ' . $transactionStatus);
                        break;
                }

                // Update midtrans record
                $midtransRecord->update([
                    'status' => $transactionStatus,
                    'payment_type' => $type,
                    'fraud_status' => $fraudStatus,
                    'updated_at' => now()
                ]);

                DB::commit();

                return response()->json(['status' => 'success']);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error processing notification: ' . $e->getMessage());
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Midtrans notification error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update order status
     */
    private function updateOrderStatus(Order $order, string $status, string $message = '')
    {
        $order->update(['status' => $status]);

        if ($status === 'paid') {
            $this->handleSuccessfulPayment($order);
        }
    }

    /**
     * Handle failed payment
     */
    private function handleFailedPayment(Order $order, string $reason)
    {
        if ($order->status === 'pending') {
            foreach ($order->orderItems as $item) {
                Produk::where('id', $item->product_id)
                      ->increment('stok', $item->quantity);
            }

        }

        $this->updateOrderStatus($order, 'failed', $reason);
    }

    /**
     * Handle successful payment
     */
    private function handleSuccessfulPayment(Order $order)
    {
        $order->update([
            'paid_at' => now(),
            'status' => 'paid'
        ]);

        Log::info("Payment successful for order {$order->id}");
    }


}
