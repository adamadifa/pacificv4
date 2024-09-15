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
        Schema::table('hrd_izinpulang', function (Blueprint $table) {
            $table->char('direktur', 1)->after('status');
            $table->bigInteger('id_user')->after('direktur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hrd_izinpulang', function (Blueprint $table) {
            $table->dropColumn('direktur');
            $table->dropColumn('id');
        });
    }
};
