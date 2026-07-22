<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ProdukController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    private function checkAdmin()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang bisa akses produk');
        }
    }

    // 1. Tampilkan list produk
    public function index(Request $request)
    {
        $query = Produk::query();

        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $produk = $query->latest()->paginate(10);
        $kategori = Kategori::all(); // buat dropdown filter

        return view('products.index', compact('produk', 'kategori'));
    }

    // 2. Tampilkan form create
    public function create()
    {
        $this->checkAdmin();
        $kategori = Kategori::all();
        return view('products.create', compact('kategori'));
    }

    // 3. Simpan produk baru
    public function store(Request $request)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'nama_produk' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|integer|min:1000',
            'stok' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle foto upload
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoPath = $foto->store('produk', 'public');
            $validated['foto'] = $fotoPath;
        }

        Produk::create($validated);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambah');
    }

    // 4. Tampilkan form edit
    public function edit(Produk $produk)
    {
        $this->checkAdmin();
        $kategori = Kategori::all();
        return view('products.edit', compact('produk', 'kategori'));
    }

    // 5. Update produk
    public function update(Request $request, Produk $produk)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'nama_produk' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|integer|min:1000',
            'stok' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle foto upload
        if ($request->hasFile('foto')) {
            // Hapus foto lama kalau ada
            if ($produk->foto && \Storage::disk('public')->exists($produk->foto)) {
                \Storage::disk('public')->delete($produk->foto);
            }
            
            $foto = $request->file('foto');
            $fotoPath = $foto->store('produk', 'public');
            $validated['foto'] = $fotoPath;
        }

        $produk->update($validated);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diubah');
    }

    // 6. Delete produk
    public function destroy(Produk $produk)
    {
        $this->checkAdmin();

        // Hapus foto kalau ada
        if ($produk->foto && \Storage::disk('public')->exists($produk->foto)) {
            \Storage::disk('public')->delete($produk->foto);
        }

        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus');
    }
}