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
        Schema::table('keuangan_saldokasbesar', function (Blueprint $table) {
            $table->char('debet_kredit', 1)->default('K');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keuangan_saldokasbesar', function (Blueprint $table) {
            $table->dropColumn('debet_kredit');
        });
    }
};
