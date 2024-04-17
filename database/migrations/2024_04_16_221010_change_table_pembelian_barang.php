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
        Schema::table('pembelian_barang', function (Blueprint $table) {
            $table->dropForeign('pembelian_barang_kode_dept_foreign');
            $table->dropColumn('kode_dept');
            $table->char('kode_group', 3);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembelian_barang', function (Blueprint $table) {
            //
        });
    }
};
