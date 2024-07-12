<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mutasikendaraan extends Model
{
    use HasFactory;

    protected $table = "ga_kendaraan_mutasi";
    protected $primaryKey = "no_mutasi";
    protected $guarded = [];
    public $incrementing = false;
}
