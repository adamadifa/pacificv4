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
            $table->dropForeign('gudang_bahan_barang_keluar_detail_no_bukti_foreign');
            $table->foreign('no_bukti')->references('no_bukti')->on('gudang_bahan_barang_keluar')->cascadeOnDelete()->cascadeOnUpdate();
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
