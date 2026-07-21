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
        Schema::create('ticket_approval_configs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_dept', 10)->nullable();
            $table->string('kode_cabang', 10)->nullable();
            $table->json('roles');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_approval_configs');
    }
};
