<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setoranpusatajuantransfer extends Model
{
    use HasFactory;
    protected $table = "keuangan_setoranpusat_ajuantransfer";
    protected $guarded = [];
    public $incrementing  = false;
}
