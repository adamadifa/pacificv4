<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hargaawalhpp extends Model
{
    use HasFactory;
    protected $table = "accounting_hpp_hargaawal";
    protected $primaryKey = "kode_hargaawal";
    protected $guarded = [];
    public $incrementing = false;
}
