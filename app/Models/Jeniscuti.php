<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jeniscuti extends Model
{
    use HasFactory;
    protected $table = "hrd_jeniscuti";
    protected $primaryKey = "kode_cuti";
    protected $guarded = [];
    public $incrementing = false;
}
