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
        Schema::create('hrd_presensi_izincuti', function (Blueprint $table) {
            $table->id('id_presensi');
            $table->char('kode_izin_cuti', 12);
            $table->foreign('id_presensi')->references('id')->on('hrd_presensi')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_izin_cuti')->references('kode_izin_cuti')->on('hrd_izincuti')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrd_presensi_izincuti');
    }
};
