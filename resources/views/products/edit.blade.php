<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-3">
                    <i class="fas fa-edit text-amber-600"></i>
                    Edit Produk
                </h2>
                <p class="text-sm text-slate-600 mt-1">Ubah informasi produk</p>
            </div>
            <a href="{{ route('produk.index') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-800 font-medium">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            <form action="{{ route('produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Kategori -->
                        <div>
                            <label for="kategori_id" class="block text-sm font-semibold text-slate-700 mb-2">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select name="kategori_id" id="kategori_id" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors @error('kategori_id') border-red-500 @enderror" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($kategori as $kat)
                                    <option value="{{ $kat->id }}" {{ old('kategori_id', $produk->kategori_id) == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Nama Produk -->
                        <div>
                            <label for="nama_produk" class="block text-sm font-semibold text-slate-700 mb-2">
                                Nama Produk <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_produk" id="nama_produk" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors @error('nama_produk') border-red-500 @enderror" value="{{ old('nama_produk', $produk->nama_produk) }}" required>
                            @error('nama_produk')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Harga -->
                        <div>
                            <label for="harga" class="block text-sm font-semibold text-slate-700 mb-2">
                                Harga (Rp) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-500 font-semibold">Rp</span>
                                <input type="number" name="harga" id="harga" class="w-full pl-12 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors @error('harga') border-red-500 @enderror" value="{{ old('harga', $produk->harga) }}" min="1000" required>
                            </div>
                            @error('harga')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Stok -->
                        <div>
                            <label for="stok" class="block text-sm font-semibold text-slate-700 mb-2">
                                Stok <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="stok" id="stok" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors @error('stok') border-red-500 @enderror" value="{{ old('stok', $produk->stok) }}" min="0" required>
                            @error('stok')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Deskripsi -->
                        <div>
                            <label for="deskripsi" class="block text-sm font-semibold text-slate-700 mb-2">
                                Deskripsi
                            </label>
                            <textarea name="deskripsi" id="deskripsi" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors @error('deskripsi') border-red-500 @enderror" rows="4">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Foto -->
                        <div>
                            <label for="foto" class="block text-sm font-semibold text-slate-700 mb-2">
                                Foto Produk
                            </label>
                            <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:border-amber-500 transition-colors">
                                <input type="file" name="foto" id="foto" class="hidden" accept="image/*" onchange="previewImage(event)">
                                <label for="foto" class="cursor-pointer">
                                    <div id="preview-container" class="{{ $produk->foto ? '' : 'hidden' }} mb-4">
                                        <img id="preview" src="{{ $produk->foto ? asset('storage/' . $produk->foto) : '' }}" class="mx-auto rounded-lg max-h-48 object-cover">
                                        <p class="text-xs text-slate-500 mt-2">Klik untuk ganti foto</p>
                                    </div>
                                    <div id="upload-placeholder" class="{{ $produk->foto ? 'hidden' : '' }}">
                                        <i class="fas fa-cloud-upload-alt text-slate-400 text-4xl mb-3"></i>
                                        <p class="text-slate-600 font-medium">Klik untuk upload foto</p>
                                        <p class="text-sm text-slate-500 mt-1">Max 2MB, format: JPEG, PNG, JPG, GIF</p>
                                    </div>
                                </label>
                            </div>
                            <p class="mt-2 text-xs text-slate-500">Kosongkan jika tidak ingin mengubah foto</p>
                            @error('foto')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-8 mt-8 border-t border-slate-200">
                    <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-semibold py-3 px-6 rounded-xl transition-all shadow-md hover:shadow-lg">
                        <i class="fas fa-save"></i>
                        Update Produk
                    </button>
                    <a href="{{ route('produk.index') }}" class="flex-1 inline-flex items-center justify-center gap-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold py-3 px-6 rounded-xl transition-colors">
                        <i class="fas fa-times"></i>
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result;
                    document.getElementById('preview-container').classList.remove('hidden');
                    document.getElementById('upload-placeholder').classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app-layout>
