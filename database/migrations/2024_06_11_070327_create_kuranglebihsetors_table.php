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
        Schema::create('keuangan_kuranglebihsetor', function (Blueprint $table) {
            $table->char('kode_kl', 9)->primary();
            $table->date('tanggal');
            $table->char('kode_salesman', 7);
            $table->integer('uang_kertas');
            $table->integer('uang_logam');
            $table->char('jenis_bayar', 1);
            $table->string('keterangan')->nullable();
            $table->foreign('kode_salesman')->references('kode_salesman')->on('salesman')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_kuranglebihsetor');
    }
};
