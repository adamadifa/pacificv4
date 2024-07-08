<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sumbercostratio extends Model
{
    use HasFactory;
    protected $table = "accounting_costratio_sumber";
    protected $primaryKey = "kode_sumber";
    protected $guarded = [];
    public $incrementing  = false;
}
