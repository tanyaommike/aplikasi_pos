<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Carbon;

class LaporanController extends Controller implements HasMiddleware
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
            abort(403, 'Hanya Admin yang bisa akses laporan');
        }
    }

    public function index(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)->startOfDay()
            : now()->startOfMonth()->startOfDay();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : now()->endOfDay();

        $baseQuery = Transaksi::whereBetween('tanggal_transaksi', [$startDate, $endDate]);

        // Ringkasan
        $totalPendapatan = (clone $baseQuery)->sum('total_harga');
        $totalTransaksi = (clone $baseQuery)->count();
        $rataRataTransaksi = $totalTransaksi > 0 ? intdiv($totalPendapatan, $totalTransaksi) : 0;
        $totalItemTerjual = TransaksiDetail::whereHas('transaksi', function ($q) use ($startDate, $endDate) {
            $q->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
        })->sum('jumlah');

        // Grafik penjualan harian
        $penjualanHarian = (clone $baseQuery)
            ->selectRaw('DATE(tanggal_transaksi) as tanggal, SUM(total_harga) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->pluck('total', 'tanggal');

        $chartLabels = [];
        $chartData = [];
        $periode = Carbon::parse($startDate)->startOfDay();
        while ($periode->lte($endDate)) {
            $key = $periode->toDateString();
            $chartLabels[] = $periode->translatedFormat('d M');
            $chartData[] = (int) ($penjualanHarian[$key] ?? 0);
            $periode->addDay();
        }

        // Produk terlaris
        $produkTerlaris = TransaksiDetail::whereHas('transaksi', function ($q) use ($startDate, $endDate) {
            $q->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
        })
            ->selectRaw('produk_id, SUM(jumlah) as total_qty, SUM(subtotal) as total_omzet')
            ->with('produk')
            ->groupBy('produk_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // Breakdown metode pembayaran
        $metodePembayaran = (clone $baseQuery)
            ->selectRaw('payment_method, COUNT(*) as jumlah, SUM(total_harga) as total')
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->get();

        // Daftar transaksi periode ini
        $transaksi = (clone $baseQuery)->with('user')->latest('tanggal_transaksi')->paginate(10)->withQueryString();

        return view('laporan.index', compact(
            'startDate',
            'endDate',
            'totalPendapatan',
            'totalTransaksi',
            'rataRataTransaksi',
            'totalItemTerjual',
            'chartLabels',
            'chartData',
            'produkTerlaris',
            'metodePembayaran',
            'transaksi'
        ));
    }
}
