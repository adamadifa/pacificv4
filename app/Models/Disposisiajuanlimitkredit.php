<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disposisiajuanlimitkredit extends Model
{
    use HasFactory;
    protected $table = "marketing_ajuan_limitkredit_disposisi";
    protected $primaryKey = "kode_disposisi";
    protected $guarded = [];
    public $incrementing  = false;
}
