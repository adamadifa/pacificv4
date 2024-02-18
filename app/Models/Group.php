<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $table = "hrd_group";
    protected $primaryKey = "kode_group";
    protected $guarded = [];
    public $incrementing  = false;
}
