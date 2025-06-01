<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk; // Import the Produk model at the top of the controller

class FrontController extends Controller // Gunakan 'FrontController' dengan huruf kapital
{
    public function index()
    {
        $produks = Produk::all(); // Or apply any query conditions as needed

        return view('frontend.index', compact('produks'));
    }

    public function checkout()
    {
        return view('frontend.checkout');
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function shop()
    {
    // Retrieve all products from the database
    $produks = Produk::all(); // Or apply any query conditions as needed

    // Pass the products to the 'frontend.shop' view
    return view('frontend.shop', compact('produks'));
    }
    public function rekomendasi()
    {
        return view('frontend.rekomendasi');
    }
}
