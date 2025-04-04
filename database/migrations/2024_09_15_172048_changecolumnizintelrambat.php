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
        Schema::table('hrd_izinterlambat', function (Blueprint $table) {
            $table->dropColumn('jam_pulang');
            $table->dateTime('jam_terlambat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hrd_izinterlambat', function (Blueprint $table) {
            //
        });
    }
};
