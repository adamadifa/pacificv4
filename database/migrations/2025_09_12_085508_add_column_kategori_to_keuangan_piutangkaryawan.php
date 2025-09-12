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
        Schema::table('keuangan_piutangkaryawan', function (Blueprint $table) {
            $table->char('kategori', 2)->default('KA')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keuangan_piutangkaryawan', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }
};
