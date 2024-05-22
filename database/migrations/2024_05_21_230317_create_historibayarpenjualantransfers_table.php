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
        Schema::create('marketing_penjualan_historibayar_transfer', function (Blueprint $table) {
            $table->char('no_bukti', 14)->unique();
            $table->char('kode_transfer', 10);
            $table->foreign('no_bukti')->references('no_bukti')->on('marketing_penjualan_historibayar')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('kode_transfer')->references('kode_transfer')->on('marketing_penjualan_transfer')
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
        Schema::dropIfExists('marketing_penjualan_historibayar_transfer');
    }
};
