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
        Schema::create('hrd_penialaian', function (Blueprint $table) {
            $table->char('kode_penilaian', 9)->primary();
            $table->date('tanggal');
            $table->char('nik', 9);
            $table->date('kontrak_dari');
            $table->date('kontrak_sampai');
            $table->char('kode_perusahaan', 2);
            $table->char('kode_cabang', 3);
            $table->char('kode_dept', 3);
            $table->char('kode_jabatan', 3);
            $table->char('kode_doc', 1);
            $table->smallInteger('sid');
            $table->smallInteger('sakit');
            $table->smallInteger('izin');
            $table->smallInteger('alfa');
            $table->char('masa_kontrak', 2);
            $table->text('rekomendasi');
            $table->text('evaluasi');
            $table->char('status', 1);
            $table->char('status_pemutihan', 1);
            $table->foreign('kode_cabang')->references('kode_cabang')->on('cabang')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('nik')->references('nik')->on('hrd_karyawan')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_dept')->references('kode_dept')->on('hrd_departemen')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_jabatan')->references('kode_jabatan')->on('hrd_jabatan')->restrictOnDelete()->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrd_penialaian');
    }
};
