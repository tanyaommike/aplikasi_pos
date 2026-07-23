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
                        <div class="space-y-3 mb-6 max-h-96 overflow-y-auto">
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
                                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm ml-2" onclick="return confirm('Hapus item?');">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <p class="text-sm font-bold text-indigo-600">{{ format_rupiah($item['subtotal']) }}</p>
                                </div>
                            @endforeach
                        </div>

                        <!-- Total -->
                        <div class="border-t border-slate-200 pt-4 mb-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-slate-600">Subtotal</span>
                                <span class="font-semibold text-slate-800">{{ format_rupiah($total) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-lg font-bold">
                                <span class="text-slate-800">Total</span>
                                <span class="text-indigo-600">{{ format_rupiah($total) }}</span>
                            </div>
                        </div>

                        <!-- Checkout Button -->
                        <form action="{{ route('transaksi.store') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold py-4 px-6 rounded-xl transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
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
                location.reload(); // Reload halaman buat tampilkan keranjang updated
            } else {
                alert(data.message || 'Gagal tambah item');
            }
        });
    </script>
</x-app-layout>
