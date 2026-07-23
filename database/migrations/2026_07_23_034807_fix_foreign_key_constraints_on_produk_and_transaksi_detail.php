<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix constraint pada produk table
        Schema::table('produk', function (Blueprint $table) {
            $table->dropForeign(['kategori_id']);
            $table->foreign('kategori_id')
                  ->references('id')->on('kategori')
                  ->onDelete('restrict');
        });

        // Fix constraint pada transaksi_detail table
        Schema::table('transaksi_detail', function (Blueprint $table) {
            $table->dropForeign(['produk_id']);
            $table->foreign('produk_id')
                  ->references('id')->on('produk')
                  ->onDelete('restrict');
            
            // Remove timestamps if they exist
            if (Schema::hasColumn('transaksi_detail', 'created_at')) {
                $table->dropColumn(['created_at', 'updated_at']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropForeign(['kategori_id']);
            $table->foreign('kategori_id')
                  ->references('id')->on('kategori')
                  ->onDelete('cascade');
        });

        Schema::table('transaksi_detail', function (Blueprint $table) {
            $table->dropForeign(['produk_id']);
            $table->foreign('produk_id')
                  ->references('id')->on('produk')
                  ->onDelete('cascade');
            
            $table->timestamps();
        });
    }
};
