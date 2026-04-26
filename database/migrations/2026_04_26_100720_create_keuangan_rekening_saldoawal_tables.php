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
        Schema::create('keuangan_rekening_saldoawal', function (Blueprint $table) {
            $table->string('kode_saldo_awal')->primary();
            $table->date('tanggal');
            $table->timestamps();
        });

        Schema::create('keuangan_rekening_saldoawal_detail', function (Blueprint $table) {
            $table->id();
            $table->string('kode_saldo_awal');
            $table->char('kode_bank', 5);
            $table->double('jumlah', 15, 2);
            $table->timestamps();

            $table->foreign('kode_saldo_awal')
                ->references('kode_saldo_awal')
                ->on('keuangan_rekening_saldoawal')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('kode_bank')
                ->references('kode_bank')
                ->on('bank')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_rekening_saldoawal_detail');
        Schema::dropIfExists('keuangan_rekening_saldoawal');
    }
};
