<?php

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\StockHistory;
use App\Models\Transaksi;
use App\Models\User;

function buatKasir(): User
{
    return User::factory()->create(['role' => 'kasir']);
}

function buatProduk(int $stok = 10, int $harga = 15000): Produk
{
    $kategori = Kategori::create(['nama_kategori' => 'Kategori Test '.uniqid(), 'deskripsi' => null]);

    return Produk::factory()->create([
        'kategori_id' => $kategori->id,
        'harga' => $harga,
        'stok' => $stok,
    ]);
}

test('kasir dapat checkout dan stok produk berkurang sesuai jumlah', function () {
    $kasir = buatKasir();
    $produk = buatProduk(stok: 10, harga: 15000);

    $this->actingAs($kasir)
        ->post(route('transaksi.addToCart'), ['produk_id' => $produk->id, 'jumlah' => 3])
        ->assertOk();

    $response = $this->actingAs($kasir)
        ->post(route('transaksi.store'), ['payment_method' => 'cash']);

    $transaksi = Transaksi::first();

    $response->assertRedirect(route('transaksi.show', $transaksi->id));
    $response->assertSessionHas('success');

    expect($produk->fresh()->stok)->toBe(7);
    expect($transaksi->total_harga)->toBe(45000);
    expect($transaksi->user_id)->toBe($kasir->id);
    expect($transaksi->detail)->toHaveCount(1);
    expect($transaksi->detail->first()->jumlah)->toBe(3);

    expect(StockHistory::where('produk_id', $produk->id)->where('tipe', 'keluar')->exists())->toBeTrue();
});

test('checkout gagal kalau jumlah di keranjang melebihi stok', function () {
    $kasir = buatKasir();
    $produk = buatProduk(stok: 2);

    $response = $this->actingAs($kasir)
        ->post(route('transaksi.addToCart'), ['produk_id' => $produk->id, 'jumlah' => 5]);

    $response->assertStatus(400);
    expect($produk->fresh()->stok)->toBe(2);
});

test('checkout ditolak kalau keranjang kosong', function () {
    $kasir = buatKasir();

    $response = $this->actingAs($kasir)->post(route('transaksi.store'), ['payment_method' => 'cash']);

    $response->assertSessionHas('error');
    expect(Transaksi::count())->toBe(0);
});

test('halaman struk menampilkan data transaksi yang benar', function () {
    $kasir = buatKasir();
    $produk = buatProduk(stok: 10, harga: 20000);

    $this->actingAs($kasir)->post(route('transaksi.addToCart'), ['produk_id' => $produk->id, 'jumlah' => 2]);
    $this->actingAs($kasir)->post(route('transaksi.store'), ['payment_method' => 'qris']);

    $transaksi = Transaksi::first();

    $response = $this->actingAs($kasir)->get(route('transaksi.show', $transaksi->id));

    $response->assertOk();
    $response->assertSee($transaksi->no_transaksi);
    $response->assertSee($produk->nama_produk);
    $response->assertSee('Rp 40.000');
});

test('kasir lain tidak bisa melihat struk transaksi yang bukan miliknya', function () {
    $kasirA = buatKasir();
    $kasirB = buatKasir();
    $produk = buatProduk();

    $this->actingAs($kasirA)->post(route('transaksi.addToCart'), ['produk_id' => $produk->id, 'jumlah' => 1]);
    $this->actingAs($kasirA)->post(route('transaksi.store'), ['payment_method' => 'cash']);

    $transaksi = Transaksi::first();

    $this->actingAs($kasirB)->get(route('transaksi.show', $transaksi->id))->assertForbidden();
});
