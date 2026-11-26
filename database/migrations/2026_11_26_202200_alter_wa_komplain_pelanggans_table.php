<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wa_komplain_pelanggans', function (Blueprint $table) {
            $table->dropForeign(['kode_pelanggan']);
            $table->dropForeign(['kode_cabang']);
            $table->dropForeign(['ditangani_oleh']);
        });

        Schema::table('wa_komplain_pelanggans', function (Blueprint $table) {
            $table->char('kode_pelanggan', 13)->nullable()->change();

            $table->foreign('kode_pelanggan')->references('kode_pelanggan')->on('pelanggan')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('kode_cabang')->references('kode_cabang')->on('cabang')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('ditangani_oleh')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('wa_komplain_pelanggans', function (Blueprint $table) {
            $table->dropForeign(['kode_pelanggan']);
            $table->dropForeign(['kode_cabang']);
            $table->dropForeign(['ditangani_oleh']);
        });

        Schema::table('wa_komplain_pelanggans', function (Blueprint $table) {
            $table->string('kode_pelanggan', 13)->nullable()->change();

            $table->foreign('kode_pelanggan')->references('kode_pelanggan')->on('pelanggan')->onDelete('set null');
            $table->foreign('kode_cabang')->references('kode_cabang')->on('cabang')->onDelete('set null');
            $table->foreign('ditangani_oleh')->references('id')->on('users')->onDelete('set null');
        });
    }
};
