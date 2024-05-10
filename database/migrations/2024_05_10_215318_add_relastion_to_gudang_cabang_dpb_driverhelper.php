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
        Schema::table('gudang_cabang_dpb_driverhelper', function (Blueprint $table) {
            $table->foreign('no_dpb')->references('no_dpb')->on('gudang_cabang_dpb')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_driver_helper')->references('kode_driver_helper')->on('driver_helper')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gudang_cabang_dpb_driverhelper', function (Blueprint $table) {
            //
        });
    }
};
