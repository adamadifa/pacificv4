<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bengkel extends Model
{
    use HasFactory;
    protected $table = "ga_bengkel";
    protected $primaryKey = "kode_bengkel";
    protected $guarded = [];
    public $incrementing = false;
}
