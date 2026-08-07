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
        Schema::table('marketing_penjualan', function (Blueprint $table) {
            $table->char('kode_pkp', 3)->nullable()->default(null)->after('status_pajak_faktur');
            $table->foreign('kode_pkp')->references('kode_cabang')->on('cabang')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_penjualan', function (Blueprint $table) {
            $table->dropForeign(['kode_pkp']);
            $table->dropColumn('kode_pkp');
        });
    }
};
