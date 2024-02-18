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
        Schema::table('hrd_karyawan', function (Blueprint $table) {
            $table->char('spip', 1)->nullable()->after('lock_location');
            $table->string('no_rekening', 20)->nullable()->after('spip');
            $table->string('file_ktp', 20)->nullable()->after('no_rekening');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hrd_karyawan', function (Blueprint $table) {
            $table->dropColumn('spip');
            $table->dropColumn('no_rekening');
            $table->dropColumn('file_ktp');
        });
    }
};
