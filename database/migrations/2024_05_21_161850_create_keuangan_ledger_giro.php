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
        Schema::create('keuangan_ledger_giro', function (Blueprint $table) {
            $table->char('no_bukti', 12)->unique();
            $table->char('kode_giro', 10)->unique();
            $table->foreign('kode_giro')->references('kode_giro')->on('marketing_penjualan_giro')
                ->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('no_bukti')->references('no_bukti')->on('keuangan_ledger')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_ledger_giro');
    }
};
