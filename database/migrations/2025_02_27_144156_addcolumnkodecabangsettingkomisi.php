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
        Schema::table('marketing_komisi_driverhelper_setting', function (Blueprint $table) {
            $table->char('kode_cabang', 3);
            $table->foreign('kode_cabang')->references('kode_cabang')->on('cabang')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_komisi_driverhelper_setting', function (Blueprint $table) {
            //
        });
    }
};
