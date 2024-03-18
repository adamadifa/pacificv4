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
        Schema::create('produksi_barang_keluar', function (Blueprint $table) {
            $table->char('no_bukti', 13)->primary();
            $table->date('tanggal');
            $table->char('kode_jenis_pengeluaran', 2);
            $table->char('kode_supplier', 6)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produksi_barang_keluar');
    }
};
