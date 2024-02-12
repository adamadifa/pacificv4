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
        Schema::create('hrd_karyawan', function (Blueprint $table) {
            $table->char('nik', 9)->primary();
            $table->string('no_ktp', 16);
            $table->string('nama_karyawan', 100);
            $table->date('tanggal_masuk');
            $table->char('kode_dept', 3);
            $table->char('kode_jabatan', 3);
            $table->char('kode_cabang', 3);
            $table->char('kode_perusahaan', 2);
            $table->char('kode_klasifikasi', 3);
            $table->string('tempat_lahir', 20);
            $table->date('tanggal_lahir');
            $table->string('alamat');
            $table->string('no_hp', 15);
            $table->string('pendidikan_terakhir', 4);
            $table->char('kode_group', 3);
            $table->char('jenis_kelamin', 1);
            $table->char('kode_status_kawin', 2);
            $table->char('status_karyawan', 1);
            $table->string('foto');
            $table->char('kode_jadwal', 5);
            $table->smallInteger('pin');
            $table->date('tanggal_nonaktif');
            $table->date('tanggal_off_gaji');
            $table->char('lock_location', 1);
            $table->char('status_aktif_karyawan', 1);
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrd_karyawan');
    }
};
