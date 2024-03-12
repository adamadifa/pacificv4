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
        Schema::create('produksi_barang', function (Blueprint $table) {
            $table->char('kode_barang_produksi', 6)->primary();
            $table->string('nama_barang');
            $table->string('satuan', 10);
            $table->char('kode_asal_barang', 2);
            $table->char('kode_kategori', 3);
            $table->char('status_aktif_barang', 1);
            $table->char('kode_barang_gb', 7)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produksi_barang');
    }
};
