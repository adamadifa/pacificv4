<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategorijmk extends Model
{
    use HasFactory;
    protected $table = 'hrd_kategorijmk';
    protected $guarded = [];
    protected $primaryKey = 'kode_kategori';
    public $incrementing = false;
}
