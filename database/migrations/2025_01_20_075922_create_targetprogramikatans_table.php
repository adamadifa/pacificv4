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
        Schema::create('marketing_program_ikatan_target', function (Blueprint $table) {
            $table->char('no_pengajuan', 11);
            $table->char('kode_pelanggan', 13);
            $table->smallInteger('bulan');
            $table->char('tahun', 4);
            $table->integer('target_perbulan');
            $table->foreign('no_pengajuan')->references('no_pengajuan')->on('marketing_program_ikatan')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_pelanggan')->references('kode_pelanggan')->on('pelanggan')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_program_ikatan_target');
    }
};
