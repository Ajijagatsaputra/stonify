<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\carts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function show()
    {
        $cart = carts::with('product')->where('user_id', Auth::id())->get();
        return view('frontend.checkout', compact('cart'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'c_fname' => 'required',
            'c_lname' => 'required',
            'c_address' => 'required',
            'c_state_country' => 'required',
            'c_postal_zip' => 'required',
            'c_email_address' => 'required|email',
            'c_phone' => 'required',
            'payment_method' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $uniqueCode = rand(100, 999);
            $vaNumber = '1234567890'; // Ganti dengan nomor VA BCA Anda

            $order = Order::create([
                'user_id' => Auth::id(),
                'nama_depan' => $request->c_fname,
                'nama_belakang' => $request->c_lname,
                'alamat' => $request->c_address,
                'state_country' => $request->c_state_country,
                'postal_zip' => $request->c_postal_zip,
                'email' => $request->c_email_address,
                'phone' => $request->c_phone,
                'notes' => $request->c_order_notes,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'va_number' => $vaNumber,
                'unique_code' => $uniqueCode,
            ]);

            $cart = Cart::with('product')->where('user_id', Auth::id())->get();
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->harga,
                ]);
            }

            Cart::where('user_id', Auth::id())->delete();
            DB::commit();

            return redirect()->route('orders.history')->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function history()
    {
        $orders = Order::with('items.product')->where('user_id', Auth::id())->get();
        return view('orders.history', compact('orders'));
    }
}
