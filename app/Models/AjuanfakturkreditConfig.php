<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AjuanfakturkreditConfig extends Model
{
    use HasFactory;
    protected $table = 'marketing_ajuan_faktur_config_approval';
    protected $fillable = ['roles'];

    protected $casts = [
        'roles' => 'array'
    ];
}
