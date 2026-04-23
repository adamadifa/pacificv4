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
        if (!Schema::hasColumn('marketing_penjualan_historibayar', 'status_pajak_hb')) {
            Schema::table('marketing_penjualan_historibayar', function (Blueprint $table) {
                $table->tinyInteger('status_pajak_hb')->default(0)->after('id_user');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_penjualan_historibayar', function (Blueprint $table) {
            $table->dropColumn('status_pajak_hb');
        });
    }
};
