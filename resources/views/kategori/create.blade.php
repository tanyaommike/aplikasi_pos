<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Kategori') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('kategori.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="nama_kategori" class="block text-gray-700 font-bold mb-2">Nama Kategori</label>
                        <input type="text" name="nama_kategori" id="nama_kategori" class="w-full border rounded px-3 py-2 @error('nama_kategori') border-red-500 @enderror" value="{{ old('nama_kategori') }}" required>
                        @error('nama_kategori')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="deskripsi" class="block text-gray-700 font-bold mb-2">Deskripsi (opsional)</label>
                        <textarea name="deskripsi" id="deskripsi" class="w-full border rounded px-3 py-2" rows="4">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Simpan</button>
                        <a href="{{ route('kategori.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>