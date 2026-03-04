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
        Schema::table('hrd_penilaian', function (Blueprint $table) {
            $table->unsignedBigInteger('posisi_ajuan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hrd_penilaian', function (Blueprint $table) {
            $table->string('posisi_ajuan', 100)->nullable()->change();
        });
    }
};
