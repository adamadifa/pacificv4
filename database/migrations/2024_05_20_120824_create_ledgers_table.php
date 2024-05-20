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
        Schema::create('keuangan_ledger', function (Blueprint $table) {
            $table->char('no_bukti', 12)->primary();
            $table->date('tanggal');
            $table->string('pelanggan');
            $table->char('kode_bank', 5);
            $table->char('kode_akun', 6);
            $table->string('keterangan');
            $table->bigInteger('jumlah');
            $table->char('debet_kredit');
            $table->char('kode_peruntukan', 2)->nullable();
            $table->string('keterangan_peruntukan')->nullable();
            $table->timestamps();
            $table->foreign('kode_akun')->references('kode_akun')->on('coa')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_ledger');
    }
};
