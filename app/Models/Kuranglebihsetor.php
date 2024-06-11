<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kuranglebihsetor extends Model
{
    use HasFactory;
    protected $table = "keuangan_kuranglebihsetor";
    protected $guarded = [];
    protected $primaryKey = "kode_kl";
    public $incrementing = false;
}
