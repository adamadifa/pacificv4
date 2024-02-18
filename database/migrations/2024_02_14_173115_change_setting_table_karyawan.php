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
        Schema::table('hrd_karyawan', function (Blueprint $table) {
            $table->string('tempat_lahir', 20)->nullable()->change();
            $table->date('tanggal_lahir')->nullable()->change();;
            $table->string('alamat')->nullable()->change();;
            $table->string('no_hp', 15)->nullable()->change();;
            $table->char('kode_status_kawin', 2)->nullable()->change();;
            $table->string('pendidikan_terakhir', 4)->nullable()->change();;
            $table->string('foto')->nullable()->change();;
            $table->char('kode_jadwal', 5)->nullable()->change();;
            $table->smallInteger('pin')->nullable()->change();;
            $table->date('tanggal_nonaktif')->nullable()->change();;
            $table->date('tanggal_off_gaji')->nullable()->change();;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hrd_karyawan', function (Blueprint $table) {
            //
        });
    }
};
