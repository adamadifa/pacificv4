<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slipgaji extends Model
{
    use HasFactory;

    protected $table = 'hrd_slipgaji';
    protected $primaryKey = 'kode_gaji';
    protected $guarded = [];
    public $incrementing = false;
}
