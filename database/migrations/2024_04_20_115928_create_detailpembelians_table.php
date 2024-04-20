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
        Schema::create('pembelian_detail', function (Blueprint $table) {
            $table->char('no_bukti', 17);
            $table->char('kode_barang', 7);
            $table->double('jumlah', 8, 2);
            $table->double('harga', 13, 2);
            $table->double('penyesuaian', 13, 2);
            $table->string('keterangan')->nullable();
            $table->string('keterangan_penjualan')->nullable();
            $table->char('kode_transaksi', 3);
            $table->char('kode_akun', 6);
            $table->smallInteger('konversi_gram')->nullable();
            $table->char('kode_cabang', 3)->nullable();
            $table->char('kode_cost_ratio', 10)->nullable();
            $table->timestamps();
            $table->foreign('no_bukti')->references('no_bukti')->on('pembelian')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_detail');
    }
};
