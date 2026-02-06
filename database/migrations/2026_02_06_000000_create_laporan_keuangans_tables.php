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
        Schema::create('laporan_keuangan', function (Blueprint $table) {
            $table->string('kode_lk')->primary(); // LK012026
            $table->integer('bulan');
            $table->integer('tahun');
            $table->string('kategori', 2); // NC = Neraca, LB = Laba Rugi
            $table->bigInteger('user_id')->nullable();
            $table->integer('kode_cabang')->nullable();
            $table->timestamps();
        });

        Schema::create('laporan_keuangan_detail', function (Blueprint $table) {
            $table->id();
            $table->string('kode_lk');
            $table->string('kode_akun');
            $table->double('jumlah');
            $table->timestamps();

            $table->foreign('kode_lk')->references('kode_lk')->on('laporan_keuangan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_keuangan_detail');
        Schema::dropIfExists('laporan_keuangan');
    }
};
