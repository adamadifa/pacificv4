<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detailkontrabonangkutan extends Model
{
    use HasFactory;
    protected $table = "gudang_jadi_angkutan_kontrabon_detail";
    protected $guarded = [];
}
