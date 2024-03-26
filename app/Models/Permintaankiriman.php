<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permintaankiriman extends Model
{
    use HasFactory;
    protected $table = "marketing_permintaan_kiriman";
    protected $primaryKey = "no_permintaan";
    protected $guarded = [];
    public $incrementing = false;
}
