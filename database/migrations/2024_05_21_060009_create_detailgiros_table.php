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
        Schema::create('marketing_penjualan_giro_detail', function (Blueprint $table) {
            $table->char('kode_giro', 10);
            $table->char('no_faktur', 13);
            $table->integer('jumlah');
            $table->timestamps();
            $table->foreign('kode_giro')->references('kode_giro')->on('marketing_penjualan_giro')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('no_faktur')->references('no_faktur')->on('marketing_penjualan')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_penjualan_giro_detail');
    }
};
