<?php

namespace Database\Factories;

use App\Models\Produk;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Produk>
 */
class ProdukFactory extends Factory
{
    /**
     * Nama produk dikelompokkan per kategori supaya kategori yang ter-assign
     * selalu masuk akal (mis. "Kopi Hitam" tidak pernah masuk "Air Mineral").
     */
    private const PRODUK_PER_KATEGORI = [
        'Ngopi' => ['Kopi Hitam', 'Cappuccino', 'Kopi Susu Gula Aren', 'Americano', 'Kopi Tubruk'],
        'Non Kopi' => ['Es Teh Manis', 'Es Jeruk', 'Soda Gembira', 'Wedang Jahe', 'Susu Coklat', 'Milkshake Vanilla', 'Jus Alpukat', 'Es Kelapa Muda', 'Es Krim Coklat', 'Es Campur'],
        'Air Mineral' => ['Aqua 600ml', 'Le Minerale 600ml', 'Air Mineral Gelas', 'Club Soda'],
        'Snack' => ['Kentang Goreng', 'Donat', 'Martabak Mini', 'Roti Bakar', 'Pisang Goreng', 'Salad Buah', 'Sandwich'],
        'Makanan Berat' => ['Nasi Goreng', 'Mie Goreng', 'Ayam Geprek', 'Sate Ayam', 'Bakso', 'Burger'],
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kategori = \App\Models\Kategori::inRandomOrder()->first();
        $namaOptions = $kategori
            ? (self::PRODUK_PER_KATEGORI[$kategori->nama_kategori] ?? ['Produk '.$kategori->nama_kategori])
            : ['Produk Baru'];

        return [
            'kategori_id' => $kategori?->id ?? 1,
            'nama_produk' => fake()->randomElement($namaOptions),
            'deskripsi' => fake()->sentence(),
            'harga' => fake()->numberBetween(5000, 100000),
            'stok' => fake()->numberBetween(0, 100),
            'foto' => null,
        ];
    }
}
