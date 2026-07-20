<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daftar Kategori') }}
            </h2>
            <a href="{{ route('kategori.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + Tambah Kategori
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($message = Session::get('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ $message }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2 text-left">No</th>
                            <th class="border px-4 py-2 text-left">Nama Kategori</th>
                            <th class="border px-4 py-2 text-left">Deskripsi</th>
                            <th class="border px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kategori as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                                <td class="border px-4 py-2">{{ $item->nama_kategori }}</td>
                                <td class="border px-4 py-2">{{ $item->deskripsi ?? '-' }}</td>
                                <td class="border px-4 py-2 text-center">
                                    <a href="{{ route('kategori.edit', $item->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white px-3 py-1 rounded text-sm">Edit</a>
                                    <form action="{{ route('kategori.destroy', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="border px-4 py-2 text-center text-gray-500">Tidak ada kategori</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>