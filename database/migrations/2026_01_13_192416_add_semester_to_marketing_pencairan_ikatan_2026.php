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
        Schema::table('marketing_pencairan_ikatan_2026', function (Blueprint $table) {
            $table->integer('semester')->after('kode_cabang')->nullable();
            $table->integer('bulan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_pencairan_ikatan_2026', function (Blueprint $table) {
            $table->dropColumn('semester');
            $table->integer('bulan')->nullable(false)->change();
        });
    }
};
