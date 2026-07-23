<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-3">
                    <i class="fas fa-warehouse text-indigo-600"></i>
                    Manajemen Stok
                </h2>
                <p class="text-sm text-slate-600 mt-1">Pantau dan kelola stok produk Anda</p>
            </div>
            <a href="{{ route('stok.riwayat') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-xl transition-all shadow-md hover:shadow-lg">
                <i class="fas fa-history"></i>
                Riwayat Mutasi
            </a>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
            <i class="fas fa-check-circle text-green-500"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif
        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
            <i class="fas fa-exclamation-circle text-red-500"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 border border-slate-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-box text-white text-xl"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-slate-800 mb-1">{{ $totalProduk }}</h3>
                <p class="text-sm text-slate-600">Total Produk</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 border border-slate-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-slate-800 mb-1">{{ $totalStokRendah }}</h3>
                <p class="text-sm text-slate-600">Stok Rendah (&le; 5)</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 border border-slate-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-times-circle text-white text-xl"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-slate-800 mb-1">{{ $totalStokHabis }}</h3>
                <p class="text-sm text-slate-600">Stok Habis</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 border border-slate-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-coins text-white text-xl"></i>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-1">{{ format_rupiah($totalNilaiStok) }}</h3>
                <p class="text-sm text-slate-600">Total Nilai Stok</p>
            </div>
        </div>

        <!-- Search & Filter -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
            <form method="GET" action="{{ route('stok.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <div class="relative">
                        <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama produk..."
                            class="w-full pl-11 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    </div>
                </div>

                <div class="sm:w-56">
                    <select name="kategori_id" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategori as $kat)
                            <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:w-48">
                    <select name="status" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <option value="">Semua Status</option>
                        <option value="aman" {{ request('status') === 'aman' ? 'selected' : '' }}>Stok Aman</option>
                        <option value="rendah" {{ request('status') === 'rendah' ? 'selected' : '' }}>Stok Rendah</option>
                        <option value="habis" {{ request('status') === 'habis' ? 'selected' : '' }}>Stok Habis</option>
                    </select>
                </div>

                <button type="submit" class="inline-flex items-center justify-center gap-2 bg-slate-800 hover:bg-slate-900 text-white px-6 py-3 rounded-xl font-medium transition-colors shadow-sm">
                    <i class="fas fa-filter"></i>
                    Filter
                </button>
            </form>
        </div>

        <!-- Produk Grid -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Produk</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Stok</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Nilai Stok</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($produk as $item)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg overflow-hidden bg-slate-100 flex-shrink-0">
                                            @if ($item->foto)
                                                <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_produk }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <span class="text-sm font-semibold text-slate-800">{{ $item->nama_produk }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $item->kategori->nama_kategori }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-lg font-bold text-slate-800">{{ $item->stok }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($item->stok == 0)
                                        <span class="inline-flex items-center gap-1 bg-red-50 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                                            <i class="fas fa-times-circle"></i> Habis
                                        </span>
                                    @elseif($item->stok <= 5)
                                        <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 px-3 py-1 rounded-full text-xs font-semibold">
                                            <i class="fas fa-exclamation-triangle"></i> Rendah
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                            <i class="fas fa-check-circle"></i> Aman
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-semibold text-slate-700">
                                    {{ format_rupiah($item->stok * $item->harga) }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('stok.mutasiForm', $item->id) }}" class="inline-flex items-center gap-1 bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
                                        <i class="fas fa-exchange-alt"></i>
                                        Kelola Stok
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-inbox text-slate-300 text-5xl mb-3"></i>
                                        <p class="text-slate-500 font-medium">Tidak ada produk ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($produk->hasPages())
        <div class="mt-6">
            {{ $produk->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
