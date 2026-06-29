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
        Schema::create('tracking_truck_trips', function (Blueprint $table) {
            $table->id();
            $table->string('device_name', 100);
            $table->string('imei', 50);
            $table->string('model', 50)->nullable();
            $table->date('tanggal');
            $table->string('start_time', 20)->nullable();
            $table->text('start_location')->nullable();
            $table->string('end_time', 20)->nullable();
            $table->text('end_location')->nullable();
            $table->double('mileage')->default(0);
            $table->string('travel_time', 20)->nullable();
            $table->double('average_speed')->default(0);
            $table->double('max_speed')->default(0);
            $table->double('fuel_ratio')->default(0);
            $table->double('fuel_consumption')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracking_truck_trips');
    }
};
