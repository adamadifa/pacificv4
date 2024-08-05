<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Belumsetor extends Model
{
    use HasFactory;
    protected $table = "keuangan_belumsetor";
    protected $primaryKey = "kode_belumsetor";
    protected $guarded = [];
    public $incrementing = false;
}
