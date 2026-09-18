<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('hrd_harilibur_kategori')->updateOrInsert(
            ['kode_kategori' => 5],
            [
                'nama_kategori' => 'Marketing Impact',
                'color' => 'secondary',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('hrd_harilibur_kategori')->where('kode_kategori', 5)->delete();
    }
};
