<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mutasigudangjadi extends Model
{
    use HasFactory;
    protected $table = "gudang_jadi_mutasi";
    protected $primaryKey = "no_mutasi";
    protected $guarded = [];
    public $incrementing = false;
}
