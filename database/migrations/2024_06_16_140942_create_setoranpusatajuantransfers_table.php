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
        Schema::create('keuangan_setoranpusat_ajuantransfer', function (Blueprint $table) {
            $table->char('kode_setoran', 11);
            $table->char('no_pengajuan', 13);
            $table->foreign('kode_setoran')->references('kode_setoran')->on('keuangan_setoranpusat')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('no_pengajuan')->references('no_pengajuan')->on('keuangan_ajuantransferdana')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_setoranpusat_ajuantransfer');
    }
};
