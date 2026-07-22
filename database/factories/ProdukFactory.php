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
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kategori_id' => \App\Models\Kategori::inRandomOrder()->first()?->id ?? 1,

            'nama_produk' => fake()->unique()->randomElement([
                'Es Teh Manis', 'Es Jeruk', 'Kopi Hitam', 'Cappuccino', 'Nasi Goreng',
                'Mie Goreng', 'Ayam Geprek', 'Kentang Goreng', 'Roti Bakar', 'Pisang Goreng',
                'Es Krim Coklat', 'Jus Alpukat', 'Soda Gembira', 'Sate Ayam', 'Bakso',
                'Es Campur', 'Donat', 'Sandwich', 'Burger', 'Salad Buah',
                'Milkshake Vanilla', 'Wedang Jahe', 'Susu Coklat', 'Es Kelapa Muda', 'Martabak Mini',
            ]),

            'deskripsi' => fake()->sentence(),
            'harga' => fake()->numberBetween(5000, 100000),
            'stok' => fake()->numberBetween(0, 100),
            'foto' => null,
        ];
    }
}
