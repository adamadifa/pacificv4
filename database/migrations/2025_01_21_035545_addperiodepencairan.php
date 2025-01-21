<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Livewire\after;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('marketing_program_ikatan_detail', function (Blueprint $table) {
            $table->smallInteger('periode_pencairan')->after('top');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_program_ikatan_detail', function (Blueprint $table) {
            $table->dropColumn('periode_pencairan');
        });
    }
};
