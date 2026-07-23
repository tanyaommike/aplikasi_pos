<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@pos.test'],
            ['name' => 'Admin POS', 'password' => bcrypt('password'), 'role' => 'admin']
        );

        User::firstOrCreate(
            ['email' => 'kasir@pos.test'],
            ['name' => 'Kasir POS', 'password' => bcrypt('password'), 'role' => 'kasir']
        );

        $this->call([
            KategoriSeeder::class,
            ProdukSeeder::class,
            TransaksiSeeder::class,
        ]);
    }
}
