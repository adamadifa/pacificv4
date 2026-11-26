<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FpbFaktur extends Model
{
    use HasFactory;

    protected $table = 'marketing_fpb_faktur';

    protected $guarded = [];
}
