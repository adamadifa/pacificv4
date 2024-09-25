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
        Schema::create('marketing_sa_piutangsales', function (Blueprint $table) {
            $table->char('kode_saldo_awal', 11)->primary();
            $table->date('tanggal');
            $table->smallInteger('bulan');
            $table->char('tahun', 4);
            $table->char('kode_cabang', 3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_sa_piutangsales');
    }
};
