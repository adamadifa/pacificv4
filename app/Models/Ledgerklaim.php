<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledgerklaim extends Model
{
    use HasFactory;
    protected $table = "keuangan_ledger_klaim";
    protected $guarded = [];
}
