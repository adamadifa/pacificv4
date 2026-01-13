<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PencairanProgramIkatan2026 extends Model
{
    use HasFactory;
    protected $table = 'marketing_pencairan_ikatan_2026';
    protected $primaryKey = 'kode_pencairan';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
}
