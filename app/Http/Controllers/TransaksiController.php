<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TransaksiController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    // 1. Tampilkan form transaksi (pilih produk)
    public function create()
    {
        // Hanya kasir & admin
        if (!in_array(auth()->user()->role, ['kasir', 'admin'])) {
            abort(403, 'Tidak berhak akses transaksi');
        }

        $produk = Produk::where('stok', '>', 0)->get();
        $cart = session()->get('cart', []);
        
        return view('transaksi.create', compact('produk', 'cart'));
    }

    // 2. Tambah item ke cart (AJAX)
    public function addToCart(Request $request)
    {
        if (!in_array(auth()->user()->role, ['kasir', 'admin'])) {
            abort(403);
        }

        $validated = $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $produk = Produk::find($validated['produk_id']);

        // Cek stok
        if ($produk->stok < $validated['jumlah']) {
            return response()->json(['message' => 'Stok tidak cukup'], 400);
        }

        $cart = session()->get('cart', []);
        
        // Jika produk sudah ada di cart, tambah jumlahnya
        if (isset($cart[$validated['produk_id']])) {
            $cart[$validated['produk_id']]['jumlah'] += $validated['jumlah'];
        } else {
            $cart[$validated['produk_id']] = [
                'produk_id' => $produk->id,
                'nama_produk' => $produk->nama_produk,
                'harga_satuan' => $produk->harga,
                'jumlah' => $validated['jumlah'],
                'subtotal' => $produk->harga * $validated['jumlah'],
            ];
        }

        session()->put('cart', $cart);

        return response()->json(['message' => 'Item ditambah', 'cart' => $cart]);
    }

    // 3. Hapus item dari cart
    public function removeFromCart($produk_id)
    {
        if (!in_array(auth()->user()->role, ['kasir', 'admin'])) {
            abort(403);
        }

        $cart = session()->get('cart', []);
        unset($cart[$produk_id]);
        session()->put('cart', $cart);

        return redirect()->route('transaksi.create')->with('success', 'Item dihapus dari keranjang');
    }

    // 4. Simpan transaksi
    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['kasir', 'admin'])) {
            abort(403);
        }

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang kosong');
        }

        // Hitung total
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['subtotal'];
        }

        // Generate nomor transaksi
        $no_transaksi = 'TRX-' . date('Ymd') . '-' . time();

        // Simpan transaksi header
        $transaksi = Transaksi::create([
            'user_id' => auth()->id(),
            'total_harga' => $total,
            'no_transaksi' => $no_transaksi,
            'tanggal_transaksi' => now(),
        ]);

        // Simpan detail transaksi & kurangi stok
        foreach ($cart as $item) {
            TransaksiDetail::create([
                'transaksi_id' => $transaksi->id,
                'produk_id' => $item['produk_id'],
                'jumlah' => $item['jumlah'],
                'harga_satuan' => $item['harga_satuan'],
                'subtotal' => $item['subtotal'],
            ]);

            // Kurangi stok produk
            $produk = Produk::find($item['produk_id']);
            $produk->update(['stok' => $produk->stok - $item['jumlah']]);
        }

        // Clear cart
        session()->forget('cart');

        return redirect()->route('transaksi.show', $transaksi->id)->with('success', 'Transaksi berhasil disimpan');
    }

    // 5. Tampilkan struk/detail transaksi
    public function show(Transaksi $transaksi)
    {
        if (!in_array(auth()->user()->role, ['kasir', 'admin'])) {
            abort(403);
        }

        // Hanya lihat transaksi user sendiri (kasir) atau semua (admin)
        if (auth()->user()->role === 'kasir' && $transaksi->user_id !== auth()->id()) {
            abort(403);
        }

        return view('transaksi.show', compact('transaksi'));
    }

    // 6. Tampilkan list transaksi
    public function index()
    {
        if (!in_array(auth()->user()->role, ['kasir', 'admin'])) {
            abort(403);
        }

        // Kasir hanya lihat transaksi mereka, admin lihat semua
        if (auth()->user()->role === 'kasir') {
            $transaksi = Transaksi::where('user_id', auth()->id())->latest()->paginate(10);
        } else {
            $transaksi = Transaksi::latest()->paginate(10);
        }

        return view('transaksi.index', compact('transaksi'));
    }
}