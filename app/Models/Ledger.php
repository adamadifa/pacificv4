<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    use HasFactory;
    protected $table = "keuangan_ledger";
    protected $primaryKey = "no_bukti";
    protected $guarded = [];
    public $incrementing = false;
}