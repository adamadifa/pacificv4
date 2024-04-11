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
        Schema::create('gudang_jadi_angkutan_suratjalan', function (Blueprint $table) {
            $table->char('no_dok', 15)->primary();
            $table->string('no_polisi', 10);
            $table->integer('tarif');
            $table->integer('tepung');
            $table->integer('bs');
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gudang_jadi_angkutan_suratjalan');
    }
};
