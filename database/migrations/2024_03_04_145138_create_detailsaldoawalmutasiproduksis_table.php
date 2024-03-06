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
        Schema::create('produksi_mutasi_saldoawal_detail', function (Blueprint $table) {
            $table->char('kode_saldo_awal', 8);
            $table->char('kode_produk', 6);
            $table->integer('jumlah');
            $table->foreign('kode_saldo_awal')->references('kode_saldo_awal')
                ->on('produksi_mutasi_saldoawal')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('kode_produk')->references('kode_produk')
                ->on('produk')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produksi_mutasi_saldoawal_detail');
    }
};
