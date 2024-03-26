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
        Schema::table('produksi_permintaan_detail', function (Blueprint $table) {
            $table->foreign('no_permintaan')->references('no_permintaan')->on('produksi_permintaan')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('kode_produk')->references('kode_produk')->on('produk')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produksi_permintaan_detail', function (Blueprint $table) {
            $table->dropForeign('produksi_permintaan_detail_kode_produk_foreign');
            $table->dropForeign('produksi_permintaan_detail_no_permintaan_foreign');
        });
    }
};
