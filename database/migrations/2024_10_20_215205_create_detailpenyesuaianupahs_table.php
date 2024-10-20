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
        Schema::create('hrd_penyesuaian_upah_detail', function (Blueprint $table) {
            $table->char('kode_gaji', 8);
            $table->char('nik', 9);
            $table->integer('penambah');
            $table->integer('pengurang');
            $table->foreign('kode_gaji')->references('kode_gaji')->on('hrd_penyesuaian_upah')->cascadeOnDelete();
            $table->foreign('nik')->references('nik')->on('hrd_karyawan')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detailpenyesuaianupahs');
    }
};
