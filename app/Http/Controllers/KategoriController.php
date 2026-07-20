<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    // Middleware: hanya admin yang boleh akses

    // 1. Tampilkan list kategori
    public function index()
    {
        // Cek role admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang bisa akses kategori');
        }

        $kategori = Kategori::all();
        return view('kategori.index', compact('kategori'));
    }

    // 2. Tampilkan form create
    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang bisa akses kategori');
        }
        return view('kategori.create');
    }

    // 3. Simpan kategori baru
    public function store(Request $request)
    {

        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang bisa akses kategori');
        }

        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori',
            'deskripsi' => 'nullable|string',
        ]);

        Kategori::create($validated);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambah');
    }

    // 4. Tampilkan form edit
    public function edit(Kategori $kategori)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang bisa akses kategori');
        }

        return view('kategori.edit', compact('kategori'));
    }

    // 5. Update kategori
    public function update(Request $request, Kategori $kategori)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang bisa akses kategori');
        }

        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori,' . $kategori->id,
            'deskripsi' => 'nullable|string',
        ]);

        $kategori->update($validated);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diubah');
    }

    // 6. Delete kategori
    public function destroy(Kategori $kategori)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang bisa akses kategori');
        }
        
        $kategori->delete();
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus');
    }
}