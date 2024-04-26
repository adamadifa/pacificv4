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
        Schema::create('gudang_logistik_saldoawal_detail', function (Blueprint $table) {
            $table->char('kode_saldo_awal', 12);
            $table->char('kode_barang', 7);
            $table->double('jumlah', 11, 2);
            $table->double('harga', 11, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gudang_logistik_saldoawal_detail');
    }
};
