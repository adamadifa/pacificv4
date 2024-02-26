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
        Schema::create('tutup_laporan', function (Blueprint $table) {
            $table->char('kode_tutup_laporan', 8)->primary();
            $table->smallInteger('bulan');
            $table->char('tahun', 4);
            $table->string('jenis_laporan', 15);
            $table->date('tanggal');
            $table->char('status', 1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutup_laporan');
    }
};
