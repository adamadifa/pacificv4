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
        Schema::create('hrd_presensi', function (Blueprint $table) {
            $table->id();
            $table->char('nik', 9);
            $table->date('tanggal');
            $table->dateTime('jam_in');
            $table->dateTime('jam_out')->nullable();
            $table->string('foto_in')->nullable();
            $table->string('foto_out')->nullable();
            $table->string('lokasi_in')->nullable();
            $table->string('lokasi_out')->nullable();
            $table->char('kode_jadwal', 5);
            $table->char('kode_jam_kerja', 4);
            $table->char('status', 1);
            $table->foreign('nik')->references('nik')->on('hrd_karyawan')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_jadwal')->references('kode_jadwal')->on('hrd_jadwalkerja')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_jam_kerja')->references('kode_jam_kerja')->on('hrd_jamkerja')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrd_presensi');
    }
};