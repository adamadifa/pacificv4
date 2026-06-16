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
        Schema::create('dokumen_opname', function (Blueprint $table) {
            $table->char('kode_dokumen_opname', 9)->primary();
            $table->char('kode_cabang', 3);
            $table->smallInteger('bulan');
            $table->char('tahun', 4);
            $table->date('tanggal');
            $table->string('file_dokumen');
            $table->bigInteger('id_user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_opname');
    }
};
