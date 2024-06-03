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
        Schema::create('marketing_ajuan_limitkredit', function (Blueprint $table) {
            $table->char('no_pengajuan', 13)->primary();
            $table->date('tanggal');
            $table->char('kode_pelanggan', 13);
            $table->integer('limit_sebelumnya');
            $table->integer('omset_sebelumnya');
            $table->integer('jumlah');
            $table->integer('jumlah_rekomendasi');
            $table->smallInteger('ljt');
            $table->smallInteger('ljt_rekomendasi');
            $table->date('topup_terakhir');
            $table->smallInteger('lama_topup');
            $table->char('histori_transaksi', 4);
            $table->smallInteger('jml_faktur');
            $table->double('skor', 10, 2);
            $table->bigInteger('id_user');
            $table->foreign('kode_pelanggan')->references('kode_pelanggan')->on('pelanggan')->restrictOnDelete()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_ajuan_limitkredit');
    }
};
