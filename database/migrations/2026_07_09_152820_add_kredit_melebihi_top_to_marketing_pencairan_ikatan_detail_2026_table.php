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
        Schema::table('marketing_pencairan_ikatan_detail_2026', function (Blueprint $table) {
            $table->integer('kredit_melebihi_top')->default(0)->after('realisasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_pencairan_ikatan_detail_2026', function (Blueprint $table) {
            $table->dropColumn('kredit_melebihi_top');
        });
    }
};
