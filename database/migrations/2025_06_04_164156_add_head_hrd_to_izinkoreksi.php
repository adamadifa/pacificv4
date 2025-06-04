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
        Schema::table('hrd_izinkoreksi', function (Blueprint $table) {
            $table->integer('head')->default(0);
            $table->integer('hrd')->default(0);
            $table->integer('forward_to_direktur')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hrd_izinkoreksi', function (Blueprint $table) {
            $table->dropColumn('head');
            $table->dropColumn('hrd');
            $table->dropColumn('forward_to_direktur');
        });
    }
};
