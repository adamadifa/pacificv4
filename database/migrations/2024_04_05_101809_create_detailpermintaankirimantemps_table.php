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
        Schema::create('marketing_permintaan_kiriman_detail_temp', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('kode_produk', 6);
            $table->integer('jumlah');
            $table->bigInteger('id_user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_permintaan_kiriman_detail_temp');
    }
};
