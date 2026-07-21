<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PanduanPertanyaanUser extends Model
{
    use HasFactory;

    protected $table = 'panduan_pertanyaan_user';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'id_admin');
    }
}
