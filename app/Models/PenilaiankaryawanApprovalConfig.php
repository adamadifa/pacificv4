<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaiankaryawanApprovalConfig extends Model
{
    use HasFactory;

    protected $table = 'hrd_penilaian_config_approval';
    protected $fillable = [
        'kode_dept',
        'kode_cabang',
        'kategori_jabatan',
        'kode_jabatan',
        'roles'
    ];

    protected $casts = [
        'roles' => 'array'
    ];
}
