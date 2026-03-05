<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AjuanlimitkreditConfig extends Model
{
    use HasFactory;
    protected $table = 'marketing_ajuan_limitkredit_config_approval';
    protected $fillable = ['min_limit', 'max_limit', 'roles'];

    protected $casts = [
        'roles' => 'array'
    ];
}
