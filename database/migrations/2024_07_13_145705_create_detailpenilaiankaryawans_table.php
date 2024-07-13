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
        Schema::create('hrd_penilaian_detail', function (Blueprint $table) {
            $table->char('kode_penilaian', 9);
            $table->char('kode_item', 3);
            $table->smallInteger('nilai');
            $table->foreign('kode_penilaian')->references('kode_penilaian')->on('hrd_penilaian')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_item')->references('kode_item')->on('hrd_penilaian_item')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrd_penilaian_detail');
    }
};
