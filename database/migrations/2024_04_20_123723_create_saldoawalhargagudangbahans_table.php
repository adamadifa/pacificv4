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
        Schema::create('gudang_bahan_saldoawal_harga', function (Blueprint $table) {
            $table->char('kode_saldo_awal', 6)->primary();
            $table->date('tanggal');
            $table->smallInteger('bulan');
            $table->char('tahun', 4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gudang_bahan_saldoawal_harga');
    }
};