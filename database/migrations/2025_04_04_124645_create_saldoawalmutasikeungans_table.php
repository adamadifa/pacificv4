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
        Schema::create('keuangan_mutasi_saldoawal', function (Blueprint $table) {
            $table->char('kode_saldo_awal', 11)->primary();
            $table->date('tanggal');
            $table->smallInteger('bulan');
            $table->char('tahun', 4);
            $table->char('kode_bank', 5);
            $table->double('jumlah', 13, 2);
            $table->foreign('kode_bank')->references('kode_bank')->on('bank')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_mutasi_saldoawal');
    }
};
