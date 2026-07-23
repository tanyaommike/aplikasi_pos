<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'produk';
    protected $fillable = [
        'kategori_id',
        'nama_produk',
        'deskripsi',
        'harga',
        'stok',
        'foto',
    ];

    protected $casts = [
        'harga' => 'integer',
        'stok' => 'integer',
    ];

    // Relasi: Produk milik satu Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id')->withTrashed();
    }

    // Relasi: Produk ada di banyak TransaksiDetail
    public function transaksiDetail()
    {
        return $this->hasMany(TransaksiDetail::class, 'produk_id');
    }

    // Accessor untuk format harga
    public function getHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    // Scope untuk produk yang tersedia
    public function scopeAvailable($query)
    {
        return $query->where('stok', '>', 0);
    }
}