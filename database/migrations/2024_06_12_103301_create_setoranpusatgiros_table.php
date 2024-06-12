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
        Schema::create('keuangan_setoranpusat_giro', function (Blueprint $table) {
            $table->char('kode_setoran', 11);
            $table->char('kode_giro', 10);
            $table->foreign('kode_setoran')->references('kode_setoran')->on('keuangan_setoranpusat')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_giro')->references('kode_giro')->on('marketing_penjualan_giro')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_setoranpusat_giro');
    }
};
