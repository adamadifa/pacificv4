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
        Schema::create('produksi_permintaan_detail', function (Blueprint $table) {
            $table->char('no_permintaan', 6);
            $table->char('kode_produk', 6);
            $table->integer('oman_marketing');
            $table->integer('stok_gudang');
            $table->integer('buffer_stok');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produksi_permintaan_detail');
    }
};
