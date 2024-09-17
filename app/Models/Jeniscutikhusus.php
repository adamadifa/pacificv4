<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jeniscutikhusus extends Model
{
    use HasFactory;
    protected $table = "hrd_jeniscuti_khusus";
    protected $primaryKey = "kode_cuti_khusus";
    protected $guarded = [];
    public $incrementing = false;
}
