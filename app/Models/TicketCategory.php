<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCategory extends Model
{
    use HasFactory;

    protected $table = 'ticket_categories';
    protected $guarded = [];

    protected $casts = [
        'perlu_manager_dept' => 'boolean',
        'perlu_smm' => 'boolean',
        'perlu_rsm' => 'boolean',
        'perlu_gm' => 'boolean',
        'wajib_lampiran' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'id_kategori');
    }
}
