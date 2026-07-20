<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $fillable = [
        'kategori_id',
        'nama_produk',
        'deskripsi',
        'harga',
        'stok',
        'foto',
    ];

    // Relasi: Produk milik satu Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // Relasi: Produk ada di banyak TransaksiDetail
    public function transaksiDetail()
    {
        return $this->hasMany(TransaksiDetail::class, 'produk_id');
    }
}