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
        Schema::create('ga_kendaraan_service_detail', function (Blueprint $table) {
            $table->char('kode_service', 10);
            $table->char('kode_item', 6);
            $table->integer('jumlah');
            $table->integer('harga');
            $table->foreign('kode_service')->references('kode_service')->on('ga_kendaraan_service')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_item')->references('kode_item')->on('ga_kendaraan_service_item')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ga_kendaraan_service_detail');
    }
};
