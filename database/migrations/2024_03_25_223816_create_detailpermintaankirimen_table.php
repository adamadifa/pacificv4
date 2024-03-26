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
        Schema::create('marketing_permintaan_kiriman_detail', function (Blueprint $table) {
            $table->char('no_permintaan', 18);
            $table->char('kode_produk', 6);
            $table->integer('jumlah');
            $table->foreign('no_permintaan')->references('no_permintaan')->on('marketing_permintaan_kiriman')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('kode_produk')->references('kode_produk')->on('produk')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_permintaan_kiriman_detail');
    }
};
