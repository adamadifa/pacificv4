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
        Schema::create('keuangan_piutangkaryawan', function (Blueprint $table) {
            $table->char('no_pinjaman', 8)->primary();
            $table->date('tanggal');
            $table->char('nik', 10);
            $table->integer('jumlah');
            $table->bigInteger('id_user');
            $table->char('status', 1)->default(0); //0 Bisa Dilihat Oleh HRD, 1 Hanya Bisa Dilihat Oleh Keuangan
            $table->foreign('nik')->references('nik')->on('hrd_karyawan')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_piutangkaryawan');
    }
};
