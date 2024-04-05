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
        Schema::table('marketing_permintaan_kiriman', function (Blueprint $table) {
            $table->index('kode_salesman');
            $table->index('tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_permintaan_kiriman', function (Blueprint $table) {
            $table->dropIndex('marketing_permintaan_kiriman_kode_salesman_index');
            $table->dropIndex('marketing_permintaan_kiriman_tanggal_index');
        });
    }
};
