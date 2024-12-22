<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ajuanprogramkumulatif extends Model
{
    use HasFactory;
    protected $table = 'marketing_program_kumulatif';
    protected $primaryKey = 'no_pengajuan';
    public $incrementing = false;
    protected $guarded  = [];
}
