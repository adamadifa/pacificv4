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
        Schema::create('hrd_jadwalshift_detail', function (Blueprint $table) {
            $table->char('kode_jadwalshift', 8);
            $table->char('nik', 9);
            $table->char('kode_jadwal', 5);
            $table->foreign('kode_jadwalshift')->references('kode_jadwalshift')->on('hrd_jadwalshift')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('nik')->references('nik')->on('hrd_karyawan')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_jadwal')->references('kode_jadwal')->on('hrd_jadwalkerja')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrd_jadwalshift_detail');
    }
};
