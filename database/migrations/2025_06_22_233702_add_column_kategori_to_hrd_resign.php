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
        Schema::table('hrd_resign', function (Blueprint $table) {
            $table->char('kode_kategori', 5)->default('JMK01');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hrd_resign', function (Blueprint $table) {
            $table->dropColumn('kode_kategori');
        });
    }
};
