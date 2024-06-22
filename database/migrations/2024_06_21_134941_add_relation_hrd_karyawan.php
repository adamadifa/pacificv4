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
        Schema::table('hrd_karyawan', function (Blueprint $table) {
            $table->foreign('kode_jabatan')->references('kode_jabatan')->on('hrd_jabatan')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_dept')->references('kode_dept')->on('hrd_departemen')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_cabang')->references('kode_cabang')->on('cabang')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_group')->references('kode_group')->on('hrd_group')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_klasifikasi')->references('kode_klasifikasi')->on('hrd_klasifikasi')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hrd_karyawan', function (Blueprint $table) {
            //
        });
    }
};
