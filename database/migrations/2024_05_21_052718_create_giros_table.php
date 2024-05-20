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
        Schema::create('marketing_penjualan_giro', function (Blueprint $table) {
            $table->char('kode_giro')->primary();
            $table->date('tanggal');
            $table->string('no_giro');
            $table->char('kode_pelanggan', 13);
            $table->char('kode_salesman', 7);
            $table->string('bank_pengirim');
            $table->date('jatuh_tempo');
            $table->char('status', 1);
            $table->date('tanggal_ditolak')->nullable();
            $table->string('keterangan')->nullable();
            $table->char('penggantian', 1)->nullable();
            $table->smallInteger('omset_bulan')->nullable();
            $table->char('omset_tahun', 4)->nullable();
            $table->timestamps();
            $table->foreign('kode_salesman')->references('kode_salesman')->on('salesman')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_pelanggan')->references('kode_pelanggan')->on('pelanggan')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_penjualan_giro');
    }
};
