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
        Schema::create('maintenance_saldoawal_bahanbakar_detail', function (Blueprint $table) {
            $table->char('kode_saldo_awal', 8);
            $table->char('kode_barang', 7);
            $table->double('jumlah');
            $table->double('harga');
            $table->foreign('kode_saldo_awal')->references('kode_saldo_awal')
                ->on('maintenance_saldoawal_bahanbakar')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('kode_barang')->references('kode_barang')
                ->on('pembelian_barang')
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
        Schema::dropIfExists('maintenance_saldoawal_bahanbakar_detail');
    }
};
