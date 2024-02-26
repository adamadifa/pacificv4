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
        Schema::create('produksi_mutasi_detail_temp', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('kode_produk', 6);
            $table->char('shift', 1);
            $table->integer('jumlah');
            $table->string('in_out', 3);
            $table->char('unit', 1)->nullable();
            $table->bigInteger('id_user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produksi_mutasi_detail_temp');
    }
};
