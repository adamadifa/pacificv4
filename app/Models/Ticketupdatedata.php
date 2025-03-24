<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticketupdatedata extends Model
{
    use HasFactory;
    protected $table = 'tickets_update_data';
    protected $primaryKey = 'kode_pengajuan';
    public $incrementing = false;
    protected $guarded = [];
}
