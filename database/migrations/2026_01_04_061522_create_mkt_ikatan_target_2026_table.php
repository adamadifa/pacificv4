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
        Schema::create('mkt_ikatan_target_2026', function (Blueprint $table) {
            $table->char('no_pengajuan', 11);
            $table->char('kode_pelanggan', 13);
            $table->smallInteger('bulan');
            $table->char('tahun', 4);
            $table->integer('target_perbulan');
            $table->smallInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('no_pengajuan')->references('no_pengajuan')->on('mkt_ikatan_2026')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_pelanggan')->references('kode_pelanggan')->on('pelanggan')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mkt_ikatan_target_2026');
    }
};
