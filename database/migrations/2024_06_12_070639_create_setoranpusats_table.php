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
        Schema::create('keuangan_setoranpusat', function (Blueprint $table) {
            $table->char('kode_setoran', 11)->primary();
            $table->date('tanggal');
            $table->char('kode_cabang', 3);
            $table->integer('setoran_kertas')->default(0);
            $table->integer('setoran_logam')->default(0);
            $table->integer('setoran_giro')->default(0);
            $table->integer('setoran_transfer')->default(0);
            $table->string('keterangan');
            $table->char('status', 1);
            $table->smallInteger('omset_bulan')->nullable();
            $table->char('omset_tahun', 4)->nullable();
            $table->foreign('kode_cabang')->references('kode_cabang')->on('cabang')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_setoranpusat');
    }
};
