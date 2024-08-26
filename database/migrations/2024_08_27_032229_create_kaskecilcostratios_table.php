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
        Schema::create('keuangan_kaskecil_costratio', function (Blueprint $table) {
            $table->char('kode_cr', 10);
            $table->bigInteger('id')->unsigned();
            $table->foreign('id')->references('id')->on('keuangan_kaskecil')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_cr')->references('kode_cr')->on('accounting_costratio')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_kaskecil_costratio');
    }
};
