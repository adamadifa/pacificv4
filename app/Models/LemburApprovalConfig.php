<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LemburApprovalConfig extends Model
{
    use HasFactory;

    protected $table = 'hrd_lembur_config_approval';
    protected $fillable = [
        'kode_dept',
        'kode_cabang',
        'roles'
    ];

    protected $casts = [
        'roles' => 'array'
    ];
}
