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
        Schema::create('hrd_izinabsen', function (Blueprint $table) {
            $table->char('kode_izin')->primary();
            $table->date('tanggal');
            $table->date('dari');
            $table->date('sampai');
            $table->char('nik', 9);
            $table->char('kode_jabatan', 3);
            $table->string('keterangan');
            $table->string('keterangan_hard')->nullable();
            $table->char('status', 1);
            $table->foreign('nik')->references('nik')->on('hrd_karyawan')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_jabatan')->references('kode_jabatan')->on('hrd_jabatan')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrd_izinabsen');
    }
};
