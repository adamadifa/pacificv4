<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $primaryKey = 'kode_pengajuan';
    protected $guarded = [];
    public $incrementing = false;
    protected $keyType = 'string';

    public function category()
    {
        return $this->belongsTo(TicketCategory::class, 'id_kategori');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function managerDept()
    {
        return $this->belongsTo(User::class, 'id_manager_dept');
    }

    public function smmUser()
    {
        return $this->belongsTo(User::class, 'id_smm');
    }

    public function rsmUser()
    {
        return $this->belongsTo(User::class, 'id_rsm');
    }

    public function gmUser()
    {
        return $this->belongsTo(User::class, 'id_gm');
    }

    public function adminUser()
    {
        return $this->belongsTo(User::class, 'id_admin');
    }

    public function messages()
    {
        return $this->hasMany(Ticketmessage::class, 'kode_pengajuan', 'kode_pengajuan');
    }

    public function getBadgeStatusAttribute()
    {
        if ($this->status == '1') {
            return '<span class="badge bg-success text-white fw-bold"><i class="ti ti-check me-1"></i>Selesai</span>';
        } elseif ($this->status == '2') {
            return '<span class="badge bg-danger text-white fw-bold"><i class="ti ti-x me-1"></i>Ditolak</span>';
        } else {
            return '<span class="badge bg-warning text-dark fw-bold"><i class="ti ti-clock me-1"></i>Menunggu</span>';
        }
    }

    public function getBadgePriorityAttribute()
    {
        switch ($this->priority) {
            case 'Urgent':
                return '<span class="badge bg-danger text-white fw-bold"><i class="ti ti-flame me-1"></i>Urgent</span>';
            case 'Tinggi':
                return '<span class="badge bg-warning text-dark fw-bold">Tinggi</span>';
            case 'Sedang':
                return '<span class="badge bg-info text-white fw-bold">Sedang</span>';
            default:
                return '<span class="badge bg-secondary text-white">Rendah</span>';
        }
    }

    public function getBadgePosisiAttribute()
    {
        if ($this->status == '1') {
            return '<span class="badge bg-success text-white">Selesai (IT Admin)</span>';
        } elseif ($this->status == '2') {
            return '<span class="badge bg-danger text-white">Ditolak</span>';
        }

        switch ($this->posisi_approval) {
            case 'MANAGER_DEPT':
                return '<span class="badge bg-label-primary text-primary fw-bold">Menunggu Manager Dept</span>';
            case 'SMM':
                return '<span class="badge bg-label-primary text-primary fw-bold">Menunggu SMM</span>';
            case 'RSM':
                return '<span class="badge bg-label-info text-info fw-bold">Menunggu RSM</span>';
            case 'GM':
                return '<span class="badge bg-label-warning text-dark fw-bold">Menunggu GM</span>';
            case 'ADMIN':
                return '<span class="badge bg-primary text-white fw-bold"><i class="ti ti-cpu me-1"></i>Diproses IT Admin</span>';
            default:
                return '<span class="badge bg-label-primary text-primary fw-bold">Menunggu ' . strtoupper($this->posisi_approval) . '</span>';
        }
    }

    public function getLamaPenyelesaianAttribute()
    {
        $start = \Carbon\Carbon::parse($this->tanggal);
        if ($this->status == '1') {
            $end = \Carbon\Carbon::parse($this->updated_at);
            $days = $start->diffInDays($end);
            $hours = $start->copy()->addDays($days)->diffInHours($end);

            if ($days > 0) {
                return $days . ' Hari ' . ($hours > 0 ? $hours . ' Jam' : '');
            } elseif ($hours > 0) {
                return $hours . ' Jam';
            } else {
                return 'Hari yang sama (< 1 Hari)';
            }
        } elseif ($this->status == '2') {
            return 'Ditolak';
        } else {
            $now = \Carbon\Carbon::now();
            $days = $start->diffInDays($now);
            if ($days > 0) {
                return $days . ' Hari (Dalam Proses)';
            } else {
                return 'Hari ini (Dalam Proses)';
            }
        }
    }
}
