<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pencairanprogramenambulan extends Model
{
    use HasFactory;
    protected $table = 'marketing_pencairan_ikatan_enambulan';
    protected $primaryKey = 'kode_pencairan';
    protected $guarded = [];
    public $incrementing = false;
}
