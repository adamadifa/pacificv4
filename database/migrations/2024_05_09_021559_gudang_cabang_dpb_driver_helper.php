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
        Schema::create('gudang_cabang_dpb_driverhelper', function (Blueprint $table) {
            $table->char('no_dpb', 10);
            $table->char('kode_driver_helper', 6);
            $table->char('kode_posisi', 1);
            $table->float('jumlah');
            $table->char('keterangan', 1)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gudang_cabang_dpb_driverhelper');
    }
};
