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
        Schema::table('users', function (Blueprint $table) {
            $table->text('pjp_cabang_access')->nullable();
            $table->text('pjp_dept_access')->nullable();
            $table->text('pjp_jabatan_access')->nullable();
            $table->text('pjp_karyawan_access')->nullable();
            $table->text('pjp_group_access')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('pjp_cabang_access');
            $table->dropColumn('pjp_dept_access');
            $table->dropColumn('pjp_jabatan_access');
            $table->dropColumn('pjp_karyawan_access');
            $table->dropColumn('pjp_group_access');
        });
    }
};
