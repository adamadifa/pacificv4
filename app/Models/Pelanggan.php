<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use \Illuminate\Support\Facades\Log;


class Pelanggan extends Model
{
    use HasFactory;
    // use LogsActivity;
    protected $table = "pelanggan";
    protected $primaryKey = "kode_pelanggan";
    protected $guarded = [];
    public $incrementing = false;

    // public function getActivitylogOptions(): LogOptions
    // {
    //     Log::info('Spatie Log Activity dipanggil untuk Pelanggan'); // Debugging
    //     return LogOptions::defaults()
    //         ->logAll();
    //     // Chain fluent methods for configuration options
    // }

    public function getJmlpelanggan($request, $status = '')
    {

        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $query = Pelanggan::query();
        $query->leftjoin('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah');
        $query->join('salesman', 'pelanggan.kode_salesman', '=', 'salesman.kode_salesman');
        $query->join('cabang', 'pelanggan.kode_cabang', '=', 'cabang.kode_cabang');
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('pelanggan.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        if (!empty($request->kode_cabang)) {
            $query->where('pelanggan.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_salesman)) {
            $query->where('pelanggan.kode_salesman', $request->kode_salesman);
        }

        if (!empty($request->kode_pelanggan)) {
            $query->where('kode_pelanggan', $request->kode_pelanggan);
        }

        if (!empty($request->nama_pelanggan)) {
            $query->where('nama_pelanggan', 'like', '%' . $request->nama_pelanggan . '%');
        }

        if ($status !== '') {
            $query->where('status_aktif_pelanggan', $status);
        }

        if ($user->hasRole('salesman')) {
            $query->where('pelanggan.kode_salesman', $user->kode_salesman);
        }
        $query->orderBy('tanggal_register', 'desc');

        return $query->count();
    }
}
