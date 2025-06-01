<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Menampilkan riwayat pesanan user
    public function history()
    {
        $orders = Order::with('items.product')
                    ->where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('order.history', compact('orders'));
    }

    // (Opsional) detail pesanan jika kamu mau
    public function show($id)
    {
        $order = Order::with('items.product')
                    ->where('user_id', Auth::id())
                    ->where('id', $id)
                    ->firstOrFail();

        return view('orders.show', compact('order'));
    }
}
