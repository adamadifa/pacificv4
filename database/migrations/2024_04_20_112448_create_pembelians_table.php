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
        Schema::create('pembelian', function (Blueprint $table) {
            $table->char('no_bukti', 17)->primary();
            $table->date('tanggal');
            $table->char('kode_supplier', 6);
            $table->char('kode_asal_pengajuan', 3);
            $table->char('kode_akun', 6);
            $table->char('ppn', 1);
            $table->string('no_fak_pajak', 30)->nullable();
            $table->date('tanggal_jatuh_tempo');
            $table->char('jenis_transaksi', 1);
            $table->char('kode_ref_tunai', 11)->nullable();
            $table->char('kategori_transaksi', 3)->nullable();
            $table->bigInteger('id_user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian');
    }
};
