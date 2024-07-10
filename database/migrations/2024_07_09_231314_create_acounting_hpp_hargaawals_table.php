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
        Schema::create('acounting_hpp_hargaawal', function (Blueprint $table) {
            $table->char('kode_hargaawal', 12)->primary();
            $table->smallInteger('bulan');
            $table->char('tahun', 4);
            $table->char('lokasi', 3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acounting_hpp_hargaawal');
    }
};
