<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-3">
                    <i class="fas fa-box text-blue-600"></i>
                    Daftar Produk
                </h2>
                <p class="text-sm text-slate-600 mt-1">Kelola produk dan inventory Anda</p>
            </div>
            <a href="{{ route('produk.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-xl transition-all shadow-md hover:shadow-lg">
                <i class="fas fa-plus-circle"></i>
                Tambah Produk
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

        <!-- Search & Filter -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
            <form method="GET" action="{{ route('produk.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <div class="relative">
                        <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Cari nama produk..." 
                            class="w-full pl-11 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    </div>
                </div>

                <div class="sm:w-64">
                    <select name="kategori_id" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategori as $kat)
                            <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="inline-flex items-center justify-center gap-2 bg-slate-800 hover:bg-slate-900 text-white px-6 py-3 rounded-xl font-medium transition-colors shadow-sm">
                    <i class="fas fa-search"></i>
                    Cari
                </button>
            </form>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse ($produk as $item)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-all group">
                    <!-- Product Image -->
                    <div class="relative aspect-square bg-slate-100 overflow-hidden">
                        @if ($item->foto)
                            <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_produk }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200">
                                <i class="fas fa-image text-slate-400 text-5xl"></i>
                            </div>
                        @endif
                        
                        <!-- Stock Badge -->
                        @if($item->stok <= 5)
                            <div class="absolute top-3 right-3 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                Stok Rendah!
                            </div>
                        @else
                            <div class="absolute top-3 right-3 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                Tersedia
                            </div>
                        @endif
                    </div>

                    <!-- Product Info -->
                    <div class="p-5">
                        <!-- Category Badge -->
                        <div class="flex items-center gap-2 mb-3">
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-purple-700 bg-purple-50 px-2 py-1 rounded-lg">
                                <i class="fas fa-tag"></i>
                                {{ $item->kategori->nama_kategori }}
                            </span>
                        </div>

                        <!-- Product Name -->
                        <h3 class="font-bold text-slate-800 text-lg mb-2 line-clamp-2">{{ $item->nama_produk }}</h3>

                        <!-- Price & Stock -->
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-xs text-slate-500">Harga</p>
                                <p class="text-xl font-bold text-indigo-600">{{ format_rupiah($item->harga) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-slate-500">Stok</p>
                                <p class="text-xl font-bold text-slate-800">{{ $item->stok }}</p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2">
                            <a href="{{ route('stok.mutasiForm', $item->id) }}" title="Kelola Stok" class="inline-flex items-center justify-center gap-2 bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                <i class="fas fa-exchange-alt"></i>
                            </a>
                            <a href="{{ route('produk.edit', $item->id) }}" class="flex-1 inline-flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                <i class="fas fa-edit"></i>
                                Edit
                            </a>
                            <form action="{{ route('produk.destroy', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus produk ini?');" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    <i class="fas fa-trash"></i>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center">
                        <i class="fas fa-inbox text-slate-300 text-6xl mb-4"></i>
                        <p class="text-slate-500 font-medium text-lg">Tidak ada produk</p>
                        <p class="text-sm text-slate-400 mt-2">Tambahkan produk pertama Anda</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($produk->hasPages())
        <div class="mt-8">
            {{ $produk->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
