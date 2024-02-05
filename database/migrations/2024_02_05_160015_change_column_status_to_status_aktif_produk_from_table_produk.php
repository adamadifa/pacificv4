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
        Schema::table('produk', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('kode_kategori_komisi');
            $table->char('status_aktif_produk', 1)->after('kode_jenis_produk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropColumn('status_aktif_produk');
            $table->char('kode_kategori_komisi', 3)->after('kode_jenis_produk');
            $table->char('status', 1)->after('kode_kategori_produk')->after('kode_kategori_komisi');
        });
    }
};
