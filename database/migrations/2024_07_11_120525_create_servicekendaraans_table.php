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
        Schema::create('ga_kendaraan_service', function (Blueprint $table) {
            $table->char('kode_service', 9)->primary();
            $table->string('no_invoice');
            $table->date('tanggal');
            $table->char('kode_kendaraan', 6);
            $table->char('kode_bengkel', 6);
            $table->char('kode_cabang', 3);
            $table->foreign('kode_kendaraan')->references('kode_kendaraan')->on('kendaraan')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_cabang')->references('kode_cabang')->on('cabang')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ga_kendaraan_service');
    }
};
