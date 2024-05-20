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
        Schema::create('marketing_penjualan_transfer', function (Blueprint $table) {
            $table->char('kode_transfer', 10)->primary();
            $table->char('tanggal');
            $table->char('no_faktur', 13);
            $table->string('bank_pengirim');
            $table->date('jatuh_tempo');
            $table->integer('jumlah');
            $table->char('status');
            $table->date('tanggal_ditolak')->nullable();
            $table->char('kode_group_transfer', 23);
            $table->char('kode_salesman', 7);
            $table->string('keterangan')->nullable();
            $table->smallInteger('omset_bulan')->nullable();
            $table->char('omset_tahun')->nullable();
            $table->timestamps();
            $table->foreign('no_faktur')->references('no_faktur')->on('marketing_penjualan')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_salesman')->references('kode_salesman')->on('salesman')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_penjualan_transfer');
    }
};
