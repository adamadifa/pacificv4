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
        Schema::create('keuangan_kaskecil_klaim_detail', function (Blueprint $table) {
            $table->char('kode_klaim', 11);
            $table->bigInteger('id')->unsigned();
            $table->foreign('kode_klaim')->references('kode_klaim')->on('keuangan_kaskecil_klaim')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('id')->references('id')->on('keuangan_kaskecil')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_kaskecil_klaim_detail');
    }
};
