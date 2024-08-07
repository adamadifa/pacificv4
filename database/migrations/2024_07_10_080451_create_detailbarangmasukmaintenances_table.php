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
        Schema::create('maintenance_barang_masuk_detail', function (Blueprint $table) {
            $table->char('no_bukti', 17);
            $table->char('kode_barang', 7);
            $table->string('keterangan')->nullable();
            $table->double('jumlah', 8, 2);
            $table->foreign('no_bukti')->references('no_bukti')->on('maintenance_barang_masuk')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_barang')->references('kode_barang')->on('pembelian_barang')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_barang_masuk_detail');
    }
};
