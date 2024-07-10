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
        Schema::create('accounting_jurnalumum', function (Blueprint $table) {
            $table->char('kode_ju', 9)->primary();
            $table->date('tanggal');
            $table->string('keterangan');
            $table->double('jumlah', 13, 2);
            $table->char('debet_kredit', 1);
            $table->char('kode_akun', 6);
            $table->char('kode_dept', 3);
            $table->char('kode_pruntukan', 2);
            $table->char('kode_cabang', 3)->nullable();
            $table->bigInteger('id_user');
            $table->foreign('kode_akun')->references('kode_akun')->on('coa')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_dept')->references('kode_dept')->on('hrd_departemen')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_jurnalumum');
    }
};
