<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Form Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-6">
                <!-- Kolom kiri: Pilih Produk -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Pilih Produk</h3>

                    <form id="formAddToCart">
                        @csrf
                        <div class="mb-4">
                            <label for="produk_id" class="block text-gray-700 font-bold mb-2">Produk</label>
                            <select id="produk_id" name="produk_id" class="w-full border rounded px-3 py-2" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach ($produk as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_produk }} (Rp {{ number_format($item->harga, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="jumlah" class="block text-gray-700 font-bold mb-2">Jumlah</label>
                            <input type="number" id="jumlah" name="jumlah" class="w-full border rounded px-3 py-2" value="1" min="1" required>
                        </div>

                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Tambah ke Keranjang</button>
                    </form>

                    @if ($errors->any())
                        <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Kolom kanan: Keranjang -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Keranjang ({{ count($cart) }} item)</h3>

                    @if (count($cart) > 0)
                        <table class="w-full border-collapse mb-4">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-2 py-2 text-left text-sm">Produk</th>
                                    <th class="border px-2 py-2 text-center text-sm">Qty</th>
                                    <th class="border px-2 py-2 text-right text-sm">Harga</th>
                                    <th class="border px-2 py-2 text-right text-sm">Subtotal</th>
                                    <th class="border px-2 py-2 text-center text-sm">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach ($cart as $item)
                                    @php $total += $item['subtotal']; @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="border px-2 py-2 text-sm">{{ $item['nama_produk'] }}</td>
                                        <td class="border px-2 py-2 text-center text-sm">{{ $item['jumlah'] }}</td>
                                        <td class="border px-2 py-2 text-right text-sm">Rp {{ number_format($item['harga_satuan'], 0, ',', '.') }}</td>
                                        <td class="border px-2 py-2 text-right text-sm">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                                        <td class="border px-2 py-2 text-center">
                                            <form action="{{ route('transaksi.removeFromCart', $item['produk_id']) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm" onclick="return confirm('Hapus item?');">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="border-t pt-4 mb-4">
                            <div class="flex justify-between font-bold text-lg">
                                <span>Total:</span>
                                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <form action="{{ route('transaksi.store') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Selesaikan Transaksi</button>
                        </form>
                    @else
                        <p class="text-gray-500 text-center py-8">Keranjang kosong</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('formAddToCart').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(this);
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