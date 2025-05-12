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
        Schema::create('marketing_program_ikatan_enambulan', function (Blueprint $table) {
            $table->char('no_pengajuan', 11)->primary();
            $table->date('tanggal');
            $table->char('kode_program', 7);
            $table->char('kode_cabang', 3);
            $table->date('periode_dari');
            $table->date('periode_sampai');
            $table->string('status');
            $table->string('keterangan')->nullable();
            $table->smallInteger('om')->nullable();
            $table->smallInteger('rsm')->nullable();
            $table->smallInteger('gm')->nullable();
            $table->smallInteger('direktur')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_program_ikatan_enambulan');
    }
};
