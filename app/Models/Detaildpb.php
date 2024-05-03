<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detaildpb extends Model
{
    use HasFactory;
    protected $table = "gudang_cabang_dpb_detail";
    protected $guarded = [];
    public $incrementing  = false;
}
