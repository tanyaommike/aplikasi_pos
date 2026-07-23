<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProdukApiController extends Controller
{
    // GET /api/produk?search=&kategori_id=&per_page=
    public function index(Request $request): JsonResponse
    {
        $query = Produk::with('kategori')->available();

        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $perPage = min((int) $request->input('per_page', 15), 50) ?: 15;
        $produk = $query->latest()->paginate($perPage);

        return response()->json([
            'data' => $produk->getCollection()->map(fn (Produk $item) => [
                'id' => $item->id,
                'nama_produk' => $item->nama_produk,
                'deskripsi' => $item->deskripsi,
                'harga' => $item->harga,
                'harga_formatted' => $item->harga_formatted,
                'stok' => $item->stok,
                'kategori' => $item->kategori ? [
                    'id' => $item->kategori->id,
                    'nama_kategori' => $item->kategori->nama_kategori,
                ] : null,
                'foto_url' => $item->foto ? asset('storage/'.$item->foto) : null,
            ]),
            'meta' => [
                'current_page' => $produk->currentPage(),
                'last_page' => $produk->lastPage(),
                'per_page' => $produk->perPage(),
                'total' => $produk->total(),
            ],
        ]);
    }
}
