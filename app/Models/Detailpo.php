<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detailpo extends Model
{
    use HasFactory;
    protected $table = "po_detail";
    protected $guarded = [];
}
