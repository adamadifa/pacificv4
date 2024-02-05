<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategorisalesman extends Model
{
    use HasFactory;
    protected $table = "salesman_kategori";
    protected $guarded = [];
    protected $primaryKey = "kode_kategori_salesman";
    public $incrementing = false;
}
