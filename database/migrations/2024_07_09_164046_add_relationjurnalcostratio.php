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
        Schema::table('accounting_jurnalumum_costratio', function (Blueprint $table) {
            $table->foreign('kode_ju')->references('kode_ju')->on('accounting_jurnalumum')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('kode_cr')->references('kode_cr')->on('accounting_costratio')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounting_jurnalumum_costratio', function (Blueprint $table) {
            //
        });
    }
};
