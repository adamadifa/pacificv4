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
        Schema::create('accounting_hpp_hargaawal_detail', function (Blueprint $table) {
            $table->char('kode_hargaawal', 12);
            $table->char('kode_produk', 5);
            $table->double('harga_awal', 15, 2);
            $table->foreign('kode_hargaawal')->references('kode_hargaawal')->on('accounting_hpp_hargaawal')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_produk')->references('kode_produk')->on('produk')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_hpp_hargaawal_detail');
    }
};
