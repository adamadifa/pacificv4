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
        Schema::create('gudang_logistik_opname_detail', function (Blueprint $table) {
            $table->char('kode_opname', 12);
            $table->char('kode_barang', 7);
            $table->double('jumlah', 11, 2);
            $table->foreign('kode_opname')->references('kode_opname')->on('gudang_logistik_opname')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_barang')->references('kode_barang')->on('pembelian_barang')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gudang_logistik_opname_detail');
    }
};
