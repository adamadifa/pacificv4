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
        Schema::create('marketing_pencairan_simpanan', function (Blueprint $table) {
            $table->char('kode_pencairan', 11)->primary();
            $table->date('tanggal');
            $table->char('kode_pelanggan', 13);
            $table->integer('jumlah');
            $table->char('status', 1)->default(0);
            $table->char('kode_cabang', 3);
            $table->string('bukti')->nullable();
            $table->foreign('kode_pelanggan')->references('kode_pelanggan')->on('pelanggan')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('kode_cabang')->references('kode_cabang')->on('cabang')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_pencairan_simpanan');
    }
};
