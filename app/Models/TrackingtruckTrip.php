<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackingtruckTrip extends Model
{
    use HasFactory;

    protected $table = 'tracking_truck_trips';

    protected $fillable = [
        'device_name',
        'imei',
        'model',
        'tanggal',
        'start_time',
        'start_location',
        'end_time',
        'end_location',
        'mileage',
        'travel_time',
        'average_speed',
        'max_speed',
        'fuel_ratio',
        'fuel_consumption',
    ];
}
