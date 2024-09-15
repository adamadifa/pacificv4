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
        Schema::table('hrd_izinkeluar', function (Blueprint $table) {
            $table->char('kode_dept', 3)->after('kode_jabatan');
            $table->char('kode_cabang', 3)->after('kode_dept');
            $table->char('direktur', 1)->after('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hrd_izinkeluar', function (Blueprint $table) {
            //
        });
    }
};
