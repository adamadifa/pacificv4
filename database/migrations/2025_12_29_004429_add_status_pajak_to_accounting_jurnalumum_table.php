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
        Schema::table('accounting_jurnalumum', function (Blueprint $table) {
            $table->tinyInteger('status_pajak')->default(0)->after('id_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounting_jurnalumum', function (Blueprint $table) {
            $table->dropColumn('status_pajak');
        });
    }
};
