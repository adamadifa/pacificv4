<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldokasbesarkeuangan extends Model
{
    use HasFactory;
    protected $table = 'keuangan_saldokasbesar';
    protected $guarded = ['id'];
}
