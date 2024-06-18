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
        Schema::create('keuangan_ajuantransferdana', function (Blueprint $table) {
            $table->char('no_pengajuan', 13)->primary();
            $table->date('tanggal');
            $table->string('nama');
            $table->string('nama_bank');
            $table->string('no_rekening');
            $table->integer('jumlah');
            $table->string('keterangan');
            $table->char('kode_cabang', 3);
            $table->char('status', 1);
            $table->bigInteger('id_user');
            $table->text('bukti');
            $table->foreign('kode_cabang')->references('kode_cabang')->on('cabang')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_ajuantransferdana');
    }
};
