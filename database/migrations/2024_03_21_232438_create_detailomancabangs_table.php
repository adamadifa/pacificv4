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
        Schema::create('marketing_oman_cabang_detail', function (Blueprint $table) {
            $table->char('kode_oman', 9);
            $table->char('kode_produk', 6);
            $table->smallInteger('minggu_ke');
            $table->date('dari');
            $table->date('sampai');
            $table->integer('jumlah');
            $table->foreign('kode_oman')->references('kode_oman')->on('marketing_oman_cabang')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('kode_produk')->references('kode_produk')->on('produk')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_oman_cabang_detail');
    }
};
