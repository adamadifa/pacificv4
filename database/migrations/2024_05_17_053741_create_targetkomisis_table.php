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
        Schema::create('marketing_komisi_target', function (Blueprint $table) {
            $table->char('kode_target', 9)->primary();
            $table->char('kode_cabang', 3);
            $table->smallInteger('bulan');
            $table->char('tahun', 4);
            $table->char('status', 1);
            $table->foreign('kode_cabang')->references('kode_cabang')->on('cabang')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_komisi_target');
    }
};
