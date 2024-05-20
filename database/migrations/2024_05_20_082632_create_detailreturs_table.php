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
        Schema::create('marketing_retur_detail', function (Blueprint $table) {
            $table->char('no_retur', 13);
            $table->char('kode_harga', 7);
            $table->integer('harga_dus');
            $table->integer('harga_pack');
            $table->integer('harga_pcs');
            $table->integer('jumlah');
            $table->timestamps();
            $table->foreign('no_retur')->references('no_retur')->on('marketing_retur')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_harga')->references('kode_harga')->on('produk_harga')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_retur_detail');
    }
};
