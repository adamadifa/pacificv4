<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jenisvoucher extends Model
{
    use HasFactory;
    protected $table = "jenis_voucher";
    protected $primaryKey = "id";
    protected $guarded = [];
    public $incrementing = false;
}
