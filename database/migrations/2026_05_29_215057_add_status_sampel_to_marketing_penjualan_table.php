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
            $table->tinyInteger('status_sampel')->default(0)->after('keterangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_penjualan', function (Blueprint $table) {
            $table->dropColumn('status_sampel');
        });
    }
};
