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
        Schema::table('produk', function (Blueprint $table) {
            $table->foreign('kode_kategori_produk')->references('kode_kategori_produk')
                ->on('produk_kategori')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('kode_jenis_produk')->references('kode_jenis_produk')
                ->on('produk_jenis')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->index('kode_kategori_produk');
            $table->index('kode_jenis_produk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropForeign('kode_kategori_produk');
            $table->dropForeign('kode_jenis_produk');
        });
    }
};
