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
        Schema::create('marketing_pencairan_ikatan_2026', function (Blueprint $table) {
            $table->char('kode_pencairan', 11)->primary();
            $table->date('tanggal');
            $table->char('kode_program', 11);
            $table->char('kode_cabang', 3);
            $table->integer('bulan');
            $table->integer('tahun');
            $table->string('keterangan');
            $table->char('status', 1)->default('0'); // 0:pending, 1:approved, 2:rejected
            $table->integer('om')->nullable();
            $table->integer('rsm')->nullable();
            $table->integer('gm')->nullable();
            $table->integer('direktur')->nullable();
            $table->integer('keuangan')->nullable();
            $table->string('bukti_transfer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_pencairan_ikatan_2026');
    }
};
