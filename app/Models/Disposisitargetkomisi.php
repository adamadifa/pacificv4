<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disposisitargetkomisi extends Model
{
    use HasFactory;
    protected $table = "marketing_komisi_target_disposisi";
    protected $primaryKey = "kode_disposisi";
    protected $guarded = [];
    public $incrementing  = false;
}
