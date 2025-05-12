<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ajuanprogramikatanenambulan extends Model
{
    use HasFactory;
    protected $table = 'marketing_program_ikatan_enambulan';
    protected $primaryKey = 'no_pengajuan';
    public $timestamps = false;
}
