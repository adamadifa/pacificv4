<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badstokga extends Model
{
    use HasFactory;
    protected $table = "ga_badstok";
    protected $primaryKey = "kode_bs";
    protected $guarded = [];
    public $incrementing = false;
}
