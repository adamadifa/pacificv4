<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bpjstenagakerja extends Model
{
    use HasFactory;
    protected $table = "hrd_bpjs_tenagakerja";
    protected $primaryKey = "kode_bpjs_tenagakerja";
    protected $guarded = [];
    public $incrementing = false;
}
