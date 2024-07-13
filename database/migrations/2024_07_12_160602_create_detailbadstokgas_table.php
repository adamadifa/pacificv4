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
        Schema::create('ga_badstok_detail', function (Blueprint $table) {
            $table->char('kode_bs', 8);
            $table->char('kode_produk', 5);
            $table->integer('jumlah');
            $table->foreign('kode_bs')->references('kode_bs')->on('ga_badstok')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_produk')->references('kode_produk')->on('produk')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ga_badstok_detail');
    }
};
