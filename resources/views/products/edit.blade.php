<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="kategori_id" class="block text-gray-700 font-bold mb-2">Kategori</label>
                        <select name="kategori_id" id="kategori_id" class="w-full border rounded px-3 py-2 @error('kategori_id') border-red-500 @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategori as $kat)
                                <option value="{{ $kat->id }}" {{ old('kategori_id', $produk->kategori_id) == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="nama_produk" class="block text-gray-700 font-bold mb-2">Nama Produk</label>
                        <input type="text" name="nama_produk" id="nama_produk" class="w-full border rounded px-3 py-2 @error('nama_produk') border-red-500 @enderror" value="{{ old('nama_produk', $produk->nama_produk) }}" required>
                        @error('nama_produk')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="deskripsi" class="block text-gray-700 font-bold mb-2">Deskripsi (opsional)</label>
                        <textarea name="deskripsi" id="deskripsi" class="w-full border rounded px-3 py-2" rows="4">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="harga" class="block text-gray-700 font-bold mb-2">Harga (Rp)</label>
                        <input type="number" name="harga" id="harga" class="w-full border rounded px-3 py-2 @error('harga') border-red-500 @enderror" value="{{ old('harga', $produk->harga) }}" min="1000" required>
                        @error('harga')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="stok" class="block text-gray-700 font-bold mb-2">Stok</label>
                        <input type="number" name="stok" id="stok" class="w-full border rounded px-3 py-2 @error('stok') border-red-500 @enderror" value="{{ old('stok', $produk->stok) }}" min="0" required>
                        @error('stok')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="foto" class="block text-gray-700 font-bold mb-2">Foto (opsional)</label>
                        @if ($produk->foto)
                            <div class="mb-2">
                                <p class="text-sm text-gray-600">Foto saat ini:</p>
                                <img src="{{ asset('storage/' . $produk->foto) }}" alt="{{ $produk->nama_produk }}" style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                        @endif
                        <input type="file" name="foto" id="foto" class="w-full border rounded px-3 py-2 @error('foto') border-red-500 @enderror" accept="image/*">
                        @error('foto')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                        <small class="text-gray-500">Kosongkan jika tidak ingin mengubah foto. Max 2MB, format: JPEG, PNG, JPG, GIF</small>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update</button>
                        <a href="{{ route('produk.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>