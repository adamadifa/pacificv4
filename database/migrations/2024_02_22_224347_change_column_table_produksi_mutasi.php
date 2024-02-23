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
        Schema::table('produksi_mutasi', function (Blueprint $table) {
            $table->char('status')->nullable()->change();
            $table->char('unit', 1)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produksi_mutasi', function (Blueprint $table) {
            $table->char('status')->change();
            $table->char('unit', 1)->change();
        });
    }
};
