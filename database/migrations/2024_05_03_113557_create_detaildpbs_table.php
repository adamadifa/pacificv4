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
        Schema::create('gudang_cabang_dpb_detail', function (Blueprint $table) {
            $table->char('no_dpb', 10);
            $table->char('kode_produk', 6);
            $table->integer('jml_ambil');
            $table->integer('jml_kembali');
            $table->integer('jml_penjualan');
            $table->timestamps();
            $table->foreign('no_dpb')->references('no_dpb')->on('gudang_cabang_dpb')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_produk')->references('kode_produk')->on('produk')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gudang_cabang_dpb_detail');
    }
};
