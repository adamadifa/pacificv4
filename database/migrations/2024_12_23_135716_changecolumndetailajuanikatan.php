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
        Schema::table('marketing_program_ikatan_detail', function (Blueprint $table) {
            $table->dropColumn('budget');
            $table->integer('budget_smm')->default(0);
            $table->integer('budget_rsm')->default(0);
            $table->integer('budget_gm')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_program_ikatan_detail', function (Blueprint $table) {
            //
        });
    }
};
