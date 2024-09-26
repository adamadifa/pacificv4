<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kirimlpc extends Model
{
    use HasFactory;
    protected $table = "kirim_lpc";
    protected $primaryKey = "kode_kirim_lpc";
    protected $guarded = [];
    public $incrementing = false;
}
