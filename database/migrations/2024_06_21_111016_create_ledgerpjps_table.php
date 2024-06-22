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
        Schema::create('keuangan_ledger_pjp', function (Blueprint $table) {
            $table->char('no_bukti', 12);
            $table->char('no_pinjaman', 8);
            $table->foreign('no_bukti')->references('no_bukti')->on('keuangan_ledger')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('no_pinjaman')->references('no_pinjaman')->on('keuangan_pjp')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledgerpjps');
    }
};
