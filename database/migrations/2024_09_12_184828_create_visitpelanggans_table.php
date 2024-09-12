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
        Schema::create('worksheetom_visitpelanggan', function (Blueprint $table) {
            $table->char('kode_visit', 13)->primary();
            $table->date('tanggal');
            $table->char('no_faktur', 13);
            $table->string('hasil_konfirmasi');
            $table->string('note');
            $table->string('saran');
            $table->string('act_om');
            $table->foreign('no_faktur')->references('no_faktur')->on('marketing_penjualan')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worksheetom_visitpelanggan');
    }
};
