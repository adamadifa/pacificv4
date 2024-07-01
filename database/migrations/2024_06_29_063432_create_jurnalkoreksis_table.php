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
        Schema::create('pembelian_jurnalkoreksi', function (Blueprint $table) {
            $table->char('kode_jurnalkoreksi', 9)->primary();
            $table->date('tanggal');
            $table->char('no_bukti', 16);
            $table->char('kode_barang', 7);
            $table->string('keterangan');
            $table->double('jumlah', 13, 2);
            $table->double('harga', 13, 2);
            $table->char('debet_kredit', 1);
            $table->char('kode_akun', 6);
            $table->foreign('no_bukti')->references('no_bukti')->on('pembelian')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_barang')->references('kode_barang')->on('pembelian_barang')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_akun')->references('kode_akun')->on('coa')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_jurnalkoreksi');
    }
};
