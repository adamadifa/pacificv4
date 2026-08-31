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
        Schema::create('wa_komplain_pelanggans', function (Blueprint $table) {
            $table->id();
            $table->string('no_komplain', 50)->unique();
            $table->string('wa_number', 20);
            $table->string('nama_pelanggan', 100);
            $table->string('kode_pelanggan', 13)->nullable();
            $table->char('kode_cabang', 3)->nullable();
            $table->text('isi_komplain');
            $table->text('ringkasan_ai')->nullable();
            $table->string('kategori_ai', 50)->nullable();
            $table->enum('status', ['baru', 'diproses', 'selesai', 'ditolak'])->default('baru');
            $table->text('chat_history')->nullable();
            $table->unsignedBigInteger('ditangani_oleh')->nullable();
            $table->text('catatan_cs')->nullable();
            $table->date('tanggal_komplain');
            $table->timestamps();

            $table->foreign('kode_pelanggan')->references('kode_pelanggan')->on('pelanggan')->onDelete('set null');
            $table->foreign('kode_cabang')->references('kode_cabang')->on('cabang')->onDelete('set null');
            $table->foreign('ditangani_oleh')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wa_komplain_pelanggans');
    }
};
