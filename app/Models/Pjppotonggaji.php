<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pjppotonggaji extends Model
{
    use HasFactory;

    protected $table = 'keuangan_pjp_potonggaji';
    protected $primaryKey = "kode_potongan";
    protected $guarded = [];
    public $incrementing = false;
}
