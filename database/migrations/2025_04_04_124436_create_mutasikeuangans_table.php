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
        Schema::create('keuangan_mutasi', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->char('kode_bank', 5);
            $table->string('keterangan');
            $table->bigInteger('jumlah');
            $table->char('debet_kredit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_mutasi');
    }
};
