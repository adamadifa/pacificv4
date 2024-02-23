<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mutasiproduksi extends Model
{
    use HasFactory;
    protected $table = "produksi_mutasi";
    protected $primaryKey = "no_mutasi";
    protected $guarded = [];
    public $incrementing = false;
}
