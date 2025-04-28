<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategoritransaksimutasikeuangan extends Model
{
    use HasFactory;
    protected $table = 'keuangan_mutasi_kategori';
    protected $primaryKey = 'kode_kategori';
    protected $guarded = [];
    public $incrementing = false;
}
