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
        Schema::create('hrd_suratperingatan', function (Blueprint $table) {
            $table->char('no_sp', 7)->primary();
            $table->char('nik', 10);
            $table->date('tanggal');
            $table->date('dari');
            $table->date('sampai');
            $table->char('jenis_sp', 3);
            $table->string('keterangan');
            $table->foreign('nik')->references('nik')->on('hrd_karyawan')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrd_suratperingatan');
    }
};
