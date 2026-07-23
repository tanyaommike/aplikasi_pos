<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KategoriController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $totalProduk = \App\Models\Produk::count();
    $totalKategori = \App\Models\Kategori::count();
    $totalTransaksi = \App\Models\Transaksi::count();
    $produkHabis = \App\Models\Produk::where('stok', '<=', 5)->count();
    $transaksiHariIni = \App\Models\Transaksi::whereDate('tanggal_transaksi', today())->count();
    $pendapatanHariIni = \App\Models\Transaksi::whereDate('tanggal_transaksi', today())->sum('total_harga');
    
    $recentTransaksi = \App\Models\Transaksi::with('user')->latest()->take(5)->get();
    
    return view('dashboard', compact(
        'totalProduk', 
        'totalKategori', 
        'totalTransaksi', 
        'produkHabis',
        'transaksiHariIni',
        'pendapatanHariIni',
        'recentTransaksi'
    ));
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

    // Laporan routes
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
    Route::get('/laporan/export-csv', [LaporanController::class, 'exportCsv'])->name('laporan.exportCsv');
});

require __DIR__.'/auth.php';
