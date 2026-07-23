<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';
    protected $fillable = [
        'user_id',
        'total_harga',
        'no_transaksi',
        'tanggal_transaksi',
        'payment_method',
        'payment_status',
        'uang_dibayar',
        'kembalian',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
        'total_harga' => 'integer',
    ];

    // Relasi: Transaksi milik satu User (kasir)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Transaksi punya banyak TransaksiDetail
    public function detail()
    {
        return $this->hasMany(TransaksiDetail::class, 'transaksi_id');
    }

    // Accessor untuk format total harga
    public function getTotalHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }
}