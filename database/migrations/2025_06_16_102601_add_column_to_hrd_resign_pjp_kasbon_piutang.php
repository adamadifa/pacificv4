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
        Schema::table('hrd_resign', function (Blueprint $table) {
            $table->boolean('pjp')->default(false);
            $table->boolean('kasbon')->default(false);
            $table->boolean('piutang')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hrd_resign', function (Blueprint $table) {
            $table->dropColumn('pjp');
            $table->dropColumn('kasbon');
            $table->dropColumn('piutang');
        });
    }
};
