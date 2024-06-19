<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Costratio extends Model
{
    use HasFactory;
    protected $table = "accounting_costratio";
    protected $primaryKey = "kode_cr";
    protected $guarded = [];
    public $incrementing = false;
}
