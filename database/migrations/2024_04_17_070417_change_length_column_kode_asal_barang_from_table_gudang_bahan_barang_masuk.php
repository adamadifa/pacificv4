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
        Schema::table('gudang_bahan_barang_masuk', function (Blueprint $table) {
            $table->char('kode_asal_barang', 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gudang_bahan_barang_masuk', function (Blueprint $table) {
            $table->char('kode_asal_barang', 3)->change();
        });
    }
};
