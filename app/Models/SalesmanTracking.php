<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesmanTracking extends Model
{
    use HasFactory;

    protected $table = 'salesman_tracking';
    protected $guarded = [];
    public $timestamps = false;
}
