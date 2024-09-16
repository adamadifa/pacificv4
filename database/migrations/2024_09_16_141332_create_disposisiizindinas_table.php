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
        Schema::create('hrd_izindinas_disposisi', function (Blueprint $table) {
            $table->char('kode_disposisi', 16)->primary();
            $table->char('kode_izin_dinas', 12);
            $table->bigInteger('id_pengirim');
            $table->bigInteger('id_penerima');
            $table->char('status', 1);
            $table->foreign('kode_izin_dinas')->references('kode_izin_dinas')->on('hrd_izindinas')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrd_izindinas_disposisi');
    }
};
