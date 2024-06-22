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
        Schema::create('keuangan_pjp_historibayar', function (Blueprint $table) {
            $table->char('no_bukti', 8)->primary();
            $table->date('tanggal');
            $table->char('no_pinjaman', 10);
            $table->integer('jumlah');
            $table->smallInteger('cicilan_ke');
            $table->string('keterangan')->nullable();
            $table->char('kode_potongan', 8)->nullable();
            $table->bigInteger('id_user');
            $table->foreign('no_pinjaman')->references('no_pinjaman')->on('keuangan_pjp')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_pjp_historibayar');
    }
};
