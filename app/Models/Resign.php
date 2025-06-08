<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resign extends Model
{
    use HasFactory;
    protected $table = 'hrd_resign';
    protected $primaryKey = 'kode_resign';
    protected $guarded = [];
    public $incrementing = false;
}
