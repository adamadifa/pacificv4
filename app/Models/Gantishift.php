<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gantishift extends Model
{
    use HasFactory;
    protected $table = "hrd_gantishift";
    protected $primaryKey = "kode_gs";
    protected $guarded = [];
    public $incrementing  = false;
}
