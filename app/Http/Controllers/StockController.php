<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\DB;

class StockController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    private function checkAdmin()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang bisa akses manajemen stok');
        }
    }

    // Dashboard monitoring stok
    public function index(Request $request)
    {
        $this->checkAdmin();

        $query = Produk::with('kategori');

        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->status === 'habis') {
            $query->where('stok', 0);
        } elseif ($request->status === 'rendah') {
            $query->whereBetween('stok', [1, 5]);
        } elseif ($request->status === 'aman') {
            $query->where('stok', '>', 5);
        }

        $produk = $query->orderBy('stok')->paginate(12)->withQueryString();

        $totalProduk = Produk::count();
        $totalStokHabis = Produk::where('stok', 0)->count();
        $totalStokRendah = Produk::whereBetween('stok', [1, 5])->count();
        $totalNilaiStok = Produk::selectRaw('SUM(stok * harga) as total')->value('total') ?? 0;

        $kategori = Kategori::all();

        return view('stok.index', compact(
            'produk',
            'totalProduk',
            'totalStokHabis',
            'totalStokRendah',
            'totalNilaiStok',
            'kategori'
        ));
    }

    // Riwayat mutasi stok (semua produk atau difilter)
    public function riwayat(Request $request)
    {
        $this->checkAdmin();

        $query = StockHistory::with(['produk', 'user']);

        if ($request->filled('produk_id')) {
            $query->where('produk_id', $request->produk_id);
        }

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        $riwayat = $query->latest('created_at')->paginate(15)->withQueryString();
        $produkList = Produk::withTrashed()->orderBy('nama_produk')->get();

        return view('stok.riwayat', compact('riwayat', 'produkList'));
    }

    // Form mutasi stok (restock / keluar / penyesuaian)
    public function mutasiForm(Produk $produk)
    {
        $this->checkAdmin();

        $riwayatTerakhir = $produk->stockHistories()->with('user')->take(5)->get();

        return view('stok.mutasi', compact('produk', 'riwayatTerakhir'));
    }

    // Proses mutasi stok
    public function mutasi(Request $request, Produk $produk)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'tipe' => 'required|in:masuk,keluar,penyesuaian',
            'jumlah' => 'required|integer|min:0',
            'keterangan' => 'nullable|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($validated, $produk) {
                $produk = Produk::where('id', $produk->id)->lockForUpdate()->first();
                $stokSebelum = $produk->stok;

                $stokSesudah = match ($validated['tipe']) {
                    'masuk' => $stokSebelum + $validated['jumlah'],
                    'keluar' => $stokSebelum - $validated['jumlah'],
                    'penyesuaian' => $validated['jumlah'],
                };

                if ($stokSesudah < 0) {
                    throw new \Exception('Stok tidak cukup untuk mutasi ini.');
                }

                $produk->update(['stok' => $stokSesudah]);

                StockHistory::create([
                    'produk_id' => $produk->id,
                    'user_id' => auth()->id(),
                    'tipe' => $validated['tipe'],
                    'jumlah' => abs($stokSesudah - $stokSebelum),
                    'stok_sebelum' => $stokSebelum,
                    'stok_sesudah' => $stokSesudah,
                    'keterangan' => $validated['keterangan'] ?? null,
                ]);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('stok.index')->with('success', "Stok {$produk->nama_produk} berhasil diperbarui");
    }
}
