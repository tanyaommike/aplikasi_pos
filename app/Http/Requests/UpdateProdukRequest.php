<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProdukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'kategori_id' => 'required|exists:kategori,id',
            'nama_produk' => 'required|string|max:100',
            'deskripsi'   => 'nullable|string',
            'harga'       => 'required|integer|min:1000',
            'stok'        => 'required|integer|min:0',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'kategori_id.required'   => 'Kategori wajib dipilih.',
            'kategori_id.exists'     => 'Kategori tidak ditemukan.',
            'nama_produk.required'   => 'Nama produk wajib diisi.',
            'nama_produk.max'        => 'Nama produk maksimal 100 karakter.',
            'harga.required'         => 'Harga wajib diisi.',
            'harga.min'              => 'Harga minimal Rp 1.000.',
            'stok.required'          => 'Stok wajib diisi.',
            'stok.min'               => 'Stok tidak boleh minus.',
            'foto.image'             => 'File harus berupa gambar.',
            'foto.mimes'             => 'Format gambar harus JPEG, PNG, JPG, atau GIF.',
            'foto.max'               => 'Ukuran gambar maksimal 2 MB.',
        ];
    }
}