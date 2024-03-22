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
        Schema::create('marketing_oman_cabang', function (Blueprint $table) {
            $table->char('kode_oman', 9)->primary();
            $table->smallInteger('bulan');
            $table->char('tahun', 4);
            $table->char('kode_cabang', 3);
            $table->char('status_oman_cabang', 1);
            $table->foreign('kode_cabang')->references('kode_cabang')
                ->on('cabang')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_oman_cabang');
    }
};
