<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Barangkeluarmaintenance extends Model
{
    use HasFactory;
    protected $table = "maintenance_barang_keluar";
    protected $primaryKey = "no_bukti";
    protected $guarded = [];
    public $incrementing = false;

    function getBarangkeluar($no_bukti = "", Request $request = null)
    {
        $start_date = config('global.start_date');
        $end_date = config('global.end_date');

        $query = Barangkeluarmaintenance::query();
        $query->select('maintenance_barang_keluar.*', 'nama_dept');
        $query->join('hrd_departemen', 'maintenance_barang_keluar.kode_dept', '=', 'hrd_departemen.kode_dept');
        $query->orderBy('maintenance_barang_keluar.tanggal', 'desc');
        $query->orderBy('maintenance_barang_keluar.created_at', 'desc');
        if (!empty($request)) {
            if (!empty($request->dari) && !empty($request->sampai)) {
                $query->whereBetween('maintenance_barang_keluar.tanggal', [$request->dari, $request->sampai]);
            } else {
                $query->whereBetween('maintenance_barang_keluar.tanggal', [$start_date, $end_date]);
            }

            if (!empty($request->no_bukti_search)) {
                $query->where('maintenance_barang_keluar.no_bukti', $request->no_bukti_search);
            }
        }

        if (!empty($no_bukti)) {
            $query->where('maintenance_barang_keluar.no_bukti', $no_bukti);
        }

        return $query;
    }
}
