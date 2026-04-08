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
        Schema::create('hrd_log_mesin_presensi', function (Blueprint $table) {
            $table->id();
            $table->string('pin', 20);
            $table->dateTime('jam_absen');
            $table->integer('status_scan');
            $table->unsignedBigInteger('id_mesin')->nullable();
            $table->tinyInteger('status')->comment('0: Gagal, 1: Berhasil');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrd_log_mesin_presensi');
    }
};
