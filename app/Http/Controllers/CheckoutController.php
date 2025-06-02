<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\carts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CheckoutController extends Controller
{
    public function show()
    {
        $cart = carts::with('product')->where('user_id', Auth::id())->get();
        return view('frontend.checkout', compact('cart'));
    }

    public function history()
    {
        if (request()->ajax()) {
            $orders = Order::with('items.product')->where('user_id', Auth::id());
            return DataTables::of($orders)
                ->addColumn('actions', function ($order) {
                    return '<a href="' . route('orders.show', $order->id) . '" class="btn btn-primary btn-sm">Lihat</a>';
                })
                ->make(true);
        }

        return view('order.index');
    }
}
