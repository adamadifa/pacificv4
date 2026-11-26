<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_fpb', function (Blueprint $table) {
            $table->id();
            $table->string('no_fpb', 20)->unique();
            $table->date('tanggal');
            $table->char('kode_cabang', 3);
            $table->char('kode_salesman', 7);
            $table->string('area_rute', 255)->nullable();
            $table->char('kode_driver', 6);
            $table->char('kode_kendaraan', 6)->nullable();
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('id_user')->nullable();
            $table->timestamps();

            $table->foreign('kode_cabang')->references('kode_cabang')->on('cabang')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('kode_salesman')->references('kode_salesman')->on('salesman')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('kode_driver')->references('kode_driver_helper')->on('driver_helper')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('kode_kendaraan')->references('kode_kendaraan')->on('kendaraan')->onUpdate('cascade')->onDelete('set null');
        });

        Schema::create('marketing_fpb_helper', function (Blueprint $table) {
            $table->id();
            $table->string('no_fpb', 20);
            $table->char('kode_driver_helper', 6);
            $table->char('posisi', 1)->default('H');
            $table->timestamps();

            $table->foreign('no_fpb')->references('no_fpb')->on('marketing_fpb')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('kode_driver_helper')->references('kode_driver_helper')->on('driver_helper')->onUpdate('cascade')->onDelete('restrict');
        });

        Schema::create('marketing_fpb_faktur', function (Blueprint $table) {
            $table->id();
            $table->string('no_fpb', 20);
            $table->char('no_faktur', 13);
            $table->timestamps();

            $table->foreign('no_fpb')->references('no_fpb')->on('marketing_fpb')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('no_faktur')->references('no_faktur')->on('marketing_penjualan')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_fpb_faktur');
        Schema::dropIfExists('marketing_fpb_helper');
        Schema::dropIfExists('marketing_fpb');
    }
};
