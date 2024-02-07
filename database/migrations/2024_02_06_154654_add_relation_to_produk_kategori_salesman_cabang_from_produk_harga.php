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
        Schema::table('produk_harga', function (Blueprint $table) {
            $table->foreign('kode_produk')
                ->references('kode_produk')
                ->on('produk')->cascadeOnUpdate()->restrictOnDelete();

            $table->foreign('kode_kategori_salesman')
                ->references('kode_kategori_salesman')
                ->on('salesman_kategori')->cascadeOnUpdate()->restrictOnDelete();

            $table->foreign('kode_cabang')
                ->references('kode_cabang')
                ->on('cabang')->cascadeOnUpdate()->restrictOnDelete();

            $table->index('kode_produk');
            $table->index('kode_kategori_salesman');
            $table->index('kode_cabang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produk_harga', function (Blueprint $table) {
            $table->dropForeign('kode_produk');
            $table->dropForeign('kode_kategori_salesman');
            $table->dropForeign('kode_cabang');

            $table->dropIndex('kode_produk');
            $table->dropIndex('kode_kategori_salesman');
            $table->dropIndex('kode_cabang');
        });
    }
};
