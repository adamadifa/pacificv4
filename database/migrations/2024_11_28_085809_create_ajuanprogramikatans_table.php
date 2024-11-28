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
        Schema::create('marketing_program_ikatan', function (Blueprint $table) {
            $table->char('no_pengajuan', 11)->primary();
            $table->string('nomor_dokumen');
            $table->date('tanggal');
            $table->char('kode_program', 7);
            $table->char('kode_cabang', 3);
            $table->date('periode_dari');
            $table->date('periode_sampai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ajuanprogramikatans');
    }
};
