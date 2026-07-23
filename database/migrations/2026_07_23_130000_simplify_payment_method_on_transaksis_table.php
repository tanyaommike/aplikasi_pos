<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // debit/credit tidak lagi didukung, dianggap setara transfer bank
        DB::table('transaksis')->whereIn('payment_method', ['debit', 'credit'])->update(['payment_method' => 'transfer']);

        // Schema Blueprint (bukan raw SQL) supaya jalan di semua driver
        // (mysql untuk aplikasi, sqlite untuk testing).
        Schema::table('transaksis', function (Blueprint $table) {
            $table->enum('payment_method', ['cash', 'qris', 'transfer'])->default('cash')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->enum('payment_method', ['cash', 'qris', 'debit', 'credit'])->default('cash')->change();
        });
    }
};
