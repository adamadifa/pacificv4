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
        Schema::create('keuangan_pjp_rencanacicilan', function (Blueprint $table) {
            $table->char('no_pinjaman', 10);
            $table->smallInteger('cicilan_ke');
            $table->smallInteger('bulan');
            $table->char('tahun', 4);
            $table->integer('jumlah');
            $table->integer('bayar')->default(0);
            $table->char('kode_potongan', 8)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_pjp_rencanacicilan');
    }
};
