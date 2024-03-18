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
        Schema::table('produksi_barang_masuk_detail', function (Blueprint $table) {
            $table->foreign('kode_barang_produksi')->references('kode_barang_produksi')->on('produksi_barang')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produksi_barang_masuk_detail', function (Blueprint $table) {
            //
        });
    }
};
