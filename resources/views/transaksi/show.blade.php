<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Struk Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold">STRUK TRANSAKSI</h3>
                    <p class="text-gray-600">{{ $transaksi->no_transaksi }}</p>
                    <p class="text-sm text-gray-500">{{ $transaksi->tanggal_transaksi->format('d-m-Y H:i:s') }}</p>
                </div>

                <div class="border-t border-b py-4 mb-4">
                    <p><strong>Kasir:</strong> {{ $transaksi->user->name }}</p>
                </div>

                <table class="w-full border-collapse mb-4">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-3 py-2 text-left">Produk</th>
                            <th class="border px-3 py-2 text-center">Qty</th>
                            <th class="border px-3 py-2 text-right">Harga</th>
                            <th class="border px-3 py-2 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksi->detail as $item)
                            <tr>
                                <td class="border px-3 py-2">{{ $item->produk->nama_produk }}</td>
                                <td class="border px-3 py-2 text-center">{{ $item->jumlah }}</td>
                                <td class="border px-3 py-2 text-right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                <td class="border px-3 py-2 text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="border-t pt-4">
                    <div class="flex justify-between font-bold text-lg">
                        <span>Total:</span>
                        <span>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">Cetak</button>
                    <a href="{{ route('transaksi.create') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Transaksi Baru</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>