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
        Schema::create('dpb', function (Blueprint $table) {
            $table->char('no_bukti', 10)->primary();
            $table->char('kode_cabang', 3);
            $table->char('kode_salesman', 7);
            $table->string('tujuan');
            $table->date('tanggal_ambil');
            $table->date('tanggal_kembali');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dpb');
    }
};
