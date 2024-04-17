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
        Schema::table('gudang_bahan_barang_masuk_detail', function (Blueprint $table) {
            $table->foreign('kode_barang')->references('kode_barang')->on('pembelian_barang')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gudang_bahan_barang_masuk_detail', function (Blueprint $table) {
            //
        });
    }
};
