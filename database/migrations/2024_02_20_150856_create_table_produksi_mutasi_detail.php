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
        Schema::create('produksi_mutasi_detail', function (Blueprint $table) {
            $table->string('no_mutasi', 25);
            $table->char('kode_produk', 6);
            $table->char('shift', 1);
            $table->integer('jumlah');
            $table->foreign('no_mutasi')
                ->references('no_mutasi')
                ->on('produksi_mutasi')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('kode_produk')
                ->references('kode_produk')
                ->on('produk')
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
        Schema::dropIfExists('table_produksi_mutasi_detail');
    }
};
