<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketApprovalConfig extends Model
{
    use HasFactory;

    protected $table = 'ticket_approval_configs';
    protected $fillable = [
        'kode_dept',
        'kode_cabang',
        'roles'
    ];

    protected $casts = [
        'roles' => 'array'
    ];
}
