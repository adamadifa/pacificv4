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
        Schema::table('worksheetom_visitpelanggan', function (Blueprint $table) {
            $table->char('jenis_kunjungan', 3)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('worksheetom_visitpelanggan', function (Blueprint $table) {
            $table->dropColumn('jenis_kunjungan');
        });
    }
};
