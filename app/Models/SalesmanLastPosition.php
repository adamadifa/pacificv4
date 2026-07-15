<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesmanLastPosition extends Model
{
    use HasFactory;

    protected $table = 'salesman_last_position';
    protected $primaryKey = 'kode_salesman';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
}
