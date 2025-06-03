<?php

namespace App\Http\Controllers\Midtrans;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\carts;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Produk;
use App\Models\OrderAlamat;

class CODCOntroller extends Controller
{
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

        carts::where('user_id', auth()->id())->delete();

        return response()->json([
            'status' => 'success',
            'order_id' => $Order->id
        ]);
    }

    
}
