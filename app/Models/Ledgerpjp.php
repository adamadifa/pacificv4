<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledgerpjp extends Model
{
    use HasFactory;
    protected $table = "keuangan_ledger_pjp";
    protected $guarded = [];
}
