<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detailomancabang extends Model
{
    use HasFactory;
    protected $table = "marketing_oman_cabang_detail";
    protected $guarded = [];

    public function omancabang()
    {
        return $this->belongsTo(Omancabang::class);
    }
}
