<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiLogError extends Model
{
    use HasFactory;

    protected $table = 'hrd_presensi_log_error';
    protected $fillable = [
        'nik',
        'tanggal',
        'jam',
        'status_presensi',
        'lokasi',
        'error_message',
        'payload'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'nik', 'nik');
    }
}
