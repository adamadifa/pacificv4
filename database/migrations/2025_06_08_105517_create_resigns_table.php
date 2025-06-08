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
        Schema::create('hrd_resign', function (Blueprint $table) {
            $table->char('kode_resign', 8)->primary();
            $table->char('nik', 10);
            $table->date('tanggal');
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
        Schema::dropIfExists('hrd_resign');
    }
};
