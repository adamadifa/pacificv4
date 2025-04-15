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
        Schema::table('marketing_pencairan_simpanan', function (Blueprint $table) {
            $table->char('metode_pembayaran', 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_pencairan_simpanan', function (Blueprint $table) {
            $table->dropColumn('metode_pembayaran');
        });
    }
};
