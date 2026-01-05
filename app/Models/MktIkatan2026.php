<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MktIkatan2026 extends Model
{
    use HasFactory;
    protected $table = 'mkt_ikatan_2026';
    protected $primaryKey = 'no_pengajuan';
    public $incrementing = false;
    protected $guarded = [];
}
