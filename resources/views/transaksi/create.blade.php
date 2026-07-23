<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-3">
                <i class="fas fa-cash-register text-green-600"></i>
                Kasir - Transaksi Baru
            </h2>
            <p class="text-sm text-slate-600 mt-1">Pilih produk dan selesaikan transaksi</p>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom kiri: Pilih Produk (2 kolom) -->
            <div class="lg:col-span-2 space-y-6">
                @if (session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-3">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                        <div class="flex items-center gap-3 mb-2">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                            <span class="font-semibold">Terdapat kesalahan:</span>
                        </div>
                        <ul class="list-disc list-inside space-y-1 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Available Products Grid -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-box-open text-blue-600"></i>
                        Pilih Produk
                    </h3>

                    <div class="relative mb-4">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" id="searchProduk" placeholder="Cari produk..." autocomplete="off"
                            class="w-full pl-11 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    </div>

                    <div id="productGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 max-h-[28rem] overflow-y-auto">
                        @foreach ($produk as $item)
                            @php $qtyDiCart = $cart[$item->id]['jumlah'] ?? 0; @endphp
                            <button type="button"
                                data-produk-card
                                data-produk-id="{{ $item->id }}"
                                data-nama-produk="{{ $item->nama_produk }}"
                                data-stok="{{ $item->stok }}"
                                {{ $item->stok <= 0 ? 'disabled' : '' }}
                                class="relative p-3 border rounded-xl transition-all group text-left
                                    {{ $item->stok <= 0
                                        ? 'border-slate-200 bg-slate-50 opacity-50 cursor-not-allowed'
                                        : 'border-slate-200 hover:border-indigo-500 hover:bg-indigo-50 hover:shadow-md cursor-pointer' }}">

                                @if ($qtyDiCart > 0)
                                    <span class="absolute -top-2 -right-2 z-10 bg-indigo-600 text-white text-xs font-bold w-6 h-6 rounded-full flex items-center justify-center shadow-md" data-cart-badge>
                                        {{ $qtyDiCart }}
                                    </span>
                                @endif

                                <div class="aspect-square bg-slate-100 rounded-lg mb-2 overflow-hidden relative">
                                    @if($item->foto)
                                        <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_produk }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="fas fa-image text-slate-300 text-2xl"></i>
                                        </div>
                                    @endif

                                    @if ($item->stok > 0)
                                        <span class="absolute bottom-1.5 right-1.5 w-7 h-7 rounded-full bg-indigo-600 text-white flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition-opacity">
                                            <i class="fas fa-plus text-xs"></i>
                                        </span>
                                    @else
                                        <span class="absolute inset-0 flex items-center justify-center bg-white/60">
                                            <span class="text-[10px] font-bold text-slate-500 bg-white px-2 py-1 rounded-full border border-slate-200">Stok Habis</span>
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs font-semibold text-slate-800 truncate">{{ $item->nama_produk }}</p>
                                <p class="text-xs text-indigo-600 font-bold">{{ format_rupiah($item->harga) }}</p>
                                <p class="text-xs text-slate-500">Stok: {{ $item->stok }}</p>
                            </button>
                        @endforeach
                    </div>

                    <p id="noProductFound" class="hidden text-center text-sm text-slate-400 py-8">Produk tidak ditemukan</p>
                </div>
            </div>

            <!-- Kolom kanan: Keranjang (1 kolom) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sticky top-24">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-shopping-cart text-green-600"></i>
                        Keranjang
                        <span class="ml-auto text-sm font-normal bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full">{{ count($cart) }} item</span>
                    </h3>

                    @if (count($cart) > 0)
                        <div class="space-y-3 mb-6 max-h-64 overflow-y-auto">
                            @php $total = 0; @endphp
                            @foreach ($cart as $item)
                                @php $total += $item['subtotal']; @endphp
                                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-slate-800">{{ $item['nama_produk'] }}</p>
                                            <p class="text-xs text-slate-500">{{ $item['jumlah'] }} x {{ format_rupiah($item['harga_satuan']) }}</p>
                                        </div>
                                        <form action="{{ route('transaksi.removeFromCart', $item['produk_id']) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm ml-2">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <p class="text-sm font-bold text-indigo-600">{{ format_rupiah($item['subtotal']) }}</p>
                                </div>
                            @endforeach
                        </div>

                        <!-- Payment Method Selection -->
                        <form action="{{ route('transaksi.store') }}" method="POST" id="checkoutForm">
                            @csrf
                            
                            <!-- Total -->
                            <div class="border-t border-slate-200 pt-4 mb-4">
                                <div class="flex items-center justify-between text-lg font-bold">
                                    <span class="text-slate-800">Total</span>
                                    <span class="text-indigo-600" id="totalAmount">{{ format_rupiah($total) }}</span>
                                    <input type="hidden" id="totalValue" value="{{ $total }}">
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">Metode Pembayaran</label>
                                <div class="space-y-2">
                                    <label class="flex items-center p-3 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-indigo-500 transition-colors payment-option">
                                        <input type="radio" name="payment_method" value="cash" class="w-4 h-4 text-indigo-600" checked>
                                        <div class="ml-3 flex items-center gap-3">
                                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center text-white">
                                                <i class="fas fa-money-bill-wave"></i>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-slate-800">Cash</p>
                                                <p class="text-xs text-slate-500">Tunai</p>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="flex items-center p-3 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-indigo-500 transition-colors payment-option">
                                        <input type="radio" name="payment_method" value="qris" class="w-4 h-4 text-indigo-600">
                                        <div class="ml-3 flex items-center gap-3">
                                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white">
                                                <i class="fas fa-qrcode"></i>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-slate-800">QRIS</p>
                                                <p class="text-xs text-slate-500">Scan QR Code</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Cash Payment Details -->
                            <div id="cashDetails" class="mb-4">
                                <label for="uang_dibayar" class="block text-sm font-semibold text-slate-700 mb-2">Uang Dibayar</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-500 font-semibold">Rp</span>
                                    <input type="number" name="uang_dibayar" id="uang_dibayar" class="w-full pl-12 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" placeholder="0" min="{{ $total }}" step="1000">
                                </div>
                                
                                <div id="kembalianDisplay" class="mt-3 p-3 bg-emerald-50 border border-emerald-200 rounded-xl hidden">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-semibold text-emerald-800">Kembalian</span>
                                        <span class="text-lg font-bold text-emerald-600" id="kembalianAmount">Rp 0</span>
                                    </div>
                                </div>
                            </div>

                            <!-- QRIS Display -->
                            <div id="qrisDetails" class="mb-4 hidden">
                                <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl text-center">
                                    <p class="text-sm font-semibold text-blue-800 mb-3">Scan QR Code untuk bayar</p>
                                    <div class="bg-white p-4 rounded-lg inline-block">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=00020101021126660014ID.LINKAJA.WWW01189360050300000898740214541234567890000303UMI51440014ID.OR.GPNQR02140123456789012340303UMI5204123453033605404{{ $total }}5802ID5915NAMA_MERCHANT_ID6007Jakarta61051234062460122{{ date('YmdHis') }}2014TRX123456789630450F4" alt="QRIS" class="w-40 h-40">
                                    </div>
                                    <p class="text-xs text-blue-600 mt-3">Total: <span class="font-bold">{{ format_rupiah($total) }}</span></p>
                                    <p class="text-xs text-slate-500 mt-2">Setelah pembayaran berhasil, klik tombol checkout</p>
                                </div>
                            </div>

                            <!-- Checkout Button -->
                            <button type="submit" id="checkoutBtn" class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold py-4 px-6 rounded-xl transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                                <i class="fas fa-check-circle"></i>
                                Selesaikan Transaksi
                            </button>
                        </form>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-shopping-cart text-slate-300 text-5xl mb-3"></i>
                            <p class="text-slate-500 font-medium">Keranjang kosong</p>
                            <p class="text-sm text-slate-400 mt-1">Tambahkan produk ke keranjang</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        function showToast(message, isError = false) {
            const toast = document.createElement('div');
            toast.className = `fixed top-20 right-6 z-50 ${isError ? 'bg-red-500' : 'bg-green-500'} text-white px-5 py-3 rounded-xl shadow-lg flex items-center gap-2 text-sm font-medium transition-all duration-300 opacity-0 translate-x-4`;
            toast.innerHTML = `<i class="fas ${isError ? 'fa-exclamation-circle' : 'fa-check-circle'}"></i><span>${message}</span>`;
            document.body.appendChild(toast);

            requestAnimationFrame(() => {
                toast.classList.remove('opacity-0', 'translate-x-4');
            });

            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-x-4');
                setTimeout(() => toast.remove(), 300);
            }, 1800);
        }

        // Tambah produk ke keranjang lewat klik kartu produk
        document.querySelectorAll('[data-produk-card]').forEach((card) => {
            card.addEventListener('click', async () => {
                if (card.disabled) return;

                const produkId = card.dataset.produkId;
                const namaProduk = card.dataset.namaProduk;

                card.disabled = true;
                card.classList.add('ring-2', 'ring-indigo-400', 'scale-95');

                try {
                    const formData = new FormData();
                    formData.append('produk_id', produkId);
                    formData.append('jumlah', 1);

                    const response = await fetch('{{ route("transaksi.addToCart") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });

                    const data = await response.json();

                    if (response.ok) {
                        sessionStorage.setItem('cartToast', `${namaProduk} ditambahkan ke keranjang`);
                        setTimeout(() => location.reload(), 350);
                    } else {
                        card.disabled = false;
                        card.classList.remove('ring-2', 'ring-indigo-400', 'scale-95');
                        showToast(data.message || 'Gagal tambah item', true);
                    }
                } catch (err) {
                    card.disabled = false;
                    card.classList.remove('ring-2', 'ring-indigo-400', 'scale-95');
                    showToast('Terjadi kesalahan, coba lagi', true);
                }
            });
        });

        // Tampilkan toast konfirmasi setelah reload
        document.addEventListener('DOMContentLoaded', () => {
            const pendingToast = sessionStorage.getItem('cartToast');
            if (pendingToast) {
                showToast(pendingToast);
                sessionStorage.removeItem('cartToast');
            }
        });

        // Search produk
        const searchInput = document.getElementById('searchProduk');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const query = this.value.toLowerCase().trim();
                let visibleCount = 0;

                document.querySelectorAll('[data-produk-card]').forEach((card) => {
                    const match = card.dataset.namaProduk.toLowerCase().includes(query);
                    card.style.display = match ? '' : 'none';
                    if (match) visibleCount++;
                });

                document.getElementById('noProductFound').classList.toggle('hidden', visibleCount > 0);
            });
        }

        // Payment method toggle
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const cashDetails = document.getElementById('cashDetails');
                const qrisDetails = document.getElementById('qrisDetails');
                const uangDibayar = document.getElementById('uang_dibayar');
                
                if (this.value === 'cash') {
                    cashDetails.classList.remove('hidden');
                    qrisDetails.classList.add('hidden');
                    uangDibayar.required = true;
                } else {
                    cashDetails.classList.add('hidden');
                    qrisDetails.classList.remove('hidden');
                    uangDibayar.required = false;
                    uangDibayar.value = '';
                }

                // Update active border
                document.querySelectorAll('.payment-option').forEach(option => {
                    option.classList.remove('border-indigo-500', 'bg-indigo-50');
                    option.classList.add('border-slate-200');
                });
                this.closest('.payment-option').classList.remove('border-slate-200');
                this.closest('.payment-option').classList.add('border-indigo-500', 'bg-indigo-50');
            });
        });

        // Calculate kembalian
        const uangDibayarInput = document.getElementById('uang_dibayar');
        const kembalianDisplay = document.getElementById('kembalianDisplay');
        const kembalianAmount = document.getElementById('kembalianAmount');
        const totalValue = parseFloat(document.getElementById('totalValue').value);

        if (uangDibayarInput) {
            uangDibayarInput.addEventListener('input', function() {
                const uangDibayar = parseFloat(this.value) || 0;
                const kembalian = uangDibayar - totalValue;

                if (uangDibayar >= totalValue) {
                    kembalianDisplay.classList.remove('hidden');
                    kembalianAmount.textContent = 'Rp ' + kembalian.toLocaleString('id-ID');
                } else {
                    kembalianDisplay.classList.add('hidden');
                }
            });
        }

        // Set initial active payment
        document.querySelector('input[name="payment_method"]:checked').closest('.payment-option').classList.add('border-indigo-500', 'bg-indigo-50');
    </script>
</x-app-layout>
