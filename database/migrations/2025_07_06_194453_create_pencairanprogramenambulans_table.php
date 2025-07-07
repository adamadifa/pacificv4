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
        Schema::create('marketing_pencairan_ikatan_enambulan', function (Blueprint $table) {
            $table->char('kode_pencairan', 11)->primary();
            $table->date('tanggal');
            $table->string('keterangan', 255);
            $table->smallInteger('semester');
            $table->char('tahun', 4);
            $table->smallInteger('om')->default(0);
            $table->smallInteger('rsm')->default(0);
            $table->smallInteger('gm')->default(0);
            $table->smallInteger('direktur')->default(0);
            $table->char('status', 1)->default(0);
            $table->char('kode_program', 7);
            $table->char('kode_cabang', 3);
            $table->smallInteger('keuangan')->default(0);
            $table->text('bukti_transfer')->nullable();
            $table->foreign('kode_program')->references('kode_program')->on('program_ikatan')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_cabang')->references('kode_cabang')->on('cabang')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_pencairan_ikatan_enambulan');
    }
};
