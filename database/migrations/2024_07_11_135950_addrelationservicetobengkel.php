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
        Schema::table('ga_kendaraan_service', function (Blueprint $table) {
            $table->foreign('kode_bengkel')->references('kode_bengkel')->on('ga_bengkel')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ga_kendaraan_service', function (Blueprint $table) {
            //
        });
    }
};
