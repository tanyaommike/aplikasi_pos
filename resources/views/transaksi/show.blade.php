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

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto" x-data="editPaymentModal()">
        @if (session('success'))
            <div class="no-print mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-center font-semibold flex items-center justify-center gap-2">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="no-print mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-center font-semibold flex items-center justify-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Invoice Card -->
        <div id="invoice" class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
            <!-- Header: brand + no invoice -->
            <div class="bg-slate-800 text-white p-8">
                <div class="flex items-start justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-white/10 border border-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-store text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-xl font-bold tracking-wide">{{ config('store.name') }}</p>
                            <p class="text-slate-300 text-xs">{{ config('store.address') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-slate-300 text-xs uppercase tracking-widest mb-1">Invoice</p>
                        <p class="text-lg font-bold font-mono">{{ $transaksi->no_transaksi }}</p>
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
                        <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold mb-2">Tanggal & Waktu Transaksi</p>
                        <p class="font-bold text-slate-800">{{ $transaksi->tanggal_transaksi->translatedFormat('d F Y') }}</p>
                        <p class="text-sm text-slate-600">{{ $transaksi->tanggal_transaksi->format('H:i') }} WIB</p>
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
                                    <p class="text-sm text-slate-600">Scan kode QR untuk membayar</p>
                                </div>
                            @elseif($transaksi->payment_method === 'transfer')
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center text-white">
                                    <i class="fas fa-university text-xl"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800">Transfer Bank</p>
                                    <p class="text-sm text-slate-600">{{ config('store.bank.name') }} {{ config('store.bank.account_number') }} a.n. {{ config('store.bank.account_holder') }}</p>
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

                    @if($transaksi->payment_method === 'qris')
                        @php
                            $qrMatrix = qris_dummy_matrix();
                            $qrModules = count($qrMatrix);
                            $qrViewBox = $qrModules * 10;
                        @endphp
                        <div class="mt-4 flex flex-col items-center gap-2">
                            <div class="p-3 bg-white border border-slate-200 rounded-xl shadow-sm">
                                <svg viewBox="0 0 {{ $qrViewBox }} {{ $qrViewBox }}" width="180" height="180" class="block" role="img" aria-label="QR Code QRIS">
                                    <rect x="0" y="0" width="{{ $qrViewBox }}" height="{{ $qrViewBox }}" fill="#ffffff" />
                                    @foreach ($qrMatrix as $row => $cols)
                                        @foreach ($cols as $col => $filled)
                                            @if ($filled)
                                                <rect x="{{ $col * 10 }}" y="{{ $row * 10 }}" width="10" height="10" fill="#1e293b" />
                                            @endif
                                        @endforeach
                                    @endforeach
                                </svg>
                            </div>
                            <p class="text-xs font-semibold text-slate-500">Scan untuk membayar via QRIS</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Items Table -->
            <div class="p-8">
                <h4 class="font-bold text-lg text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-list text-indigo-600"></i>
                    Detail Produk
                </h4>
                <div class="overflow-x-auto">
                    <table class="w-full border border-slate-200 rounded-lg overflow-hidden">
                        <thead class="bg-slate-100 border-b-2 border-slate-300">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider border-r border-slate-200">Produk</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider border-r border-slate-200">Qty</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider border-r border-slate-200">Harga</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($transaksi->detail as $item)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-4 border-r border-slate-100">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">
                                                {{ strtoupper(substr($item->produk->nama_produk, 0, 1)) }}
                                            </div>
                                            <span class="font-semibold text-slate-800">{{ $item->produk->nama_produk }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center border-r border-slate-100">
                                        <span class="inline-flex items-center justify-center w-8 h-8 bg-indigo-100 text-indigo-700 rounded-lg font-bold">
                                            {{ $item->jumlah }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-right text-slate-700 border-r border-slate-100">{{ format_rupiah($item->harga_satuan) }}</td>
                                    <td class="px-4 py-4 text-right font-semibold text-slate-800">{{ format_rupiah($item->subtotal) }}</td>
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

            <!-- Footer -->
            <div class="px-8 py-6 border-t border-slate-200 text-center">
                <p class="text-sm font-semibold text-slate-700 mb-3">Terima kasih atas kunjungan Anda!</p>
                <a href="tel:{{ config('store.phone') }}" class="inline-flex items-center gap-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-sm font-semibold px-4 py-2 rounded-full transition-colors">
                    <i class="fas fa-phone-alt"></i>
                    {{ config('store.phone') }}
                </a>
            </div>

            <!-- Footer Actions -->
            <div class="no-print p-6 bg-white border-t border-slate-200 flex flex-col sm:flex-row gap-3 justify-center">
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
                <button type="button" @click="openModal()" class="inline-flex items-center justify-center gap-2 bg-slate-500 hover:bg-slate-600 text-white font-semibold py-3 px-6 rounded-xl transition-colors shadow-sm">
                    <i class="fas fa-edit"></i>
                    Ubah Pembayaran
                </button>
            </div>
        </div>

        <!-- Modal: Ubah Pembayaran -->
        <div x-show="open" x-cloak style="display:none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/50" @click="open = false"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6" @click.stop x-show="open" x-transition>
                <h3 class="text-lg font-bold text-slate-800 mb-5 flex items-center gap-2">
                    <i class="fas fa-edit text-indigo-600"></i>
                    Ubah Pembayaran
                </h3>

                <form action="{{ route('transaksi.updatePayment', $transaksi->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <label class="block text-sm font-semibold text-slate-700 mb-3">Metode Pembayaran</label>
                    <div class="grid grid-cols-3 gap-2 mb-5">
                        <label class="flex flex-col items-center gap-1.5 p-3 border-2 rounded-xl cursor-pointer transition-colors"
                            :class="method === 'cash' ? 'border-indigo-500 bg-indigo-50' : 'border-slate-200 hover:border-indigo-300'">
                            <input type="radio" name="payment_method" value="cash" class="hidden" x-model="method">
                            <i class="fas fa-money-bill-wave text-lg text-green-600"></i>
                            <span class="text-xs font-semibold text-slate-800">Cash</span>
                        </label>
                        <label class="flex flex-col items-center gap-1.5 p-3 border-2 rounded-xl cursor-pointer transition-colors"
                            :class="method === 'qris' ? 'border-indigo-500 bg-indigo-50' : 'border-slate-200 hover:border-indigo-300'">
                            <input type="radio" name="payment_method" value="qris" class="hidden" x-model="method">
                            <i class="fas fa-qrcode text-lg text-blue-600"></i>
                            <span class="text-xs font-semibold text-slate-800">QRIS</span>
                        </label>
                        <label class="flex flex-col items-center gap-1.5 p-3 border-2 rounded-xl cursor-pointer transition-colors"
                            :class="method === 'transfer' ? 'border-indigo-500 bg-indigo-50' : 'border-slate-200 hover:border-indigo-300'">
                            <input type="radio" name="payment_method" value="transfer" class="hidden" x-model="method">
                            <i class="fas fa-university text-lg text-purple-600"></i>
                            <span class="text-xs font-semibold text-slate-800">Transfer</span>
                        </label>
                    </div>

                    <div x-show="method === 'cash'" x-cloak>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Uang Dibayar</label>
                        <input type="number" name="uang_dibayar" x-model.number="uangDibayar" min="{{ $transaksi->total_harga }}"
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <p class="text-xs text-slate-400 mt-2">Total tagihan: {{ format_rupiah($transaksi->total_harga) }}</p>
                        <p class="text-sm font-semibold mt-2" :class="kembalian >= 0 ? 'text-emerald-600' : 'text-red-500'" x-show="uangDibayar">
                            Kembalian: <span x-text="'Rp ' + Math.max(0, kembalian).toLocaleString('id-ID')"></span>
                        </p>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="button" @click="open = false" class="flex-1 py-3 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 py-3 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold transition-all">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Print Styles -->
        <style>
            @media print {
                @page {
                    size: A4;
                    margin: 12mm;
                }
                body * {
                    visibility: hidden;
                }
                #invoice, #invoice * {
                    visibility: visible;
                }
                #invoice {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    box-shadow: none;
                    border: none;
                }
                .no-print {
                    display: none !important;
                }
            }
        </style>
    </div>

    <script>
        function editPaymentModal() {
            return {
                open: false,
                method: '{{ $transaksi->payment_method }}',
                uangDibayar: {{ $transaksi->uang_dibayar ?? $transaksi->total_harga }},
                get kembalian() {
                    return (this.uangDibayar || 0) - {{ $transaksi->total_harga }};
                },
                openModal() {
                    this.method = '{{ $transaksi->payment_method }}';
                    this.uangDibayar = {{ $transaksi->uang_dibayar ?? $transaksi->total_harga }};
                    this.open = true;
                },
            };
        }
    </script>
</x-app-layout>
