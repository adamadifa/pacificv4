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
        Schema::create('hrd_lembur', function (Blueprint $table) {
            $table->char('kode_lembur', 7)->primary();
            $table->date('tanggal');
            $table->date('tanggal_dari');
            $table->date('tanggal_sampai');
            $table->char('kode_cabang', 3);
            $table->char('kode_dept', 3);
            $table->string('keterangan');
            $table->string('keterangan_hrd')->nullable();
            $table->char('kategori', 1);
            $table->char('istirahat', 1);
            $table->char('status', 1);
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
        Schema::dropIfExists('hrd_lembur');
    }
};
