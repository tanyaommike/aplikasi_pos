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
                <!-- Product Selection -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-shopping-basket text-indigo-600"></i>
                        Pilih Produk
                    </h3>

                    <form id="formAddToCart">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="produk_id" class="block text-sm font-semibold text-slate-700 mb-2">Produk</label>
                                <select id="produk_id" name="produk_id" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" required>
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach ($produk as $item)
                                        <option value="{{ $item->id }}" data-harga="{{ $item->harga }}">
                                            {{ $item->nama_produk }} - {{ format_rupiah($item->harga) }} (Stok: {{ $item->stok }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="jumlah" class="block text-sm font-semibold text-slate-700 mb-2">Jumlah</label>
                                <input type="number" id="jumlah" name="jumlah" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" value="1" min="1" required>
                            </div>

                            <button type="submit" class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold py-3 px-6 rounded-xl transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                                <i class="fas fa-plus-circle"></i>
                                Tambah ke Keranjang
                            </button>
                        </div>
                    </form>
                    
                    @if (session('error'))
                        <div class="mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-3">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
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
                </div>

                <!-- Available Products Grid -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-box-open text-blue-600"></i>
                        Produk Tersedia
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 max-h-96 overflow-y-auto">
                        @foreach ($produk as $item)
                            <button type="button" onclick="selectProduct({{ $item->id }})" class="p-3 border border-slate-200 rounded-xl hover:border-indigo-500 hover:bg-indigo-50 transition-all group text-left">
                                <div class="aspect-square bg-slate-100 rounded-lg mb-2 overflow-hidden">
                                    @if($item->foto)
                                        <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_produk }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="fas fa-image text-slate-300 text-2xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <p class="text-xs font-semibold text-slate-800 truncate">{{ $item->nama_produk }}</p>
                                <p class="text-xs text-indigo-600 font-bold">{{ format_rupiah($item->harga) }}</p>
                                <p class="text-xs text-slate-500">Stok: {{ $item->stok }}</p>
                            </button>
                        @endforeach
                    </div>
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
        function selectProduct(productId) {
            document.getElementById('produk_id').value = productId;
            document.getElementById('jumlah').focus();
        }

        // Add to cart AJAX
        document.getElementById('formAddToCart').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(e.target);
            const response = await fetch('{{ route("transaksi.addToCart") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                },
                body: formData,
            });

            const data = await response.json();

            if (response.ok) {
                location.reload();
            } else {
                alert(data.message || 'Gagal tambah item');
            }
        });

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
