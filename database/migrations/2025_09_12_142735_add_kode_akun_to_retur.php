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
            $table->char('kode_akun', 6)->default('4-2101')->after('jenis_retur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_retur', function (Blueprint $table) {
            $table->dropColumn('kode_akun');
        });
    }
};
