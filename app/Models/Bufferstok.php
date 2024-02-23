<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bufferstok extends Model
{
    use HasFactory;
    protected $table = "buffer_stok";
    protected $primaryKey = "kode_buffer_stok";
    protected $guarded = [];
    public $incrementing = false;
}
