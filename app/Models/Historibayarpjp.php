<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historibayarpjp extends Model
{
    use HasFactory;
    protected $table = "keuangan_pjp_historibayar";
    protected $primaryKey = "no_bukti";
    protected $guarded = [];
    public $incrementing  = false;
}
