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
        Schema::create('gudang_jadi_angkutan_kontrabon_detail', function (Blueprint $table) {
            $table->char('no_kontrabon', 15);
            $table->char('no_dok', 15);
            $table->foreign('no_kontrabon')->references('no_kontrabon')->on('gudang_jadi_angkutan_kontrabon')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('no_dok')->references('no_dok')->on('gudang_jadi_angkutan_suratjalan')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gudang_jadi_angkutan_kontrabon_detail');
    }
};
