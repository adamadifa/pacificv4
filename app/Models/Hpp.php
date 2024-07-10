<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hpp extends Model
{
    use HasFactory;
    protected $table = "accounting_hpp";
    protected $primaryKey = "kode_hpp";
    protected $guarded = [];
    public $incrementing  = false;
}
