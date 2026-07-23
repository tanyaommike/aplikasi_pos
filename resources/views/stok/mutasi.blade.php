<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-3">
                <i class="fas fa-exchange-alt text-indigo-600"></i>
                Kelola Stok
            </h2>
            <p class="text-sm text-slate-600 mt-1">{{ $produk->nama_produk }}</p>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-3xl mx-auto">
        @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Info Produk -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6 flex items-center gap-4">
            <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0">
                @if ($produk->foto)
                    <img src="{{ asset('storage/' . $produk->foto) }}" alt="{{ $produk->nama_produk }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-slate-400">
                        <i class="fas fa-image text-2xl"></i>
                    </div>
                @endif
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-slate-800 text-lg">{{ $produk->nama_produk }}</h3>
                <p class="text-sm text-slate-500">{{ $produk->kategori->nama_kategori }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-slate-500 uppercase tracking-wider">Stok Saat Ini</p>
                <p class="text-3xl font-bold text-indigo-600">{{ $produk->stok }}</p>
            </div>
        </div>

        <!-- Form Mutasi -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6" x-data="{ tipe: 'masuk' }">
            <form action="{{ route('stok.mutasi', $produk->id) }}" method="POST">
                @csrf

                <label class="block text-sm font-semibold text-slate-700 mb-3">Jenis Mutasi</label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
                    <label class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-colors"
                        :class="tipe === 'masuk' ? 'border-green-500 bg-green-50' : 'border-slate-200 hover:border-slate-300'">
                        <input type="radio" name="tipe" value="masuk" x-model="tipe" class="w-4 h-4 text-green-600" checked>
                        <div>
                            <p class="font-semibold text-slate-800 text-sm">Stok Masuk</p>
                            <p class="text-xs text-slate-500">Restock / pembelian</p>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-colors"
                        :class="tipe === 'keluar' ? 'border-red-500 bg-red-50' : 'border-slate-200 hover:border-slate-300'">
                        <input type="radio" name="tipe" value="keluar" x-model="tipe" class="w-4 h-4 text-red-600">
                        <div>
                            <p class="font-semibold text-slate-800 text-sm">Stok Keluar</p>
                            <p class="text-xs text-slate-500">Rusak / hilang / retur</p>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-colors"
                        :class="tipe === 'penyesuaian' ? 'border-indigo-500 bg-indigo-50' : 'border-slate-200 hover:border-slate-300'">
                        <input type="radio" name="tipe" value="penyesuaian" x-model="tipe" class="w-4 h-4 text-indigo-600">
                        <div>
                            <p class="font-semibold text-slate-800 text-sm">Penyesuaian</p>
                            <p class="text-xs text-slate-500">Koreksi stok fisik</p>
                        </div>
                    </label>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <span x-show="tipe !== 'penyesuaian'">Jumlah</span>
                        <span x-show="tipe === 'penyesuaian'">Stok Akhir (hasil hitung fisik)</span>
                    </label>
                    <input type="number" name="jumlah" min="0" required
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                        placeholder="0">
                    <p class="text-xs text-slate-400 mt-1" x-show="tipe === 'penyesuaian'">
                        Masukkan jumlah stok fisik yang sebenarnya, sistem akan menghitung selisihnya otomatis.
                    </p>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Keterangan (opsional)</label>
                    <textarea name="keterangan" rows="2"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                        placeholder="Contoh: Restock dari supplier ABC">{{ old('keterangan') }}</textarea>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-xl transition-all shadow-md hover:shadow-lg">
                        <i class="fas fa-save"></i>
                        Simpan Mutasi
                    </button>
                    <a href="{{ route('stok.index') }}" class="flex-1 inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-3 px-6 rounded-xl transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        <!-- Riwayat Terakhir -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fas fa-history text-indigo-600"></i>
                Riwayat Terakhir
            </h3>
            @if($riwayatTerakhir->count() > 0)
                <div class="space-y-3">
                    @foreach ($riwayatTerakhir as $riwayat)
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                            <div class="flex items-center gap-3">
                                @php
                                    $tipeStyle = match($riwayat->tipe) {
                                        'masuk' => ['bg-green-100', 'text-green-700', 'fa-arrow-down'],
                                        'keluar' => ['bg-red-100', 'text-red-700', 'fa-arrow-up'],
                                        default => ['bg-indigo-100', 'text-indigo-700', 'fa-sliders-h'],
                                    };
                                @endphp
                                <div class="w-9 h-9 {{ $tipeStyle[0] }} {{ $tipeStyle[1] }} rounded-lg flex items-center justify-center">
                                    <i class="fas {{ $tipeStyle[2] }}"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800 capitalize">{{ $riwayat->tipe }}
                                        <span class="text-slate-500 font-normal">({{ $riwayat->stok_sebelum }} &rarr; {{ $riwayat->stok_sesudah }})</span>
                                    </p>
                                    <p class="text-xs text-slate-500">{{ $riwayat->user->name }} &middot; {{ $riwayat->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-slate-400 text-center py-6">Belum ada riwayat mutasi untuk produk ini</p>
            @endif
        </div>
    </div>
</x-app-layout>
