<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MktRewardProgram2026 extends Model
{
    use HasFactory;
    protected $table = 'mkt_reward_program_2026';
    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(MktRewardProgramDetail2026::class, 'reward_id');
    }
}
