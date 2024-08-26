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
        Schema::create('keuangan_kaskecil_klaim', function (Blueprint $table) {
            $table->char('kode_klaim', 11)->primary();
            $table->date('tanggal');
            $table->string('keterangan');
            $table->char('status', 1);
            $table->integer('saldo_akhir');
            $table->char('kode_cabang', 3);
            $table->foreign('kode_cabang')->references('kode_cabang')->on('cabang')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kaskecilklaims');
    }
};
