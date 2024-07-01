<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kontrabonpembelian extends Model
{
    use HasFactory;
    protected $table = "pembelian_kontrabon";
    protected $primaryKey = "no_kontrabon";
    protected $guarded = [];
    public $incrementing = false;
}
