<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-3">
                    <i class="fas fa-history text-indigo-600"></i>
                    Riwayat Mutasi Stok
                </h2>
                <p class="text-sm text-slate-600 mt-1">Semua pergerakan stok tercatat di sini</p>
            </div>
            <a href="{{ route('stok.index') }}" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white font-semibold py-3 px-6 rounded-xl transition-colors shadow-sm">
                <i class="fas fa-warehouse"></i>
                Kembali ke Stok
            </a>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <!-- Filter -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
            <form method="GET" action="{{ route('stok.riwayat') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <select name="produk_id" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <option value="">Semua Produk</option>
                        @foreach ($produkList as $p)
                            <option value="{{ $p->id }}" {{ request('produk_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_produk }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:w-56">
                    <select name="tipe" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <option value="">Semua Jenis</option>
                        <option value="masuk" {{ request('tipe') === 'masuk' ? 'selected' : '' }}>Stok Masuk</option>
                        <option value="keluar" {{ request('tipe') === 'keluar' ? 'selected' : '' }}>Stok Keluar</option>
                        <option value="penyesuaian" {{ request('tipe') === 'penyesuaian' ? 'selected' : '' }}>Penyesuaian</option>
                    </select>
                </div>
                <button type="submit" class="inline-flex items-center justify-center gap-2 bg-slate-800 hover:bg-slate-900 text-white px-6 py-3 rounded-xl font-medium transition-colors shadow-sm">
                    <i class="fas fa-filter"></i>
                    Filter
                </button>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Waktu</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Produk</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Perubahan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Oleh</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($riwayat as $item)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-slate-600 whitespace-nowrap">{{ $item->created_at->format('d M Y, H:i') }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-800">{{ $item->produk->nama_produk ?? 'Produk Dihapus' }}</td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $tipeStyle = match($item->tipe) {
                                            'masuk' => ['bg-green-50', 'text-green-700', 'fa-arrow-down'],
                                            'keluar' => ['bg-red-50', 'text-red-700', 'fa-arrow-up'],
                                            default => ['bg-indigo-50', 'text-indigo-700', 'fa-sliders-h'],
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1 {{ $tipeStyle[0] }} {{ $tipeStyle[1] }} px-3 py-1 rounded-full text-xs font-semibold capitalize">
                                        <i class="fas {{ $tipeStyle[2] }}"></i>
                                        {{ $item->tipe }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-slate-700">
                                    {{ $item->stok_sebelum }} <i class="fas fa-arrow-right text-slate-300 text-xs mx-1"></i> {{ $item->stok_sesudah }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $item->user->name }}</td>
                                <td class="px-6 py-4 text-sm text-slate-500">{{ $item->keterangan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-inbox text-slate-300 text-5xl mb-3"></i>
                                        <p class="text-slate-500 font-medium">Belum ada riwayat mutasi stok</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($riwayat->hasPages())
        <div class="mt-6">
            {{ $riwayat->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
