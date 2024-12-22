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
        Schema::table('marketing_program_kumulatif_detail', function (Blueprint $table) {
            $table->char('metode_pembayaran', 1)->after('kode_pelanggan');
            $table->string('file_doc')->nullable()->after('metode_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_program_kumulatif_detail', function (Blueprint $table) {
            //
        });
    }
};
