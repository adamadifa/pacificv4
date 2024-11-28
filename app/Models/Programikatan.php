<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programikatan extends Model
{
    use HasFactory;

    protected $table = 'program_ikatan';
    protected $primaryKey = 'kode_program';
    public $incrementing = false;
    protected $guarded = [];
}
