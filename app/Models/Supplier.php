<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = "supplier";
    protected $primaryKey = "kode_supplier";
    protected $guarded = [];
    public $incrementing  = false;
}
