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
        Schema::create('hrd_izinpulang', function (Blueprint $table) {
            $table->char('kode_izin_pulang', 12)->primary();
            $table->date('tanggal');
            $table->char('nik', 9);
            $table->char('kode_jabatan', 3);
            $table->char('kode_cabang', 3);
            $table->char('kode_dept', 3);
            $table->dateTime('jam_pulang');
            $table->string('keterangan');
            $table->string('keterangan_hrd')->nullable();
            $table->char('status', 1);
            $table->foreign('nik')->references('nik')->on('hrd_karyawan')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_jabatan')->references('kode_jabatan')->on('hrd_jabatan')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_cabang')->references('kode_cabang')->on('cabang')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_dept')->references('kode_dept')->on('hrd_departemen')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrd_izinpulang');
    }
};
