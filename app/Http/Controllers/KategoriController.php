<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Http\Requests\StoreKategoriRequest;
use App\Http\Requests\UpdateKategoriRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class KategoriController extends Controller implements HasMiddleware
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
            abort(403, 'Hanya Admin yang bisa akses kategori');
        }
    }

    // 1. Tampilkan list kategori
    public function index()
    {
        $this->checkAdmin();
        $kategori = Kategori::all();
        return view('kategori.index', compact('kategori'));
    }

    // 2. Tampilkan form create
    public function create()
    {
        $this->checkAdmin();
        return view('kategori.create');
    }

    // 3. Simpan kategori baru
    public function store(StoreKategoriRequest $request)
    {
        Kategori::create($request->validated());
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambah');
    }

    // 4. Tampilkan form edit
    public function edit(Kategori $kategori)
    {
        $this->checkAdmin();
        return view('kategori.edit', compact('kategori'));
    }

    // 5. Update kategori
    public function update(UpdateKategoriRequest $request, Kategori $kategori)
    {
        $kategori->update($request->validated());
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diubah');
    }

    // 6. Delete kategori
    public function destroy(Kategori $kategori)
    {
        $this->checkAdmin();
        
        // Cek apakah kategori ini masih dipakai produk
        if ($kategori->produk()->count() > 0) {
            return redirect()->route('kategori.index')->with('error', 'Kategori tidak bisa dihapus karena masih ada produk yang menggunakannya');
        }
        
        $kategori->delete();
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus');
    }
}