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
        Schema::table('marketing_penjualan_transfer', function (Blueprint $table) {
            $table->dropForeign('marketing_penjualan_transfer_no_faktur_foreign');
            $table->dropColumn('no_faktur');
            $table->dropColumn('jumlah');
            $table->dropColumn('kode_group_transfer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_penjualan_transfer', function (Blueprint $table) {
            //
        });
    }
};
