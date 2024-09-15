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
        Schema::table('hrd_izinkeluar', function (Blueprint $table) {
            $table->char('keperluan', 1)->after('jam_keluar');
            $table->bigInteger('id_user')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hrd_izinkeluar', function (Blueprint $table) {
            $table->dropColumn('keperluan');
        });
    }
};
