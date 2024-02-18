<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bpjskesehatan extends Model
{
    use HasFactory;
    protected $table = "hrd_bpjs_kesehatan";
    protected $primaryKey = "kode_bpjs_kesehatan";
    protected $guarded = [];
    public $incrementing = false;
}
