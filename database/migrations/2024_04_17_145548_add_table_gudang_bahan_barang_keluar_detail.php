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
        Schema::create('gudang_bahan_barang_keluar_detail', function (Blueprint $table) {
            $table->char('no_bukti',17);
            $table->char('kode_barang',7);
            $table->double('qty_lebih', 13, 2);
            $table->double('qty_berat', 13, 2);
            $table->double('qty_unit', 13, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('gudang_bahan_barang_keluar_detail');
    }
};
