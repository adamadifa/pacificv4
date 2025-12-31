<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnalumum extends Model
{
    use HasFactory;
    protected $table = "accounting_jurnalumum";
    protected $primaryKey = "kode_ju";
    protected $guarded = [];
    public $incrementing = false;

    // Accessor untuk kode_peruntukan (kolom di DB adalah kode_pruntukan dengan typo)

}
