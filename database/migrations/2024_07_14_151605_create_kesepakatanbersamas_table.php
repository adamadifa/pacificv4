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
        Schema::create('hrd_kesepakatanbersama', function (Blueprint $table) {
            $table->char('no_kb', 9)->primary();
            $table->date('tanggal');
            $table->char('nik', 10);
            $table->char('kode_penilaian', 9);
            $table->char('no_kontrak', 8);
            $table->char('kode_gaji', 7);
            $table->foreign('no_kontrak')->references('no_kontrak')->on('hrd_kontrak')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_gaji')->references('kode_gaji')->on('hrd_gaji')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('nik')->references('nik')->on('hrd_karyawan')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_penilaian')->references('kode_penilaian')->on('hrd_penilaian')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrd_kesepakatanbersama');
    }
};
