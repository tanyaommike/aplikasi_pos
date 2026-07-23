<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Penjualan - {{ $startDate->format('d M Y') }} s/d {{ $endDate->format('d M Y') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800">
    <div class="no-print bg-white border-b border-slate-200 sticky top-0 py-4 px-6 flex items-center justify-between shadow-sm">
        <p class="text-sm text-slate-500">Pratinjau cetak &mdash; gunakan tombol di kanan untuk menyimpan sebagai PDF</p>
        <button onclick="window.print()" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-5 rounded-xl transition-colors shadow-sm">
            Cetak / Simpan PDF
        </button>
    </div>

    <div class="max-w-4xl mx-auto bg-white my-8 p-10 shadow-sm print:shadow-none print:my-0">
        <!-- Header -->
        <div class="flex items-center justify-between border-b border-slate-200 pb-6 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Laporan Penjualan</h1>
                <p class="text-sm text-slate-500 mt-1">{{ config('app.name', 'POS System') }}</p>
            </div>
            <div class="text-right text-sm text-slate-600">
                <p class="font-semibold">Periode</p>
                <p>{{ $startDate->translatedFormat('d MMMM Y') }} &ndash; {{ $endDate->translatedFormat('d MMMM Y') }}</p>
                <p class="text-xs text-slate-400 mt-1">Dicetak: {{ now()->translatedFormat('d MMMM Y, H:i') }}</p>
            </div>
        </div>

        <!-- Ringkasan -->
        <div class="grid grid-cols-4 gap-4 mb-8">
            <div class="border border-slate-200 rounded-xl p-4">
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Total Pendapatan</p>
                <p class="text-lg font-bold text-slate-800">{{ format_rupiah($totalPendapatan) }}</p>
            </div>
            <div class="border border-slate-200 rounded-xl p-4">
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Total Transaksi</p>
                <p class="text-lg font-bold text-slate-800">{{ $totalTransaksi }}</p>
            </div>
            <div class="border border-slate-200 rounded-xl p-4">
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Rata-rata/Transaksi</p>
                <p class="text-lg font-bold text-slate-800">{{ format_rupiah($rataRataTransaksi) }}</p>
            </div>
            <div class="border border-slate-200 rounded-xl p-4">
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Item Terjual</p>
                <p class="text-lg font-bold text-slate-800">{{ $totalItemTerjual }}</p>
            </div>
        </div>

        <!-- Produk Terlaris -->
        <div class="mb-8">
            <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-3">Produk Terlaris</h2>
            @if($produkTerlaris->count() > 0)
                <table class="w-full text-sm border border-slate-200 rounded-lg overflow-hidden">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="text-left px-4 py-2 font-semibold text-slate-600">Produk</th>
                            <th class="text-right px-4 py-2 font-semibold text-slate-600">Qty Terjual</th>
                            <th class="text-right px-4 py-2 font-semibold text-slate-600">Omzet</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach ($produkTerlaris as $item)
                            <tr>
                                <td class="px-4 py-2">{{ $item->produk->nama_produk ?? 'Produk Dihapus' }}</td>
                                <td class="px-4 py-2 text-right">{{ $item->total_qty }}</td>
                                <td class="px-4 py-2 text-right">{{ format_rupiah($item->total_omzet) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-sm text-slate-400">Tidak ada data.</p>
            @endif
        </div>

        <!-- Metode Pembayaran -->
        <div class="mb-8">
            <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-3">Metode Pembayaran</h2>
            @if($metodePembayaran->count() > 0)
                <table class="w-full text-sm border border-slate-200 rounded-lg overflow-hidden">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="text-left px-4 py-2 font-semibold text-slate-600">Metode</th>
                            <th class="text-right px-4 py-2 font-semibold text-slate-600">Jumlah Transaksi</th>
                            <th class="text-right px-4 py-2 font-semibold text-slate-600">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach ($metodePembayaran as $metode)
                            <tr>
                                <td class="px-4 py-2 uppercase">{{ $metode->payment_method }}</td>
                                <td class="px-4 py-2 text-right">{{ $metode->jumlah }}</td>
                                <td class="px-4 py-2 text-right">{{ format_rupiah($metode->total) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-sm text-slate-400">Tidak ada data.</p>
            @endif
        </div>

        <!-- Daftar Transaksi -->
        <div>
            <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-3">Daftar Transaksi</h2>
            @if($transaksi->count() > 0)
                <table class="w-full text-sm border border-slate-200 rounded-lg overflow-hidden">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="text-left px-4 py-2 font-semibold text-slate-600">No Transaksi</th>
                            <th class="text-left px-4 py-2 font-semibold text-slate-600">Tanggal</th>
                            <th class="text-left px-4 py-2 font-semibold text-slate-600">Kasir</th>
                            <th class="text-left px-4 py-2 font-semibold text-slate-600">Pembayaran</th>
                            <th class="text-right px-4 py-2 font-semibold text-slate-600">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach ($transaksi as $item)
                            <tr>
                                <td class="px-4 py-2 font-medium">{{ $item->no_transaksi }}</td>
                                <td class="px-4 py-2">{{ $item->tanggal_transaksi->format('d M Y, H:i') }}</td>
                                <td class="px-4 py-2">{{ $item->user->name }}</td>
                                <td class="px-4 py-2 uppercase">{{ $item->payment_method }}</td>
                                <td class="px-4 py-2 text-right">{{ format_rupiah($item->total_harga) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-50 font-bold">
                            <td colspan="4" class="px-4 py-2 text-right">Total</td>
                            <td class="px-4 py-2 text-right">{{ format_rupiah($totalPendapatan) }}</td>
                        </tr>
                    </tfoot>
                </table>
            @else
                <p class="text-sm text-slate-400">Tidak ada transaksi pada periode ini.</p>
            @endif
        </div>
    </div>
</body>
</html>
