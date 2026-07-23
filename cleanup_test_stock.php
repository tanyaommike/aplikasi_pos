<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$histories = \App\Models\StockHistory::whereIn('keterangan', ['Test restock', 'Rusak', 'Overdraw test', 'Stock opname'])->get();
foreach ($histories as $h) {
    echo "Reverting produk_id={$h->produk_id}: restoring stok to {$h->stok_sebelum} (was {$h->stok_sesudah})\n";
}

if ($histories->isNotEmpty()) {
    $produkId = $histories->first()->produk_id;
    $stokAsli = $histories->sortBy('id')->first()->stok_sebelum;
    \App\Models\Produk::where('id', $produkId)->update(['stok' => $stokAsli]);
    \App\Models\StockHistory::whereIn('id', $histories->pluck('id'))->delete();
    echo "Done. Produk id={$produkId} stok restored to {$stokAsli}\n";
}
