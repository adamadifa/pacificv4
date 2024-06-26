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
        Schema::create('produksi_barang_keluar_detail', function (Blueprint $table) {
            $table->char('no_bukti', 13);
            $table->char('kode_barang_produksi', 6);
            $table->string('keterangan')->nullable();
            $table->double('jumlah', 8, 2);
            $table->double('jumlah_berat', 8, 2);
            $table->foreign('no_bukti')->references('no_bukti')->on('produksi_barang_keluar')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_barang_produksi')->references('kode_barang_produksi')->on('produksi_barang')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produksi_barang_keluar_detail');
    }
};
