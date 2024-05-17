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
        Schema::create('marketing_komisi_target_detail', function (Blueprint $table) {
            $table->char('kode_target', 9);
            $table->char('kode_produk', 6);
            $table->integer('jumlah');
            $table->foreign('kode_target')->references('kode_target')->on('marketing_komisi_target')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_komisi_target_detail');
    }
};
