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
        Schema::create('produk_harga', function (Blueprint $table) {
            $table->char('kode_harga', 7)->primary();
            $table->char('kode_produk', 6);
            $table->integer('harga_dus');
            $table->integer('harga_pack');
            $table->integer('harga_pcs');
            $table->integer('harga_retur_dus');
            $table->integer('harga_retur_pack');
            $table->integer('harga_retur_pcs');
            $table->char('status_aktif_harga', 1);
            $table->char('status_ppn', 2)->nullable(); //EX OR INC
            $table->char('status_promo', 1);
            $table->char('kode_kategori_salesman', 2);
            $table->char('kode_cabang', 3);
            $table->char('kode_pelanggan', 13)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_harga');
    }
};
