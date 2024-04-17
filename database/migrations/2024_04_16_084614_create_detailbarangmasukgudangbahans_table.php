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
        Schema::create('gudang_bahan_barang_masuk_detail', function (Blueprint $table) {
            $table->char('no_bukti', 17);
            $table->char('kode_barang', 7);
            $table->string('keterangan')->nullable();
            $table->double('qty_lebih', 13, 2);
            $table->double('qty_berat', 13, 2);
            $table->double('qty_unit', 13, 2);
            $table->timestamps();
            $table->foreign('no_bukti')->references('no_bukti')->on('gudang_bahan_barang_masuk')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gudang_bahan_barang_masuk_detail');
    }
};
