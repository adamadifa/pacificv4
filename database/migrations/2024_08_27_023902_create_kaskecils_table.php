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
        Schema::create('keuangan_kaskecil', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->char('no_bukti', 12);
            $table->date('tanggal');
            $table->string('keterangan')->nullable();
            $table->integer('jumlah');
            $table->char('debet_kredit', 1);
            $table->char('kode_akun', 6);
            $table->char('kode_cabang', 3);
            $table->char('kode_peruntukan', 3);
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
        Schema::dropIfExists('kaskecils');
    }
};
