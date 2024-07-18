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
        Schema::create('hrd_jadwalkerja_detail', function (Blueprint $table) {
            $table->char('kode_jadwal', 5);
            $table->string('hari');
            $table->char('kode_jam_kerja', 4);
            $table->foreign('kode_jadwal')->references('kode_jadwal')->on('hrd_jadwalkerja')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_jam_kerja')->references('kode_jam_kerja')->on('hrd_jamkerja')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detailjadwalkerjas');
    }
};
