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
        Schema::create('marketing_program_pencairan', function (Blueprint $table) {
            $table->char('kode_pencairan', 11)->primary();
            $table->date('tanggal');
            $table->char('kode_jenis_program', 2);
            $table->char('kode_program', 11);
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_program_pencairan');
    }
};
