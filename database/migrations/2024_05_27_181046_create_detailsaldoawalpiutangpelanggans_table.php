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
        Schema::create('marketing_saldoawal_piutang_detail', function (Blueprint $table) {
            $table->char('kode_saldo_awal', 8);
            $table->char('no_faktur', 13);
            $table->integer('jumlah');
            $table->foreign('kode_saldo_awal')->references('kode_saldo_awal')->on('marketing_saldoawal_piutang')->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('no_faktur')->references('no_faktur')->on('marketing_penjualan')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_saldoawal_piutang_detail');
    }
};
