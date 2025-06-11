<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\frontController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\Midtrans\PaymentController;
use App\Models\Artikel;
use App\Models\carts;

Route::get('/', [frontController::class, 'index'])->name('frontend.index');
Route::get('/checkout', [frontController::class, 'checkout'])->name('frontend.checkout');
Route::get('/contact', [frontController::class, 'contact'])->name('frontend.contact');
Route::get('/shop', [frontController::class, 'shop'])->name('frontend.shop');
Route::get('/rekomendasi', [frontController::class, 'rekomendasi'])->name('frontend.rekomendasi');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('users', UserController::class);

Route::get('/list/artikel', function () {
    $artikels = Artikel::where('status', 'published')->latest()->get();
    return view('frontend.artikel', compact('artikels'));
})->name('frontend.artikel');

Route::get('/list/artikel/{slug}', function ($slug) {
    $artikel = Artikel::where('slug', $slug)->first();
    return view('frontend.artikel-detail', compact('artikel'));
})->name('frontend.artikel-detail');

Route::middleware('auth')->group(function () {
    Route::resource('artikels', ArtikelController::class);

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('produk', ProdukController::class);
    Route::resource('pesanan', PesananController::class);
    Route::post('/pesanan/proses/{id}', [PesananController::class, 'proses'])->name('pesanan.proses');

});

Route::middleware(['auth'])->group(function () {
    Route::get('/live-chat/{room}', [ChatController::class, 'show'])->name('chat.showing');

    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/start', [ChatController::class, 'start'])->name('chat.start');

    Route::get('/chat/start/{admin}', [ChatController::class, 'startChat'])->name('chat.starting');

    Route::post('/live-chat/{room}', [ChatController::class, 'store'])->name('chat.store');
    Route::post('/chat/room/create', [ChatController::class, 'createRoom'])->name('chat.create-room');
    Route::delete('/chat/message/{message}', [ChatController::class, 'deleteMessage'])->name('chat.delete-message');
});

Route::middleware('auth')->group(function () {
    Route::get('/cart/count', function () {
        $count = carts::where('user_id', auth()->id())
            ->count();

        return response()->json(['count' => $count]);
    })->name('cart.count');

    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('frontend.cart');
    Route::post('/update-cart', [CartController::class, 'updateCart']);
    Route::post('/remove-from-cart', [CartController::class, 'delete']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/cod', [CODCOntroller::class, 'bikinTransaksi'])->name('checkout.cod');
    Route::get('/orders/history', [CheckoutController::class, 'history'])->name('orders.history');
});


Route::post('/payment/checkout', [PaymentController::class, 'bikinTransaksi'])->name('payment.checkout');



require __DIR__.'/auth.php';
