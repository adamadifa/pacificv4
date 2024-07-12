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
        Schema::create('ga_kendaraan_service_item', function (Blueprint $table) {
            $table->char('kode_item', 6)->primary();
            $table->string('nama_item');
            $table->string('jenis_item');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ga_kendaraan_service_item');
    }
};
