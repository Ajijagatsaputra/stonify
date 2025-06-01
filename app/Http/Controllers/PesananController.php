<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Produk;

class PesananController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::with('produk')->get();
        return view('pesanan.index', compact('pesanan'));
    }
}
