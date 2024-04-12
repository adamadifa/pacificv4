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
        Schema::create('gudang_cabang_mutasi_detail', function (Blueprint $table) {
            $table->char('no_mutasi', 18);
            $table->char('kode_produk', 6);
            $table->integer('jumlah');
            $table->foreign('no_mutasi')->references('no_mutasi')->on('gudang_cabang_mutasi')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_produk')->references('kode_produk')->on('produk')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gudang_cabang_mutasi_detail');
    }
};
