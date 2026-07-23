<?php

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TransaksiController;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $totalProduk = Produk::count();
    $totalKategori = Kategori::count();
    $totalTransaksi = Transaksi::count();
    $produkHabis = Produk::where('stok', '<=', 5)->count();
    $transaksiHariIni = Transaksi::whereDate('tanggal_transaksi', today())->count();
    $pendapatanHariIni = Transaksi::whereDate('tanggal_transaksi', today())->sum('total_harga');

    $recentTransaksi = Transaksi::with('user')->latest()->take(5)->get();

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
    Route::post('/transaksi/{transaksi}/pembayaran-selesai', [TransaksiController::class, 'confirmPayment'])->name('transaksi.confirmPayment');

    // Laporan routes
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
    Route::get('/laporan/export-csv', [LaporanController::class, 'exportCsv'])->name('laporan.exportCsv');

    // Stok routes
    Route::get('/stok', [StockController::class, 'index'])->name('stok.index');
    Route::get('/stok/riwayat', [StockController::class, 'riwayat'])->name('stok.riwayat');
    Route::get('/produk/{produk}/mutasi', [StockController::class, 'mutasiForm'])->name('stok.mutasiForm');
    Route::post('/produk/{produk}/mutasi', [StockController::class, 'mutasi'])->name('stok.mutasi');
});

require __DIR__.'/auth.php';
