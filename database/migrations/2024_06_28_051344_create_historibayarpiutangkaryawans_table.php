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
        Schema::create('keuangan_piutangkaryawan_historibayar', function (Blueprint $table) {
            $table->char('no_bukti', 8)->primary();
            $table->date('tanggal');
            $table->char('no_pinjaman', 8);
            $table->char('jenis_bayar', 1);
            $table->integer('jumlah');
            $table->string('keterangan');
            $table->char('kode_potongan', 8)->nullable();
            $table->bigInteger('id_user');
            $table->foreign('no_pinjaman')->references('no_pinjaman')->on('keuangan_piutangkaryawan')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_piutangkaryawan_historibayar');
    }
};
