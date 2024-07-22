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
        Schema::create('hrd_lembur_disposisi', function (Blueprint $table) {
            $table->char('kode_disposisi', 16)->primary();
            $table->char('kode_lembur', 7);
            $table->bigInteger('id_pengirim');
            $table->bigInteger('id_penerima');
            $table->char('status', 1);
            $table->foreign('kode_lembur')->references('kode_lembur')->on('hrd_lembur')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrd_lembur_disposisi');
    }
};
