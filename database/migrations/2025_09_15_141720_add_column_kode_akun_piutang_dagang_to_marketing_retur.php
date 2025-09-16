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
        Schema::table('marketing_retur', function (Blueprint $table) {
            $table->char('kode_akun_piutang_dagang', 6)->default('1-1401')->after('kode_akun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_retur', function (Blueprint $table) {
            $table->dropColumn('kode_akun_piutang_dagang');
        });
    }
};
