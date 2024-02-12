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
        Schema::create('supplier', function (Blueprint $table) {
            $table->char('kode_supplier', 6)->primary();
            $table->string('nama_supplier', 100);
            $table->string('contact_person', 100)->nullable();
            $table->string('no_hp_supplier', 100)->nullable();
            $table->string('alamat_supplier')->nullable();
            $table->string('email_supplier')->nullable();
            $table->string('no_rekening_supplier', 30)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier');
    }
};
