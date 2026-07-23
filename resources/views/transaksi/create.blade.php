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

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto" x-data="cartQtyModal()">
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
                                data-harga="{{ $item->harga }}"
                                data-stok="{{ $item->stok }}"
                                data-foto="{{ $item->foto }}"
                                @click="openQtyModal($event.currentTarget)"
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
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">Metode Pembayaran</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <label class="flex items-center gap-2 p-3 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-indigo-500 transition-colors payment-option">
                                        <input type="radio" name="payment_method" value="cash" class="w-4 h-4 text-indigo-600" checked>
                                        <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center text-white flex-shrink-0">
                                            <i class="fas fa-money-bill-wave text-xs"></i>
                                        </div>
                                        <span class="text-sm font-semibold text-slate-800">Cash</span>
                                    </label>

                                    <label class="flex items-center gap-2 p-3 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-indigo-500 transition-colors payment-option">
                                        <input type="radio" name="payment_method" value="qris" class="w-4 h-4 text-indigo-600">
                                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white flex-shrink-0">
                                            <i class="fas fa-qrcode text-xs"></i>
                                        </div>
                                        <span class="text-sm font-semibold text-slate-800">QRIS</span>
                                    </label>

                                    <label class="flex items-center gap-2 p-3 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-indigo-500 transition-colors payment-option">
                                        <input type="radio" name="payment_method" value="debit" class="w-4 h-4 text-indigo-600">
                                        <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center text-white flex-shrink-0">
                                            <i class="fas fa-credit-card text-xs"></i>
                                        </div>
                                        <span class="text-sm font-semibold text-slate-800">Debit</span>
                                    </label>

                                    <label class="flex items-center gap-2 p-3 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-indigo-500 transition-colors payment-option">
                                        <input type="radio" name="payment_method" value="credit" class="w-4 h-4 text-indigo-600">
                                        <div class="w-8 h-8 bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg flex items-center justify-center text-white flex-shrink-0">
                                            <i class="fas fa-credit-card text-xs"></i>
                                        </div>
                                        <span class="text-sm font-semibold text-slate-800">Kredit</span>
                                    </label>
                                </div>
                                <p class="text-xs text-slate-400 mt-3 flex items-center gap-1.5">
                                    <i class="fas fa-info-circle"></i>
                                    Konfirmasi pembayaran dilakukan manual di halaman struk setelah transaksi dibuat.
                                </p>
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

        <!-- Modal: input jumlah saat tambah produk -->
        <div x-show="open" x-cloak style="display:none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/50" @click="open = false"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6" @click.stop x-show="open" x-transition>
                <div class="flex items-center gap-4 mb-5">
                    <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0">
                        <template x-if="foto"><img :src="'/storage/' + foto" class="w-full h-full object-cover"></template>
                        <template x-if="!foto"><div class="w-full h-full flex items-center justify-center"><i class="fas fa-image text-slate-300 text-xl"></i></div></template>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-slate-800 truncate" x-text="namaProduk"></p>
                        <p class="text-sm text-indigo-600 font-semibold" x-text="'Rp ' + harga.toLocaleString('id-ID')"></p>
                        <p class="text-xs text-slate-500">Stok tersedia: <span x-text="stok"></span></p>
                    </div>
                </div>

                <label class="block text-sm font-semibold text-slate-700 mb-2">Jumlah</label>
                <div class="flex items-center gap-3 mb-2">
                    <button type="button" @click="decreaseQty()" class="w-11 h-11 rounded-xl border border-slate-300 text-slate-600 font-bold text-lg hover:bg-slate-50 transition-colors">-</button>
                    <input type="number" x-model.number="qty" min="1" :max="stok" class="flex-1 text-center px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <button type="button" @click="increaseQty()" class="w-11 h-11 rounded-xl border border-slate-300 text-slate-600 font-bold text-lg hover:bg-slate-50 transition-colors">+</button>
                </div>
                <p class="text-xs text-slate-400 mb-5">Maksimal <span x-text="stok"></span> sesuai stok tersedia</p>

                <div class="flex items-center justify-between mb-5 pt-3 border-t border-slate-100">
                    <span class="text-sm font-semibold text-slate-600">Subtotal</span>
                    <span class="text-lg font-bold text-indigo-600" x-text="'Rp ' + (harga * qty).toLocaleString('id-ID')"></span>
                </div>

                <div class="flex gap-3">
                    <button type="button" @click="open = false" class="flex-1 py-3 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition-colors">
                        Batal
                    </button>
                    <button type="button" @click="confirmAddToCart()" :disabled="submitting" class="flex-1 py-3 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold transition-all disabled:opacity-60 flex items-center justify-center gap-2">
                        <i class="fas fa-spinner fa-spin" x-show="submitting"></i>
                        <i class="fas fa-plus-circle" x-show="!submitting"></i>
                        <span x-text="submitting ? 'Menambahkan...' : 'Tambah ke Keranjang'"></span>
                    </button>
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

        // Modal jumlah produk (dipakai lewat x-data="cartQtyModal()")
        function cartQtyModal() {
            return {
                open: false,
                submitting: false,
                produkId: null,
                namaProduk: '',
                harga: 0,
                stok: 0,
                foto: null,
                qty: 1,

                openQtyModal(el) {
                    if (el.disabled) return;

                    this.produkId = el.dataset.produkId;
                    this.namaProduk = el.dataset.namaProduk;
                    this.harga = parseInt(el.dataset.harga, 10);
                    this.stok = parseInt(el.dataset.stok, 10);
                    this.foto = el.dataset.foto || null;
                    this.qty = 1;
                    this.submitting = false;
                    this.open = true;
                },

                increaseQty() {
                    if (this.qty < this.stok) this.qty++;
                },

                decreaseQty() {
                    if (this.qty > 1) this.qty--;
                },

                async confirmAddToCart() {
                    if (this.submitting) return;

                    this.qty = Math.min(Math.max(1, this.qty || 1), this.stok);
                    this.submitting = true;

                    try {
                        const formData = new FormData();
                        formData.append('produk_id', this.produkId);
                        formData.append('jumlah', this.qty);

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
                            sessionStorage.setItem('cartToast', `${this.namaProduk} x${this.qty} ditambahkan ke keranjang`);
                            this.open = false;
                            setTimeout(() => location.reload(), 250);
                        } else {
                            this.submitting = false;
                            showToast(data.message || 'Gagal tambah item', true);
                        }
                    } catch (err) {
                        this.submitting = false;
                        showToast('Terjadi kesalahan, coba lagi', true);
                    }
                },
            };
        }

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

        // Payment method: highlight opsi yang dipilih
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.payment-option').forEach(option => {
                    option.classList.remove('border-indigo-500', 'bg-indigo-50');
                    option.classList.add('border-slate-200');
                });
                this.closest('.payment-option').classList.remove('border-slate-200');
                this.closest('.payment-option').classList.add('border-indigo-500', 'bg-indigo-50');
            });
        });

        // Set initial active payment
        document.querySelector('input[name="payment_method"]:checked').closest('.payment-option').classList.add('border-indigo-500', 'bg-indigo-50');
    </script>
</x-app-layout>
