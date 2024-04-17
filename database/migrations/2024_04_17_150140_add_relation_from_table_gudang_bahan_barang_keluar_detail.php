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
        Schema::table('gudang_bahan_barang_keluar_detail', function (Blueprint $table) {
            $table->foreign('no_bukti')->references('no_bukti')->on('gudang_bahan_barang_keluar')->cascadeOnDelete()->restrictOnDelete();
            $table->foreign('kode_barang')->references('kode_barang')->on('pembelian_barang')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gudang_bahan_barang_keluar_detail', function (Blueprint $table) {
            //
        });
    }
};
