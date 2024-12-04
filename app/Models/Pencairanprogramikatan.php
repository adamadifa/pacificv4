<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pencairanprogramikatan extends Model
{
    use HasFactory;
    protected $table = 'marketing_pencairan_ikatan';
    protected $primaryKey = 'kode_pencairan';
    public $incrementing = false;
    protected $guarded = [];
}
