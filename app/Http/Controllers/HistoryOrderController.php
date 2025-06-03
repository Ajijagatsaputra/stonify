<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;


class HistoryOrderController extends Controller
{
    public function index()
    {
        $order = Order::with('items')->get();
        return view('order.index', compact('order'));
    }
}
