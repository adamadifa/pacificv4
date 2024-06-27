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
        Schema::create('keuangan_kasbon_historibayar', function (Blueprint $table) {
            $table->char('no_bukti', 8)->primary();
            $table->date('tanggal');
            $table->char('no_kasbon', 8);
            $table->integer('jumlah');
            $table->char('kode_potongan', 8)->nullable();
            $table->bigInteger('id_user');
            $table->foreign('no_kasbon')->references('no_kasbon')->on('keuangan_kasbon')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_kasbon_historibayar');
    }
};
