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
            $table->integer('jml_awal')->nullable();
            $table->integer('rsm')->nullable();
            $table->integer('gm')->nullable();
            $table->integer('direktur')->nullable();
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
