<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledgerkontrabon extends Model
{
    use HasFactory;
    protected $table = "keuangan_ledger_kontrabon";
    protected $guarded = [];
}
