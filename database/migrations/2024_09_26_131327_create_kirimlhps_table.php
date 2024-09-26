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
        Schema::create('kirim_lhp', function (Blueprint $table) {
            $table->char('kode_kirim_lhp', 9)->primary();
            $table->char('kode_cabang', 3);
            $table->smallInteger('bulan');
            $table->char('tahun', 4);
            $table->date('tanggal');
            $table->time('jam');
            $table->char('status');
            $table->string('foto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kirim_lhp');
    }
};
