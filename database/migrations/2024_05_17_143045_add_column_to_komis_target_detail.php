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
        Schema::table('marketing_komisi_target_detail', function (Blueprint $table) {
            $table->char('kode_salesman', 7)->after('kode_target');
            $table->foreign('kode_salesman')->references('kode_salesman')->on('salesman')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_komisi_target_detail', function (Blueprint $table) {
            //
        });
    }
};
