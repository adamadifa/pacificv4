<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dpb extends Model
{
    use HasFactory;
    protected $table = "gudang_cabang_dpb";
    protected $primaryKey = "no_dpb";
    protected $guarded = [];
    public $incrementing  = false;
}
