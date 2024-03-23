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
        Schema::table('marketing_oman_cabang_detail', function (Blueprint $table) {
            $table->dropColumn('dari');
            $table->dropColumn('sampai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_oman_cabang_detail', function (Blueprint $table) {
            $table->date('dari');
            $table->date('sampai');
        });
    }
};
