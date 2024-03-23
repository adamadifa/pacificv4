<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Omancabang extends Model
{
    use HasFactory;
    protected $table = "marketing_oman_cabang";
    protected $primaryKey = "kode_oman";
    protected $guarded = [];
    public $incrementing = false;

    public function detailomancabang()
    {
        return $this->hasMany(Detailomancabang::class);
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'kode_cabang');
    }
}
