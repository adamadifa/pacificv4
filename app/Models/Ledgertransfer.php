<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledgertransfer extends Model
{
    use HasFactory;
    protected $table = "keuangan_ledger_transfer";
    protected $guarded = [];
}
