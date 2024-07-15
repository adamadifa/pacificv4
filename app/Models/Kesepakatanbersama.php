<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kesepakatanbersama extends Model
{
    use HasFactory;
    protected $table = "hrd_kesepakatanbersama";
    protected $primaryKey = "no_kb";
    protected $guarded = [];
    public $incrementing = false;
}
