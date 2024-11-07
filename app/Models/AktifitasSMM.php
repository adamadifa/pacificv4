<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AktifitasSMM extends Model
{
    use HasFactory;

    protected $table = 'aktifitas_smm';
    protected $guarded = [];
    protected $primaryKey = 'kode_aktifitas';
}
