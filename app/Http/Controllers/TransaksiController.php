<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Produk;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Requests\AddToCartRequest;
use Illuminate\Support\Facades\DB;

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

        $produk = Produk::with('kategori')->where('stok', '>', 0)->get();
        $cart = session()->get('cart', []);
        
        return view('transaksi.create', compact('produk', 'cart'));
    }

    // 2. Tambah item ke cart (AJAX)
    public function addToCart(AddToCartRequest $request)
    {
        $validated = $request->validated();
        $produk = Produk::findOrFail($validated['produk_id']);

        $cart = session()->get('cart', []);

        // Jumlah produk ini yang SUDAH ada di cart
        $jumlahDiCart = $cart[$validated['produk_id']]['jumlah'] ?? 0;
        $totalJumlah = $jumlahDiCart + $validated['jumlah'];

        // Cek stok terhadap TOTAL, bukan cuma jumlah yang baru diinput
        if ($totalJumlah > $produk->stok) {
            $sisa = max(0, $produk->stok - $jumlahDiCart);
            return response()->json([
                'message' => "Stok tidak cukup. Sisa yang bisa ditambahkan: {$sisa}",
            ], 400);
        }

        if (isset($cart[$validated['produk_id']])) {
            $cart[$validated['produk_id']]['jumlah'] = $totalJumlah;
            $cart[$validated['produk_id']]['subtotal'] = $produk->harga * $totalJumlah;
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

        // Validate payment
        $request->validate([
            'payment_method' => 'required|in:cash,qris,debit,credit',
            'uang_dibayar' => 'required_if:payment_method,cash|nullable|numeric|min:0',
        ]);

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['subtotal'];
        }

        // Validasi uang dibayar untuk cash
        if ($request->payment_method === 'cash' && $request->uang_dibayar < $total) {
            return redirect()->back()->with('error', 'Uang yang dibayar kurang dari total belanja');
        }

        $kembalian = 0;
        if ($request->payment_method === 'cash') {
            $kembalian = $request->uang_dibayar - $total;
        }

        try {
            $transaksi = DB::transaction(function () use ($cart, $request, $total, $kembalian) {
                $no_transaksi = 'TRX-' . date('Ymd') . '-' . time();

                $transaksi = Transaksi::create([
                    'user_id' => auth()->id(),
                    'total_harga' => $total,
                    'no_transaksi' => $no_transaksi,
                    'tanggal_transaksi' => now(),
                    'payment_method' => $request->payment_method,
                    'payment_status' => 'paid',
                    'uang_dibayar' => $request->payment_method === 'cash' ? $request->uang_dibayar : null,
                    'kembalian' => $request->payment_method === 'cash' ? $kembalian : null,
                ]);

                foreach ($cart as $item) {
                    // Kunci baris produk ini selama transaksi berjalan
                    $produk = Produk::where('id', $item['produk_id'])->lockForUpdate()->first();

                    if (!$produk || $produk->stok < $item['jumlah']) {
                        throw new \Exception("Stok {$item['nama_produk']} tidak cukup, transaksi dibatalkan.");
                    }

                    TransaksiDetail::create([
                        'transaksi_id' => $transaksi->id,
                        'produk_id' => $item['produk_id'],
                        'jumlah' => $item['jumlah'],
                        'harga_satuan' => $item['harga_satuan'],
                        'subtotal' => $item['subtotal'],
                    ]);

                    $stokSebelum = $produk->stok;
                    $produk->decrement('stok', $item['jumlah']);

                    StockHistory::create([
                        'produk_id' => $produk->id,
                        'user_id' => auth()->id(),
                        'tipe' => 'keluar',
                        'jumlah' => $item['jumlah'],
                        'stok_sebelum' => $stokSebelum,
                        'stok_sesudah' => $stokSebelum - $item['jumlah'],
                        'keterangan' => "Penjualan {$no_transaksi}",
                    ]);
                }

                return $transaksi;
            });
        } catch (\Exception $e) {
            return redirect()->route('transaksi.create')->with('error', $e->getMessage());
        }

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

        $transaksi->load(['user', 'detail.produk']);

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
            $transaksi = Transaksi::with('user', 'detail')->where('user_id', auth()->id())->latest()->paginate(10);
        } else {
            $transaksi = Transaksi::with('user', 'detail')->latest()->paginate(10);
        }

        return view('transaksi.index', compact('transaksi'));
    }
}