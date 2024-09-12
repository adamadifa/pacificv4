<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitpelanggan extends Model
{
    use HasFactory;
    protected $table = "worksheetom_visitpelanggan";
    protected $primaryKey = "kode_visit";
    protected $guarded = [];
    public $incrementing = false;
}
