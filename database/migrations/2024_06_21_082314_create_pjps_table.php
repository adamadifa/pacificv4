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
        Schema::create('keuangan_pjp', function (Blueprint $table) {
            $table->char('no_pinjaman', 8)->primary();
            $table->date('tanggal');
            $table->char('nik', 10);
            $table->char('status_karyawan', 1);
            $table->date('akhir_kontrak')->nullable();
            $table->integer('gapok_tunjangan');
            $table->smallInteger('tenor_max');
            $table->smallInteger('angsuran_max');
            $table->integer('jmk');
            $table->integer('jmk_sudahbayar');
            $table->integer('plafon_max');
            $table->integer('jumlah_pinjaman');
            $table->smallInteger('angsuran');
            $table->date('mulai_cicilan');
            $table->bigInteger('id_user');
            $table->char('status', 1)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_pjp');
    }
};
