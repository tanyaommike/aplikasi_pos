<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-3">
                    <i class="fas fa-chart-line text-indigo-600"></i>
                    Laporan &amp; Analitik
                </h2>
                <p class="text-sm text-slate-600 mt-1">Ringkasan performa penjualan periode terpilih</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <!-- Filter Periode -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
            <form method="GET" action="{{ route('laporan.index') }}" class="flex flex-col sm:flex-row sm:items-end gap-3">
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ $startDate->toDateString() }}"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ $endDate->toDateString() }}"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                </div>
                <button type="submit" class="inline-flex items-center justify-center gap-2 bg-slate-800 hover:bg-slate-900 text-white px-6 py-3 rounded-xl font-medium transition-colors shadow-sm">
                    <i class="fas fa-filter"></i>
                    Terapkan
                </button>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 border border-slate-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-wallet text-white text-xl"></i>
                    </div>
                    <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-3 py-1 rounded-full">Periode</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 mb-1">{{ format_rupiah($totalPendapatan) }}</h3>
                <p class="text-sm text-slate-600">Total Pendapatan</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 border border-slate-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-receipt text-white text-xl"></i>
                    </div>
                    <span class="text-xs font-semibold text-green-600 bg-green-50 px-3 py-1 rounded-full">Periode</span>
                </div>
                <h3 class="text-3xl font-bold text-slate-800 mb-1">{{ $totalTransaksi }}</h3>
                <p class="text-sm text-slate-600">Total Transaksi</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 border border-slate-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-calculator text-white text-xl"></i>
                    </div>
                    <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">Rata-rata</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 mb-1">{{ format_rupiah($rataRataTransaksi) }}</h3>
                <p class="text-sm text-slate-600">Per Transaksi</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 border border-slate-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-box-open text-white text-xl"></i>
                    </div>
                    <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">Periode</span>
                </div>
                <h3 class="text-3xl font-bold text-slate-800 mb-1">{{ $totalItemTerjual }}</h3>
                <p class="text-sm text-slate-600">Item Terjual</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Sales Chart -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-chart-area text-indigo-600"></i>
                    Tren Penjualan Harian
                </h3>
                @if($totalTransaksi > 0)
                    <div class="relative" style="height: 300px;">
                        <canvas id="salesChart"></canvas>
                    </div>
                @else
                    <div class="text-center py-16">
                        <i class="fas fa-chart-area text-slate-300 text-5xl mb-3"></i>
                        <p class="text-slate-500 font-medium">Belum ada data penjualan</p>
                        <p class="text-sm text-slate-400 mt-1">Coba ubah rentang tanggal</p>
                    </div>
                @endif
            </div>

            <!-- Payment Method Breakdown -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-credit-card text-indigo-600"></i>
                    Metode Pembayaran
                </h3>
                @forelse ($metodePembayaran as $metode)
                    @php
                        $label = match($metode->payment_method) {
                            'cash' => 'Tunai',
                            'qris' => 'QRIS',
                            'debit' => 'Kartu Debit',
                            'credit' => 'Kartu Kredit',
                            default => ucfirst($metode->payment_method),
                        };
                        $persen = $totalPendapatan > 0 ? round(($metode->total / $totalPendapatan) * 100) : 0;
                    @endphp
                    <div class="mb-4 last:mb-0">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-sm font-medium text-slate-700">{{ $label }}</span>
                            <span class="text-sm font-bold text-slate-800">{{ format_rupiah($metode->total) }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2">
                            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2 rounded-full" style="width: {{ $persen }}%"></div>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">{{ $metode->jumlah }} transaksi &middot; {{ $persen }}%</p>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <i class="fas fa-credit-card text-slate-300 text-4xl mb-3"></i>
                        <p class="text-slate-500 text-sm">Belum ada data pembayaran</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Top Produk -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
            <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                <i class="fas fa-trophy text-indigo-600"></i>
                Produk Terlaris
            </h3>
            @if($produkTerlaris->count() > 0)
                <div class="space-y-3">
                    @foreach ($produkTerlaris as $index => $item)
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-9 h-9 rounded-lg flex items-center justify-center font-bold text-sm text-white
                                    {{ $index === 0 ? 'bg-gradient-to-br from-amber-400 to-amber-600' : ($index === 1 ? 'bg-gradient-to-br from-slate-400 to-slate-500' : ($index === 2 ? 'bg-gradient-to-br from-orange-400 to-orange-600' : 'bg-gradient-to-br from-indigo-400 to-indigo-500')) }}">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $item->produk->nama_produk ?? 'Produk Dihapus' }}</p>
                                    <p class="text-xs text-slate-500">{{ $item->total_qty }} terjual</p>
                                </div>
                            </div>
                            <p class="font-bold text-slate-800">{{ format_rupiah($item->total_omzet) }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-slate-300 text-5xl mb-3"></i>
                    <p class="text-slate-500 font-medium">Belum ada produk terjual</p>
                </div>
            @endif
        </div>

        <!-- Daftar Transaksi -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-list text-indigo-600"></i>
                    Daftar Transaksi Periode Ini
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">No Transaksi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Kasir</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Pembayaran</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($transaksi as $item)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-bold text-slate-800">{{ $item->no_transaksi }}</td>
                                <td class="px-6 py-4 text-sm text-slate-700">{{ $item->user->name }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $item->tanggal_transaksi->format('d M Y, H:i') }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center text-xs font-semibold text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-lg uppercase">
                                        {{ $item->payment_method }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-green-600">{{ format_rupiah($item->total_harga) }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('transaksi.show', $item->id) }}" class="inline-flex items-center gap-1 bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
                                        <i class="fas fa-eye"></i>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-inbox text-slate-300 text-5xl mb-3"></i>
                                        <p class="text-slate-500 font-medium">Tidak ada transaksi pada periode ini</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($transaksi->hasPages())
        <div class="mt-6">
            {{ $transaksi->links() }}
        </div>
        @endif
    </div>

    @if($totalTransaksi > 0)
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('salesChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Pendapatan',
                    data: {!! json_encode($chartData) !!},
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 3,
                    pointBackgroundColor: '#6366f1',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (item) => 'Rp ' + item.parsed.y.toLocaleString('id-ID')
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: (value) => 'Rp ' + (value / 1000) + 'rb'
                        },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    </script>
    @endpush
    @endif
</x-app-layout>
