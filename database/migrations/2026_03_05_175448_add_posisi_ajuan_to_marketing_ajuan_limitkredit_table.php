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
        Schema::table('marketing_ajuan_limitkredit', function (Blueprint $table) {
            $table->string('posisi_ajuan', 50)->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_ajuan_limitkredit', function (Blueprint $table) {
            $table->dropColumn('posisi_ajuan');
        });
    }
};
