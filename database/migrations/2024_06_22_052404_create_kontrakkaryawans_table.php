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
        Schema::create('hrd_kontrak', function (Blueprint $table) {
            $table->char('no_kontrak', 8)->primary();
            $table->char('nik', 10);
            $table->date('tanggal');
            $table->date('dari');
            $table->date('sampai');
            $table->char('kode_jabatan', 3)->nullable();
            $table->char('kode_cabang', 3)->nullable();
            $table->char('kode_perusahaan', 2)->nullable();
            $table->char('kode_dept', 3)->nullable();
            $table->char('status_pemutihan', 1)->default(0);
            $table->char('status_kontrak', 1)->default(1);
            // $table->foreign('kode_jabatan')->references('kode_jabatan')->on('hrd_jabatan')->restrictOnDelete()->cascadeOnUpdate();
            // $table->foreign('kode_cabang')->references('kode_cabang')->on('cabang')->restrictOnDelete()->cascadeOnUpdate();
            // $table->foreign('kode_dept')->references('kode_dept')->on('hrd_departemen')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('nik')->references('nik')->on('hrd_karyawan')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrd_kontrak');
    }
};
