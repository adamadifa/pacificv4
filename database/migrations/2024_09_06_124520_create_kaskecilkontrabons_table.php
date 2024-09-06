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
        Schema::create('keuangan_kaskecil_kontrabon', function (Blueprint $table) {
            $table->bigInteger('id_kaskecil')->unsigned();
            $table->char('no_kontrabon', 13);
            $table->foreign('id_kaskecil')->references('id')->on('keuangan_kaskecil')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('no_kontrabon')->references('no_kontrabon')->on('pembelian_historibayar')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_kaskecil_kontrabon');
    }
};
