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
        Schema::create('gudang_cabang_mutasi', function (Blueprint $table) {
            $table->char('no_mutasi', 18)->primary();
            $table->date('tanggal');
            $table->date('tanggal_kirim')->nullable();
            $table->char('no_dpb', 10)->nullable();
            $table->char('no_surat_jalan', 25)->nullable();
            $table->char('kode_cabang', 3);
            $table->char('kondisi', 1);
            $table->char('in_out_good', 1)->nullable();
            $table->char('in_out_bad', 1)->nullable();
            $table->char('jenis_mutasi', 2);
            $table->string('keterangan')->nullable();
            $table->bigInteger('id_user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gudang_cabang_mutasi');
    }
};
