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
        Schema::create('gudang_bahan_saldoawal_detail', function (Blueprint $table) {
            $table->char('kode_saldo_awal', 6);
            $table->char('kode_barang', 7);
            $table->double('qty_unit', 13, 2);
            $table->double('qty_berat', 13, 2);
            $table->foreign('kode_saldo_awal')->references('kode_saldo_awal')->on('gudang_bahan_saldoawal')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_barang')->references('kode_barang')->on('pembelian_barang')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gudang_bahan_saldoawal_detail');
    }
};
