<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Validasiitemretur extends Model
{
    use HasFactory;
    protected $table = "worksheetom_validasiretur_item";
    protected $primaryKey = "kode_item";
    protected $guarded = [];
    public $incrementing = false;
}
