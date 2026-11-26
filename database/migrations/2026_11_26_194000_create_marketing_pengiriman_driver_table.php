<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_pengiriman_driver', function (Blueprint $table) {
            $table->id();
            $table->string('no_pengiriman', 20)->unique();
            $table->date('tanggal');
            $table->char('kode_cabang', 3);
            $table->char('kode_salesman', 7);
            $table->char('kode_driver', 6);
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('id_user')->nullable();
            $table->timestamps();

            $table->foreign('kode_cabang')->references('kode_cabang')->on('cabang')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('kode_salesman')->references('kode_salesman')->on('salesman')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('kode_driver')->references('kode_driver_helper')->on('driver_helper')->onUpdate('cascade')->onDelete('restrict');
        });

        Schema::create('marketing_pengiriman_driver_helper', function (Blueprint $table) {
            $table->id();
            $table->string('no_pengiriman', 20);
            $table->char('kode_driver_helper', 6);
            $table->char('posisi', 1)->default('H');
            $table->timestamps();

            $table->foreign('no_pengiriman')->references('no_pengiriman')->on('marketing_pengiriman_driver')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('kode_driver_helper')->references('kode_driver_helper')->on('driver_helper')->onUpdate('cascade')->onDelete('restrict');
        });

        Schema::create('marketing_pengiriman_driver_detail', function (Blueprint $table) {
            $table->id();
            $table->string('no_pengiriman', 20);
            $table->smallInteger('no_urut')->default(1);
            $table->char('kode_pelanggan', 13);
            $table->char('kode_produk', 6);
            $table->decimal('qty', 10, 2)->default(0);
            $table->timestamps();

            $table->foreign('no_pengiriman')->references('no_pengiriman')->on('marketing_pengiriman_driver')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('kode_pelanggan')->references('kode_pelanggan')->on('pelanggan')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('kode_produk')->references('kode_produk')->on('produk')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_pengiriman_driver_detail');
        Schema::dropIfExists('marketing_pengiriman_driver_helper');
        Schema::dropIfExists('marketing_pengiriman_driver');
    }
};
