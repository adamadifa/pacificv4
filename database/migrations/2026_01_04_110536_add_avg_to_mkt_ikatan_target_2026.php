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
        Schema::table('mkt_ikatan_target_2026', function (Blueprint $table) {
            $table->integer('avg')->default(0)->after('target_perbulan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mkt_ikatan_target_2026', function (Blueprint $table) {
            $table->dropColumn('avg');
        });
    }
};
