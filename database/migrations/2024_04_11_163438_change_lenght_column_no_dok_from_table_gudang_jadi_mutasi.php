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
        Schema::table('gudang_jadi_mutasi', function (Blueprint $table) {
            $table->char('no_dok', 15)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gudang_jadi_mutasi', function (Blueprint $table) {
            $table->string('no_dok', 30)->nullable()->change();
        });
    }
};
