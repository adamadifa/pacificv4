<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pencairansimpanan extends Model
{
    use HasFactory;
    protected $table = 'marketing_pencairan_simpanan';
    protected $guarded = [];
    protected $primaryKey = 'kode_pencairan';
    public $incrementing = false;
}
