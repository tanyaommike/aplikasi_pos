<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKategoriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        $kategoriId = $this->route('kategori')->id;

        return [
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori,' . $kategoriId,
            'deskripsi'     => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique'   => 'Nama kategori sudah ada, pakai nama lain.',
        ];
    }
}