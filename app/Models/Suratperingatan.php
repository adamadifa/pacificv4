<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suratperingatan extends Model
{
    use HasFactory;
    protected $table = "hrd_suratperingatan";
    protected $primaryKey = "no_sp";
    protected $guarded = [];
    public $incrementing  = false;
}
