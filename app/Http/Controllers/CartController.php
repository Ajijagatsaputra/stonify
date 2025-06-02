<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\carts;
use App\Models\Produk; // Perbaiki ini
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $data = $request->all();
        $userId = auth()->id();
        $productId = $data['id'];

        DB::beginTransaction();

        try {
            $cart = carts::lockForUpdate()
                ->where('user_id', $userId)
                ->where('product_id', $productId)
                ->first();

            if ($cart) {
                $cart->quantity += 1;
                $cart->save();
            } else {
                carts::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'quantity' => 1,
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Produk berhasil ditambahkan ke keranjang!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan.'], 500);
        }
    }


    // Menampilkan keranjang belanja
    public function index()
    {
        $cartItems = carts::with('product') // Ini akan merujuk pada relasi yang sudah didefinisikan di model
            ->where('user_id', auth()->id())
            ->get();

        return view('frontend.cart', ['cart' => $cartItems]);
    }

    // Mengupdate jumlah produk di keranjang
    public function updateCart(Request $request)
    {
        $cart = carts::where('user_id', auth()->id())->get();
        $item = $cart->firstWhere('product_id', $request->id);

        if ($item) {
            $item->quantity = $request->quantity;
            $item->save();
        }

        $total = $cart->sum(function ($item) {
            return $item->product->harga * $item->quantity;
        });

        return response()->json([
            'cart' => $cart,
            'total' => $total,
        ]);
    }

    // Menghapus produk dari keranjang
    public function delete(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer'
        ]);

        $cart = carts::where('user_id', auth()->id())
                    ->where('product_id', $request->product_id)
                    ->first();

        if ($cart) {
            $cart->delete();

            $updatedCart = carts::with('product')
                            ->where('user_id', auth()->id())
                            ->get();

            $total = $updatedCart->sum(function ($item) {
                return $item->product->harga * $item->quantity;
            });

            return response()->json([
                'cart' => $updatedCart,
                'total' => $total,
                'message' => 'Produk berhasil dihapus'
            ]);
        }

        return response()->json(['message' => 'Produk tidak ditemukan di keranjang.'], 404);
    }


    // Mendapatkan keranjang pengguna
    private function getCart()
    {
        return carts::with('product') // Pastikan relasi 'product' sudah didefinisikan di model Cart
                    ->where('user_id', auth()->id())
                    ->get();
    }


    
}
