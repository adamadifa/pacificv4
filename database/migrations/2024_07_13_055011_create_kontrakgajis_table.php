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
        Schema::create('hrd_kontrak_gaji', function (Blueprint $table) {
            $table->char('no_kontrak', 8)->unique();
            $table->char('kode_gaji', 7)->unique();
            $table->foreign('no_kontrak')->references('no_kontrak')->on('hrd_kontrak')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_gaji')->references('kode_gaji')->on('hrd_gaji')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrd_kontrak_gaji');
    }
};
