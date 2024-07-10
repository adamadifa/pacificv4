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
        Schema::create('accounting_hpp_detail', function (Blueprint $table) {
            $table->char('kode_hpp', 9);
            $table->char('kode_produk', 5);
            $table->double('harga_hpp', 15, 9);
            $table->foreign('kode_hpp')->references('kode_hpp')->on('accounting_hpp')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('kode_produk')->references('kode_produk')->on('produk')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_hpp_detail');
    }
};
