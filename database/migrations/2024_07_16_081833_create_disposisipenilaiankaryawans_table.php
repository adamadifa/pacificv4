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
        Schema::create('hrd_penilaian_disposisi', function (Blueprint $table) {
            $table->char('kode_disposisi', 16)->primary();
            $table->char('kode_penilaian', 9);
            $table->bigInteger('id_pengirim');
            $table->bigInteger('id_penerima');
            $table->char('status', 1);
            $table->timestamps();
            $table->foreign('kode_penilaian')->references('kode_penilaian')->on('hrd_penilaian')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrd_penilaian_disposisi');
    }
};
