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
        Schema::table('bukubesar_saldoawal_detail', function (Blueprint $table) {
            $table->bigInteger('jumlah')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bukubesar_saldoawal_detail', function (Blueprint $table) {
            $table->integer('jumlah')->change();
        });
    }
};
