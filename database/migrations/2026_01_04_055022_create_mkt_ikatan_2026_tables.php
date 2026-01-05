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
        Schema::create('mkt_ikatan_2026', function (Blueprint $table) {
            $table->char('no_pengajuan', 11)->primary();
            $table->string('nomor_dokumen');
            $table->date('tanggal');
            $table->char('kode_program', 7);
            $table->char('kode_cabang', 3);
            $table->date('periode_dari');
            $table->date('periode_sampai');
            $table->smallInteger('om')->nullable();
            $table->smallInteger('rsm')->nullable();
            $table->smallInteger('gm')->nullable();
            $table->smallInteger('direktur')->nullable();
            $table->char('status', 1)->default(0);
            $table->timestamps();
        });

        Schema::create('mkt_ikatan_detail_2026', function (Blueprint $table) {
            $table->char('no_pengajuan', 11);
            $table->char('kode_pelanggan', 13);
            $table->integer('qty_avg');
            $table->integer('top');
            $table->smallInteger('periode_pencairan');
            $table->integer('qty_target');
            $table->integer('reward');
            $table->char('metode_pembayaran', 2);
            $table->integer('budget_smm')->default(0);
            $table->integer('budget_rsm')->default(0);
            $table->integer('budget_gm')->default(0);
            $table->char('status', 1)->default('1');
            $table->string('file_doc')->nullable();
            $table->char('tipe_reward', 1)->default('1');
            $table->timestamps();

            $table->foreign('no_pengajuan')->references('no_pengajuan')->on('mkt_ikatan_2026')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_pelanggan')->references('kode_pelanggan')->on('pelanggan')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mkt_ikatan_detail_2026');
        Schema::dropIfExists('mkt_ikatan_2026');
    }
};
