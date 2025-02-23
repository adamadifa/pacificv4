<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $primaryKey = 'kode_pengajuan';
    protected $guarded = [];
    public $incrementing = false;
    protected $keyType = 'string';
}
