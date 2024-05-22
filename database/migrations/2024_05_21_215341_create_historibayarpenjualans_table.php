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
        Schema::create('marketing_penjualan_historibayar', function (Blueprint $table) {
            $table->char('no_bukti', 14)->primary();
            $table->char('no_faktur', 13);
            $table->date('tanggal');
            $table->char('jenis_bayar', 2);
            $table->integer('jumlah');
            $table->char('voucher', 1)->default(0);
            $table->string('jenis_voucher', 1)->default(0);
            $table->char('kode_salesman', 7);
            $table->char('kode_lhp', 12)->nullable();
            $table->bigInteger('id_user')->unsigned();
            $table->timestamps();
            $table->foreign('no_faktur')->references('no_faktur')->on('marketing_penjualan')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_salesman')->references('kode_salesman')->on('salesman')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_penjualan_historibayar');
    }
};
