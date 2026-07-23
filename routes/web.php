<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KategoriController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Kategori routes
    Route::resource('kategori', KategoriController::class);
    
    // Produk routes
    Route::resource('produk', ProdukController::class);
    
    // Transaksi routes
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/create', [TransaksiController::class, 'create'])->name('transaksi.create');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('/transaksi/{transaksi}', [TransaksiController::class, 'show'])->name('transaksi.show');
    Route::post('/transaksi/add-to-cart', [TransaksiController::class, 'addToCart'])->name('transaksi.addToCart');
    Route::delete('/transaksi/remove-from-cart/{produk_id}', [TransaksiController::class, 'removeFromCart'])->name('transaksi.removeFromCart');
});

require __DIR__.'/auth.php';
