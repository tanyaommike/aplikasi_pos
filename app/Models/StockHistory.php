<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'produk_id',
        'user_id',
        'tipe',
        'jumlah',
        'stok_sebelum',
        'stok_sesudah',
        'keterangan',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'stok_sebelum' => 'integer',
        'stok_sesudah' => 'integer',
        'created_at' => 'datetime',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
