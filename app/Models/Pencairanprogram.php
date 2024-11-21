<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pencairanprogram extends Model
{
    use HasFactory;

    protected $table = 'marketing_program_pencairan';
    protected $guarded = [];
    protected $primaryKey = 'kode_pencairan';
    public $incrementing = false;
}
