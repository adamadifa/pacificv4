<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pencairansimpanankumulatif extends Model
{
    use HasFactory;

    protected $table = 'marketing_pencairan_simpanan_kumulatif';
    protected $guarded = [];
    protected $primaryKey = 'kode_pencairan';
    public $incrementing = false;
}
