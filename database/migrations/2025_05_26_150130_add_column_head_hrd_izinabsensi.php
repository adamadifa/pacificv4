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
        Schema::table('hrd_izinabsen', function (Blueprint $table) {
            $table->char('head', 1)->after('keterangan_hrd')->default('0');
            $table->char('hrd', 1)->after('head')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hrd_izinabsen', function (Blueprint $table) {
            $table->dropColumn('head');
            $table->dropColumn('hrd');
        });
    }
};
