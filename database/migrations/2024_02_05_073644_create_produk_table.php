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
        Schema::create('produk', function (Blueprint $table) {
            $table->char('kode_produk', 6)->primary();
            $table->string('nama_produk', 30);
            $table->string('satuan', 4);
            $table->smallInteger('isi_pcs_dus');
            $table->smallInteger('isi_pack_dus');
            $table->smallInteger('isi_pcs_pack');
            $table->char('kode_kategori_produk', 3);
            $table->char('kode_jenis_produk', 3);
            $table->char('kode_kategori_komisi', 3);
            $table->char('status', 1);
            $table->smallInteger('urutan')->nullable();
            $table->char('kode_sku', 3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
