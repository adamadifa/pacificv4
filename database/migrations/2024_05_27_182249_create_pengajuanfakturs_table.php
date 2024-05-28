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
        Schema::create('marketing_ajuan_faktur', function (Blueprint $table) {
            $table->char('no_pengajuan', 13)->primary();
            $table->date('tanggal');
            $table->char('kode_pelanggan', 13);
            $table->smallInteger('jumlah_faktur');
            $table->char('siklus_pembayaran', 1)->default(0);
            $table->char('status')->default(0);
            $table->string('keterangan')->nullable();
            $table->foreign('kode_pelanggan')->references('kode_pelanggan')->on('pelanggan')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_ajuan_faktur');
    }
};
