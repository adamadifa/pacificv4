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
        Schema::create('gudang_logistik_barang_keluar', function (Blueprint $table) {
            $table->char('no_bukti', 17)->primary();
            $table->date('tanggal');
            $table->char('kode_jenis_pengeluaran', 3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gudang_logistik_barang_keluar');
    }
};
