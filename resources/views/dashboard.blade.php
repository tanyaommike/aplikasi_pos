<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-3">
                    <i class="fas fa-chart-line text-indigo-600"></i>
                    Dashboard
                </h2>
                <p class="text-sm text-slate-600 mt-1">Selamat datang, {{ Auth::user()->name }}!</p>
            </div>
            <div class="text-sm text-slate-600">
                <i class="far fa-calendar"></i>
                {{ now()->isoFormat('dddd, D MMMM YYYY') }}
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Produk -->
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 border border-slate-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-box text-white text-xl"></i>
                    </div>
                    <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">Total</span>
                </div>
                <h3 class="text-3xl font-bold text-slate-800 mb-1">{{ $totalProduk }}</h3>
                <p class="text-sm text-slate-600">Total Produk</p>
            </div>

            <!-- Total Kategori -->
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 border border-slate-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-tags text-white text-xl"></i>
                    </div>
                    <span class="text-xs font-semibold text-purple-600 bg-purple-50 px-3 py-1 rounded-full">Total</span>
                </div>
                <h3 class="text-3xl font-bold text-slate-800 mb-1">{{ $totalKategori }}</h3>
                <p class="text-sm text-slate-600">Total Kategori</p>
            </div>

            <!-- Transaksi Hari Ini -->
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 border border-slate-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-shopping-cart text-white text-xl"></i>
                    </div>
                    <span class="text-xs font-semibold text-green-600 bg-green-50 px-3 py-1 rounded-full">Hari Ini</span>
                </div>
                <h3 class="text-3xl font-bold text-slate-800 mb-1">{{ $transaksiHariIni }}</h3>
                <p class="text-sm text-slate-600">Transaksi</p>
            </div>

            <!-- Pendapatan Hari Ini -->
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 border border-slate-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-wallet text-white text-xl"></i>
                    </div>
                    <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-3 py-1 rounded-full">Hari Ini</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 mb-1">{{ format_rupiah($pendapatanHariIni) }}</h3>
                <p class="text-sm text-slate-600">Pendapatan</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Transactions -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-history text-indigo-600"></i>
                        Transaksi Terakhir
                    </h3>
                    <a href="{{ route('transaksi.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium flex items-center gap-1">
                        Lihat Semua
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse ($recentTransaksi as $transaksi)
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-semibold text-sm">
                                    <i class="fas fa-receipt"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $transaksi->no_transaksi }}</p>
                                    <p class="text-xs text-slate-500">{{ $transaksi->user->name }} • {{ $transaksi->tanggal_transaksi->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-slate-800">{{ format_rupiah($transaksi->total_harga) }}</p>
                                <a href="{{ route('transaksi.show', $transaksi) }}" class="text-xs text-indigo-600 hover:text-indigo-700">Detail</a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <i class="fas fa-inbox text-slate-300 text-5xl mb-3"></i>
                            <p class="text-slate-500">Belum ada transaksi</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Quick Actions & Alerts -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-bolt text-amber-500"></i>
                        Quick Actions
                    </h3>
                    <div class="space-y-2">
                        <a href="{{ route('transaksi.create') }}" class="flex items-center gap-3 p-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all shadow-md hover:shadow-lg">
                            <i class="fas fa-plus-circle"></i>
                            <span class="font-semibold">Transaksi Baru</span>
                        </a>
                        <a href="{{ route('produk.create') }}" class="flex items-center gap-3 p-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors">
                            <i class="fas fa-box"></i>
                            <span class="font-semibold">Tambah Produk</span>
                        </a>
                        <a href="{{ route('kategori.create') }}" class="flex items-center gap-3 p-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors">
                            <i class="fas fa-tags"></i>
                            <span class="font-semibold">Tambah Kategori</span>
                        </a>
                    </div>
                </div>

                <!-- Stok Alert -->
                @if($produkHabis > 0)
                <div class="bg-gradient-to-br from-red-50 to-orange-50 rounded-2xl shadow-sm border border-red-200 p-6">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center text-white flex-shrink-0">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-red-900 mb-1">Peringatan Stok!</h3>
                            <p class="text-sm text-red-700">{{ $produkHabis }} produk stok hampir habis (≤ 5)</p>
                        </div>
                    </div>
                    <a href="{{ route('produk.index') }}" class="text-sm text-red-700 hover:text-red-800 font-semibold flex items-center gap-1">
                        Lihat Produk
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
                @endif

                <!-- Info Card -->
                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl shadow-sm border border-indigo-200 p-6">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-indigo-500 rounded-lg flex items-center justify-center text-white flex-shrink-0">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-indigo-900 mb-1">Total Transaksi</h3>
                            <p class="text-2xl font-bold text-indigo-600">{{ $totalTransaksi }}</p>
                            <p class="text-sm text-indigo-700 mt-1">Transaksi keseluruhan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
