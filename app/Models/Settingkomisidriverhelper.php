<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settingkomisidriverhelper extends Model
{
    use HasFactory;

    protected $table = 'marketing_komisi_driverhelper_setting';
    protected $guarded = [];
    protected $primaryKey = 'kode_komisi';
    public $incrementing = false;
}
