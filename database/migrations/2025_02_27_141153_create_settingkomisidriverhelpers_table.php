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
        Schema::create('marketing_komisi_driverhelper_setting', function (Blueprint $table) {
            $table->char('kode_komisi', 10)->primary();
            $table->smallInteger('bulan');
            $table->char('tahun', 4);
            $table->integer('komisi_salesman');
            $table->smallInteger('qty_flat');
            $table->smallInteger('umk');
            $table->smallInteger('persentase');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_komisi_driverhelper_setting');
    }
};
