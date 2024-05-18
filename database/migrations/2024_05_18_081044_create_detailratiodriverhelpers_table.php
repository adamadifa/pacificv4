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
        Schema::create('marketing_komisi_ratiodriverhelper_detail', function (Blueprint $table) {
            $table->char('kode_ratio', 10);
            $table->char('kode_driver_helper', 6);
            $table->double('ratio_default');
            $table->double('ratio_helper');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_komisi_ratiodriverhelper_detail');
    }
};
