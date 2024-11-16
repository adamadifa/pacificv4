<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyesuaianupah extends Model
{
    use HasFactory;
    protected $table = 'hrd_penyesuaian_upah';
    protected $guarded = [];
    protected $primaryKey = 'kode_gaji';
    public $incrementing = false;
}
