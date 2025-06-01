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

Route::get('/', [frontController::class, 'index'])->name('frontend.index');
Route::get('/checkout', [frontController::class, 'checkout'])->name('frontend.checkout');
Route::get('/contact', [frontController::class, 'contact'])->name('frontend.contact');
Route::get('/shop', [frontController::class, 'shop'])->name('frontend.shop');
Route::get('/rekomendasi', [frontController::class, 'rekomendasi'])->name('frontend.rekomendasi');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('users', UserController::class);
Route::resource('artikels', ArtikelController::class);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('produk', ProdukController::class);
    Route::resource('pesanan', PesananController::class);

});

Route::middleware(['auth'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/{roomId}', [ChatController::class, 'show'])->name('chat.show');
});

Route::middleware('auth')->group(function () {
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('frontend.cart');
    Route::post('/update-cart', [CartController::class, 'updateCart']);
    Route::post('/remove-from-cart', [CartController::class, 'delete']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/orders/history', [CheckoutController::class, 'history'])->name('orders.history');
});


require __DIR__.'/auth.php';
