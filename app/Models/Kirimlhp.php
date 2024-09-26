<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kirimlhp extends Model
{
    use HasFactory;

    protected $table = "kirim_lhp";
    protected $primaryKey = "kode_kirim_lhp";
    protected $guarded = [];
    public $incrementing = false;
}
