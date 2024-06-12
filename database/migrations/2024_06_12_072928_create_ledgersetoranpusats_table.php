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
        Schema::create('keuangan_ledger_setoranpusat', function (Blueprint $table) {
            $table->char('no_bukti', 12);
            $table->char('kode_setoran', 11);
            $table->foreign('no_bukti')->references('no_bukti')->on('keuangan_ledger')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_setoran')->references('kode_setoran')->on('keuangan_setoranpusat')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_ledger_setoranpusat');
    }
};
