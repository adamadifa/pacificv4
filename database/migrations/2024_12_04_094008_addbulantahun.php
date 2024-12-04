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
        Schema::table('marketing_pencairan_ikatan', function (Blueprint $table) {
            $table->smallInteger('bulan')->after('keterangan');
            $table->char('tahun', 4)->after('bulan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_pencairan_ikatan', function (Blueprint $table) {
            //
        });
    }
};
