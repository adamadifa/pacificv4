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
        Schema::create('worksheetom_retur_pelunasan', function (Blueprint $table) {
            $table->char('no_retur', 13);
            $table->char('kode_harga', 7);
            $table->integer('jumlah');
            $table->char('no_dpb', 10);
            $table->foreign('no_retur')->references('no_retur')->on('marketing_retur')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_harga')->references('kode_harga')->on('produk_harga')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('no_dpb')->references('no_dpb')->on('gudang_cabang_dpb')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worksheetom_retur_pelunasan');
    }
};
