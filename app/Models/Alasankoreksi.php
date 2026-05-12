<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alasankoreksi extends Model
{
    use HasFactory;

    protected $table = 'hrd_alasan_koreksi';
    protected $fillable = ['alasan', 'status_denda'];
}
