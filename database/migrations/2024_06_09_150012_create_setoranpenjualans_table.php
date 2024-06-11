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
        Schema::create('keuangan_setoranpenjualan', function (Blueprint $table) {
            $table->char('kode_setoran', 9)->primary();
            $table->date('tanggal');
            $table->char('kode_salesman', 7);
            $table->integer('lhp_tunai');
            $table->integer('lhp_tagihan');
            $table->integer('setoran_kertas');
            $table->integer('setoran_logam');
            $table->integer('setoran_lainnya');
            $table->integer('setoran_giro');
            $table->integer('setoran_transfer');
            $table->string('keterangan')->nullable();
            $table->integer('giro_to_cash');
            $table->integer('giro_to_transfer');
            $table->timestamps();
            $table->foreign('kode_salesman')->references('kode_salesman')->on('salesman')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_setoranpenjualan');
    }
};
