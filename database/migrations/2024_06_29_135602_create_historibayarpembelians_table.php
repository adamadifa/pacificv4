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
        Schema::create('pembelian_historibayar', function (Blueprint $table) {
            $table->char('no_kontrabon', 13)->primary();
            $table->date('tanggal');
            $table->double('jumlah', 13, 2);
            $table->bigInteger('id_user');
            $table->char('kode_bank', 5);
            $table->char('kode_cabang', 3)->nullable();
            $table->foreign('no_kontrabon')->references('no_kontrabon')->on('pembelian_kontrabon')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_bank')->references('kode_bank')->on('bank')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_historibayar');
    }
};
