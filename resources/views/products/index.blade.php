<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daftar Produk') }}
            </h2>
            <a href="{{ route('produk.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + Tambah Produk
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <form method="GET" action="{{ route('produk.index') }}" class="mb-4 flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" 
            placeholder="Cari nama produk..." 
            class="border rounded px-3 py-2 w-full sm:w-64">

        <select name="kategori_id" class="border rounded px-3 py-2">
            <option value="">Semua Kategori</option>
            @foreach ($kategori as $kat)
                <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                    {{ $kat->nama_kategori }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">
            Cari
        </button>
    </form>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2 text-left">No</th>
                            <th class="border px-4 py-2 text-left">Foto</th>
                            <th class="border px-4 py-2 text-left">Nama Produk</th>
                            <th class="border px-4 py-2 text-left">Kategori</th>
                            <th class="border px-4 py-2 text-left">Harga</th>
                            <th class="border px-4 py-2 text-left">Stok</th>
                            <th class="border px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($produk as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                                <td class="border px-4 py-2">
                                    @if ($item->foto)
                                        <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_produk }}" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <span class="text-gray-500 text-sm">-</span>
                                    @endif
                                </td>
                                <td class="border px-4 py-2">{{ $item->nama_produk }}</td>
                                <td class="border px-4 py-2">{{ $item->kategori->nama_kategori }}</td>
                                <td class="border px-4 py-2">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td class="border px-4 py-2">{{ $item->stok }}</td>
                                <td class="border px-4 py-2 text-center">
                                    <a href="{{ route('produk.edit', $item->id) }}" style="background-color: #EAB308; color: white; padding: 0.25rem 0.75rem; border-radius: 0.25rem; font-size: 0.875rem; display: inline-block;">Edit</a>
                                    <form action="{{ route('produk.destroy', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background-color: #EF4444; color: white; padding: 0.25rem 0.75rem; border-radius: 0.25rem; font-size: 0.875rem; cursor: pointer;">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="border px-4 py-2 text-center text-gray-500">Tidak ada produk</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $produk->appends(request()->query())->links() }}
            </div>

        </div>
    </div>



</x-app-layout>