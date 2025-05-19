<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticketmessage extends Model
{
    use HasFactory;
    protected $table = 'tickets_messages';
    protected $fillable = [
        'kode_pengajuan',
        'id_user',
        'message',
    ];

    
}

