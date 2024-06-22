<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kontrakkaryawan extends Model
{
    use HasFactory;
    protected $table = "hrd_kontrak";
    protected $primaryKey = "no_kontrak";
    protected $guarded = [];
    public $incrementing = false;
}
