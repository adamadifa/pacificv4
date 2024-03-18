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
        Schema::table('produksi_barang_saldoawal_detail', function (Blueprint $table) {
            $table->foreign('kode_saldo_awal')->references('kode_saldo_awal')
                ->on('produksi_barang_saldoawal')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('kode_barang_produksi')->references('kode_barang_produksi')
                ->on('produksi_barang')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produksi_barang_saldoawal_detail', function (Blueprint $table) {
            $table->dropForeign('produksi_barang_saldoawal_detail_kode_barang_produksi_foreign');
            $table->dropForeign('produksi_barang_saldoawal_detail_kode_saldo_awal_foreign');
        });
    }
};
