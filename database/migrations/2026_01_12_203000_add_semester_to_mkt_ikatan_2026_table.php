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
        Schema::table('mkt_ikatan_2026', function (Blueprint $table) {
            $table->integer('semester')->after('periode_sampai')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mkt_ikatan_2026', function (Blueprint $table) {
            $table->dropColumn('semester');
        });
    }
};
