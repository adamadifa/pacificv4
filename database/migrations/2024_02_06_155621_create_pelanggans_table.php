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
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->char('kode_pelanggan', 13)->primary();
            $table->date('tanggal_register');
            $table->char('nik', 16)->nullable();
            $table->char('no_kk', 16)->nullable();
            $table->string('nama_pelanggan', 50);
            $table->date('tanggal_lahir')->nullable();
            $table->string('alamat_pelanggan')->nullable();
            $table->string('alamat_toko')->nullable();
            $table->string('no_hp_pelanggan', 13)->nullable();
            $table->char('kode_wilayah', 10)->nullable();
            $table->string('hari', 100)->nullable();
            $table->string('latitude', 30)->nullable();
            $table->string('longitude', 30)->nullable();
            $table->char('status_lokasi', 1)->nullable();
            $table->smallInteger('ljt')->nullable();
            $table->char('status_outlet', 2)->nullable();
            $table->char('type_outlet', 2)->nullable();
            $table->char('cara_pembayaran', 2)->nullable();
            $table->char('kepemilikan', 2)->nullable();
            $table->char('lama_berjualan', 4)->nullable();
            $table->char('jaminan', 1)->nullable();
            $table->bigInteger('omset_toko')->nullable();
            $table->string('foto', 20)->nullable();
            $table->bigInteger('limit_pelanggan')->nullable();
            $table->char('kode_salesman', 7);
            $table->char('kode_cabang', 3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};
