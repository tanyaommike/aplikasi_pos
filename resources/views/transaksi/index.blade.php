<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2 text-left">No</th>
                            <th class="border px-4 py-2 text-left">No Transaksi</th>
                            <th class="border px-4 py-2 text-left">Kasir</th>
                            <th class="border px-4 py-2 text-left">Tanggal</th>
                            <th class="border px-4 py-2 text-right">Total</th>
                            <th class="border px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaksi as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                                <td class="border px-4 py-2">{{ $item->no_transaksi }}</td>
                                <td class="border px-4 py-2">{{ $item->user->name }}</td>
                                <td class="border px-4 py-2">{{ $item->tanggal_transaksi->format('d-m-Y H:i') }}</td>
                                <td class="border px-4 py-2 text-right">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                <td class="border px-4 py-2 text-center">
                                    <a href="{{ route('transaksi.show', $item->id) }}" style="background-color: #3B82F6; color: white; padding: 0.25rem 0.75rem; border-radius: 0.25rem; font-size: 0.875rem; display: inline-block;">Lihat Struk</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="border px-4 py-2 text-center text-gray-500">Tidak ada transaksi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="p-4">
                    {{ $transaksi->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>