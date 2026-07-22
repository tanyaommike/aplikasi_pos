<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array(auth()->user()->role, ['kasir', 'admin']);
    }

    public function rules(): array
    {
        return [
            'produk_id' => 'required|exists:produk,id',
            'jumlah'    => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'produk_id.required' => 'Produk wajib dipilih.',
            'produk_id.exists'   => 'Produk tidak ditemukan.',
            'jumlah.required'    => 'Jumlah wajib diisi.',
            'jumlah.min'         => 'Jumlah minimal 1.',
        ];
    }
}