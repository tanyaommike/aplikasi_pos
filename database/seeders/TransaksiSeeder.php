<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Produk;
use App\Models\User;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereIn('role', ['admin', 'kasir'])->get();
        $produkList = Produk::all();

        for ($i = 1; $i <= 15; $i++) {
            $user = $users->random();
            $jumlahItem = rand(1, 3);
            $produkDipilih = $produkList->random($jumlahItem);

            $total = 0;
            $items = [];

            foreach ($produkDipilih as $produk) {
                $jumlah = rand(1, 5);
                $subtotal = $produk->harga * $jumlah;
                $total += $subtotal;

                $items[] = [
                    'produk_id' => $produk->id,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $produk->harga,
                    'subtotal' => $subtotal,
                ];
            }

            $transaksi = Transaksi::create([
                'user_id' => $user->id,
                'total_harga' => $total,
                'no_transaksi' => 'TRX-' . now()->format('Ymd') . '-' . rand(1000, 9999) . $i,
                'tanggal_transaksi' => now()->subDays(rand(0, 30)),
            ]);

            foreach ($items as $item) {
                TransaksiDetail::create(array_merge($item, [
                    'transaksi_id' => $transaksi->id,
                ]));
            }
        }
    }
}