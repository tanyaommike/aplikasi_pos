<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-3">
                    <i class="fas fa-receipt text-green-600"></i>
                    Detail Transaksi
                </h2>
                <p class="text-sm text-slate-600 mt-1">{{ $transaksi->no_transaksi }}</p>
            </div>
            <a href="{{ route('transaksi.index') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-800 font-medium">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-center font-semibold flex items-center justify-center gap-2">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Invoice Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white p-8">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-3xl font-bold mb-2">INVOICE</h3>
                        <p class="text-indigo-100 text-lg">{{ $transaksi->no_transaksi }}</p>
                    </div>
                    <div class="text-right">
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <i class="fas fa-file-invoice text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Section -->
            <div class="p-8 border-b border-slate-200 bg-slate-50">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold mb-2">Informasi Kasir</p>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($transaksi->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-800">{{ $transaksi->user->name }}</p>
                                <p class="text-sm text-slate-600">{{ $transaksi->user->email }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-left md:text-right">
                        <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold mb-2">Tanggal Transaksi</p>
                        <p class="font-bold text-slate-800">{{ $transaksi->tanggal_transaksi->format('d F Y') }}</p>
                        <p class="text-sm text-slate-600">{{ $transaksi->tanggal_transaksi->format('H:i:s') }} WIB</p>
                        <p class="text-xs text-slate-500 mt-1">{{ $transaksi->tanggal_transaksi->diffForHumans() }}</p>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="mt-6 pt-6 border-t border-slate-200">
                    <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold mb-3">Metode Pembayaran</p>
                    <div class="flex items-center justify-between flex-wrap gap-3">
                        <div class="flex items-center gap-3">
                            @if($transaksi->payment_method === 'cash')
                                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center text-white">
                                    <i class="fas fa-money-bill-wave text-xl"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800">Cash (Tunai)</p>
                                    @if($transaksi->uang_dibayar)
                                        <p class="text-sm text-slate-600">Dibayar: {{ format_rupiah($transaksi->uang_dibayar) }}</p>
                                        @if($transaksi->kembalian > 0)
                                            <p class="text-sm text-emerald-600 font-semibold">Kembalian: {{ format_rupiah($transaksi->kembalian) }}</p>
                                        @endif
                                    @endif
                                </div>
                            @elseif($transaksi->payment_method === 'qris')
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white">
                                    <i class="fas fa-qrcode text-xl"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800">QRIS</p>
                                </div>
                            @elseif($transaksi->payment_method === 'debit')
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center text-white">
                                    <i class="fas fa-credit-card text-xl"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800">Debit Card</p>
                                </div>
                            @elseif($transaksi->payment_method === 'credit')
                                <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center text-white">
                                    <i class="fas fa-credit-card text-xl"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800">Credit Card</p>
                                </div>
                            @endif
                        </div>

                        @if($transaksi->payment_status === 'paid')
                            <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 px-4 py-2 rounded-full text-sm font-semibold">
                                <i class="fas fa-check-circle"></i> Lunas
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 px-4 py-2 rounded-full text-sm font-semibold">
                                <i class="fas fa-clock"></i> Menunggu Pembayaran
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="p-8">
                <h4 class="font-bold text-lg text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-list text-indigo-600"></i>
                    Detail Produk
                </h4>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b-2 border-slate-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Produk</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Qty</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Harga</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Subtotal</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Stok Sisa</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($transaksi->detail as $item)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">
                                                {{ strtoupper(substr($item->produk->nama_produk, 0, 1)) }}
                                            </div>
                                            <span class="font-semibold text-slate-800">{{ $item->produk->nama_produk }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="inline-flex items-center justify-center w-8 h-8 bg-indigo-100 text-indigo-700 rounded-lg font-bold">
                                            {{ $item->jumlah }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-right text-slate-700">{{ format_rupiah($item->harga_satuan) }}</td>
                                    <td class="px-4 py-4 text-right font-semibold text-slate-800">{{ format_rupiah($item->subtotal) }}</td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="inline-flex items-center gap-1 {{ $item->produk->stok <= 5 ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700' }} px-3 py-1 rounded-full text-xs font-semibold">
                                            <i class="fas fa-box"></i>
                                            {{ $item->produk->stok }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Total Section -->
            <div class="p-8 bg-slate-50 border-t border-slate-200">
                <div class="flex items-center justify-between max-w-md ml-auto">
                    <div class="text-lg text-slate-700">
                        <p class="font-semibold">Jumlah Item:</p>
                        <p class="text-sm text-slate-600">{{ $transaksi->detail->sum('jumlah') }} produk</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-slate-600 uppercase tracking-wider font-semibold mb-1">Total Pembayaran</p>
                        <p class="text-3xl font-bold text-green-600">{{ format_rupiah($transaksi->total_harga) }}</p>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="p-6 bg-white border-t border-slate-200 flex flex-col sm:flex-row gap-3 justify-center">
                @if($transaksi->payment_status === 'pending')
                    <form action="{{ route('transaksi.confirmPayment', $transaksi->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-xl transition-colors shadow-sm">
                            <i class="fas fa-check-circle"></i>
                            Pembayaran Selesai
                        </button>
                    </form>
                @endif

                <button onclick="window.print()" class="inline-flex items-center justify-center gap-2 bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-3 px-6 rounded-xl transition-colors shadow-sm">
                    <i class="fas fa-print"></i>
                    Cetak Struk
                </button>
                <a href="{{ route('transaksi.create') }}" class="inline-flex items-center justify-center gap-2 bg-white hover:bg-slate-50 text-slate-700 font-semibold py-3 px-6 rounded-xl transition-colors shadow-sm border border-slate-200">
                    <i class="fas fa-plus-circle"></i>
                    Transaksi Baru
                </a>
                <a href="{{ route('transaksi.index') }}" class="inline-flex items-center justify-center gap-2 bg-slate-500 hover:bg-slate-600 text-white font-semibold py-3 px-6 rounded-xl transition-colors shadow-sm">
                    <i class="fas fa-list"></i>
                    Lihat Riwayat
                </a>
            </div>
        </div>

        <!-- Print Styles -->
        <style>
            @media print {
                body * {
                    visibility: hidden;
                }
                .bg-white.rounded-2xl, .bg-white.rounded-2xl * {
                    visibility: visible;
                }
                .bg-white.rounded-2xl {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    box-shadow: none;
                    border: none;
                }
                button, a, form {
                    display: none !important;
                }
            }
        </style>
    </div>
</x-app-layout>
