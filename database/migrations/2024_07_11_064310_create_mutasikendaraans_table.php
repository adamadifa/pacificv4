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
        Schema::create('ga_kendaraan_mutasi', function (Blueprint $table) {
            $table->char('no_mutasi', 8)->primary();
            $table->date('tanggal');
            $table->char('kode_kendaraan', 6);
            $table->char('kode_cabang_asal', 3);
            $table->char('kode_cabang_tujuan', 3);
            $table->string('keterangan')->nullable();
            $table->foreign('kode_kendaraan')->references('kode_kendaraan')->on('kendaraan')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ga_kendaraan_mutasi');
    }
};
