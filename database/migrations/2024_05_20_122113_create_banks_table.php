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
        Schema::create('bank', function (Blueprint $table) {
            $table->char('kode_bank', 5)->primary();
            $table->string('nama_bank');
            $table->string('no_rekening')->nullable();
            $table->char('kode_cabang', 3);
            $table->char('show_on_cabang', 1);
            $table->char('kode_akun', 6)->nullable();
            $table->char('jenis_rekening', 1);
            $table->timestamps();
            $table->foreign('kode_cabang')->references('kode_cabang')->on('cabang')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank');
    }
};
