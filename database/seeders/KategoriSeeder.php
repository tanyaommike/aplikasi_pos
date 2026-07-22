<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            ['nama_kategori' => 'Ngopi', 'deskripsi' => 'Minuman berbahan dasar kopi'],
            ['nama_kategori' => 'Non Kopi', 'deskripsi' => 'Minuman tanpa kopi seperti teh'],
            ['nama_kategori' => 'Air Mineral', 'deskripsi' => 'Air mineral berbagai merek'],
            ['nama_kategori' => 'Snack', 'deskripsi' => 'Camilan ringan'],
            ['nama_kategori' => 'Makanan Berat', 'deskripsi' => 'Menu makanan utama'],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::firstOrCreate(
                ['nama_kategori' => $kategori['nama_kategori']],
                $kategori
            );
        }
    }
}