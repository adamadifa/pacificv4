<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detailajuanprogramikatanenambulan extends Model
{
    use HasFactory;

    protected $table = 'marketing_program_ikatan_enambulan_detail';
    protected $primaryKey = 'no_pengajuan';
    public $incrementing = false;
    protected $guarded = [];
}
