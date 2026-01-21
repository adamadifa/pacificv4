<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBPB extends Model
{
    use HasFactory;
    protected $table = 'bpb_detail';
    //protected $primaryKey = 'no_bpb';
    protected $guarded = [];
    public $incrementing = false;
}
