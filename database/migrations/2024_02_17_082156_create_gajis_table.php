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
        Schema::create('hrd_gaji', function (Blueprint $table) {
            $table->char('kode_gaji', 7)->primary();
            $table->char('nik', 10);
            $table->integer('gaji_pokok');
            $table->integer('t_jabatan');
            $table->integer('t_masakerja');
            $table->integer('t_tanggungjawab');
            $table->integer('t_makan');
            $table->integer('t_istri');
            $table->integer('t_skill');
            $table->integer('tanggal_berlaku');
            $table->char('no_kontrak', 8);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrd_gaji');
    }
};
