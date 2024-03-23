<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oman extends Model
{
    use HasFactory;
    protected $table = "marketing_oman";
    protected $primaryKey = "kode_oman";
    protected $guarded = [];
    public $incrementing = false;
}
