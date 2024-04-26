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
        Schema::table('gudang_logistik_saldoawal_detail', function (Blueprint $table) {
            $table->foreign('kode_saldo_awal')->references('kode_saldo_awal')->on('gudang_logistik_saldoawal')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_barang')->references('kode_barang')->on('pembelian_barang')->restrictOnUpdate()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gudang_logistik_saldoawal_detail', function (Blueprint $table) {
            //
        });
    }
};
