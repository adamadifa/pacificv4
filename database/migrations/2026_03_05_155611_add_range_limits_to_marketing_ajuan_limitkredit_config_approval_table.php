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
        Schema::table('marketing_ajuan_limitkredit_config_approval', function (Blueprint $table) {
            $table->double('min_limit')->after('id')->default(0);
            $table->double('max_limit')->after('min_limit')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_ajuan_limitkredit_config_approval', function (Blueprint $table) {
            $table->dropColumn(['min_limit', 'max_limit']);
        });
    }
};
