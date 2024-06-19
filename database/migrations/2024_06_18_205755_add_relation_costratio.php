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
        Schema::table('accounting_costratio', function (Blueprint $table) {
            $table->foreign('kode_sumber')->references('kode_sumber')->on('accounting_costratio_sumber')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounting_costratio', function (Blueprint $table) {
            //
        });
    }
};
