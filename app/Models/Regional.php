<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regional extends Model
{
    use HasFactory;
    protected $table = "regional";
    protected $primaryKey = "kode_regional";
    protected $guarded = [];
    public $incrementing = false;
}
