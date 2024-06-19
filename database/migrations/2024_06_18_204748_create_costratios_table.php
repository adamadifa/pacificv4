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
        Schema::create('accounting_costratio', function (Blueprint $table) {
            $table->char('kode_cr', 10)->primary();
            $table->date('tanggal');
            $table->char('kode_akun', 6);
            $table->string('keterangan');
            $table->char('kode_cabang', 3);
            $table->smallInteger('kode_sumber');
            $table->integer('jumlah');
            $table->foreign('kode_akun')->references('kode_akun')->on('coa')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_cabang')->references('kode_cabang')->on('cabang')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_costratio');
    }
};
