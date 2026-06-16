<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumenopname extends Model
{
    use HasFactory;

    protected $table = 'dokumen_opname';
    protected $primaryKey = 'kode_dokumen_opname';
    protected $guarded = [];
    public $incrementing = false;

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'kode_cabang', 'kode_cabang');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
