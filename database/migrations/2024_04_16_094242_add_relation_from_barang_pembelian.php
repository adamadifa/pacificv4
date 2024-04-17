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
        Schema::table('pembelian_barang', function (Blueprint $table) {
            $table->foreign('kode_kategori')->references('kode_kategori')->on('pembelian_barang_kategori')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembelian_barang', function (Blueprint $table) {
            $table->dropForeign('pembelian_barang_kode_kategori_foreign');
        });
    }
};
