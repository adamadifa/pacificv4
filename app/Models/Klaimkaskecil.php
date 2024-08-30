<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Klaimkaskecil extends Model
{
    use HasFactory;
    protected $table = "keuangan_kaskecil_klaim";
    protected $primaryKey = "kode_klaim";
    protected $guarded = [];
    public $incrementing = false;
}
