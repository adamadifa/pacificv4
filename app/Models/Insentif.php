<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insentif extends Model
{
    use HasFactory;
    protected $table = "hrd_insentif";
    protected $primaryKey = "kode_insentif";
    protected $guarded = [];
    public $incrementing  = false;
}
