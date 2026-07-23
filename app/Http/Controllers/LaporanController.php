<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    private function resolvePeriode(Request $request): array
    {
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

        return [$startDate, $endDate];
    }

    private function buildLaporanData(Carbon $startDate, Carbon $endDate): array
    {
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

        return compact(
            'totalPendapatan',
            'totalTransaksi',
            'rataRataTransaksi',
            'totalItemTerjual',
            'chartLabels',
            'chartData',
            'produkTerlaris',
            'metodePembayaran'
        );
    }

    public function index(Request $request)
    {
        $this->checkAdmin();

        [$startDate, $endDate] = $this->resolvePeriode($request);
        $data = $this->buildLaporanData($startDate, $endDate);

        $transaksi = Transaksi::whereBetween('tanggal_transaksi', [$startDate, $endDate])
            ->with('user')
            ->latest('tanggal_transaksi')
            ->paginate(10)
            ->withQueryString();

        return view('laporan.index', array_merge($data, compact('startDate', 'endDate', 'transaksi')));
    }

    // Tampilan siap-cetak (untuk disimpan sebagai PDF lewat dialog print browser)
    public function cetak(Request $request)
    {
        $this->checkAdmin();

        [$startDate, $endDate] = $this->resolvePeriode($request);
        $data = $this->buildLaporanData($startDate, $endDate);

        $transaksi = Transaksi::whereBetween('tanggal_transaksi', [$startDate, $endDate])
            ->with('user')
            ->latest('tanggal_transaksi')
            ->get();

        return view('laporan.cetak', array_merge($data, compact('startDate', 'endDate', 'transaksi')));
    }

    // Export daftar transaksi periode terpilih ke CSV (bisa dibuka Excel)
    public function exportCsv(Request $request): StreamedResponse
    {
        $this->checkAdmin();

        [$startDate, $endDate] = $this->resolvePeriode($request);

        $transaksi = Transaksi::whereBetween('tanggal_transaksi', [$startDate, $endDate])
            ->with('user')
            ->latest('tanggal_transaksi')
            ->get();

        $filename = 'laporan-penjualan_'.$startDate->format('Ymd').'-'.$endDate->format('Ymd').'.csv';

        $callback = function () use ($transaksi) {
            $out = fopen('php://output', 'w');
            // BOM supaya Excel membaca UTF-8 dengan benar
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['No Transaksi', 'Tanggal', 'Kasir', 'Metode Pembayaran', 'Total']);

            foreach ($transaksi as $item) {
                fputcsv($out, [
                    $item->no_transaksi,
                    $item->tanggal_transaksi->format('Y-m-d H:i'),
                    $item->user->name,
                    strtoupper($item->payment_method),
                    $item->total_harga,
                ]);
            }

            fclose($out);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
