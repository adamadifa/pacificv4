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
        Schema::table('hrd_izincuti', function (Blueprint $table) {
            $table->char('kode_cuti', 3)->after('kode_dept');
            $table->char('kode_cuti_khusus', 3)->nullable()->after('kode_cuti');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hrd_izincuti', function (Blueprint $table) {
            //
        });
    }
};
