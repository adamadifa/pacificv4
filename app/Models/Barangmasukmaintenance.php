<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Barangmasukmaintenance extends Model
{
    use HasFactory;

    protected $table = "maintenance_barang_masuk";
    protected $primaryKey = "no_bukti";
    protected $guarded = [];
    public $incrementing = false;

    function getBarangmasuk($no_bukti = "", Request $request = null)
    {
        $start_date = config('global.start_date');
        $end_date = config('global.end_date');

        $query = Barangmasukmaintenance::query();
        $query->select('maintenance_barang_masuk.*', 'pembelian.tanggal as tanggal_pembelian', 'pembelian.kode_supplier', 'nama_supplier');
        $query->leftJoin('pembelian', 'maintenance_barang_masuk.no_bukti', '=', 'pembelian.no_bukti');
        $query->leftJoin('supplier', 'pembelian.kode_supplier', '=', 'supplier.kode_supplier');
        $query->orderBy('maintenance_barang_masuk.tanggal', 'desc');
        $query->orderBy('maintenance_barang_masuk.created_at', 'desc');

        if (!empty($request)) {
            if (!empty($request->dari) && !empty($request->sampai)) {
                $query->whereBetween('maintenance_barang_masuk.tanggal', [$request->dari, $request->sampai]);
            } else {
                $query->whereBetween('maintenance_barang_masuk.tanggal', [$start_date, $end_date]);
            }

            if (!empty($request->no_bukti_search)) {
                $query->where('maintenance_barang_masuk.no_bukti', $request->no_bukti_search);
            }
        }

        if (!empty($no_bukti)) {
            $query->where('maintenance_barang_masuk.no_bukti', $no_bukti);
        }

        return $query;
    }
}
