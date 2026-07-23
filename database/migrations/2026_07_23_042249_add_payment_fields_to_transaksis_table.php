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
        Schema::table('transaksis', function (Blueprint $table) {
            $table->enum('payment_method', ['cash', 'qris', 'debit', 'credit'])->default('cash')->after('total_harga');
            $table->enum('payment_status', ['pending', 'paid'])->default('paid')->after('payment_method');
            $table->decimal('uang_dibayar', 15, 2)->nullable()->after('payment_status');
            $table->decimal('kembalian', 15, 2)->nullable()->after('uang_dibayar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_status', 'uang_dibayar', 'kembalian']);
        });
    }
};
