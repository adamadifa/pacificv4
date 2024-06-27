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
        Schema::create('keuangan_kasbon', function (Blueprint $table) {
            $table->char('no_kasbon', 8)->primary();
            $table->date('tanggal');
            $table->char('nik', 10);
            $table->char('kode_jabatan', 3);
            $table->char('status_karyawan', 1);
            $table->date('akhir_kontrak')->nullable();
            $table->integer('jumlah');
            $table->date('jatuh_tempo');
            $table->char('status', 1)->default(0);
            $table->bigInteger('id_user');
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
        Schema::dropIfExists('keuangan_kasbon');
    }
};
