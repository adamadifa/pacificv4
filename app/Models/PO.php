<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PO extends Model
{
    use HasFactory;
    protected $table = "po";
    protected $primaryKey = "no_bukti";
    protected $guarded = [];
    public $incrementing = false;

    function getPO($no_bukti = "", Request $request = null, $kode_supplier = "")
    {
        $role_access_all_pembelian = config('po.role_access_all_pembelian');
        $user = User::findorfail(auth()->user()->id);

        $start_date = config('global.start_date');
        $end_date = config('global.end_date');
        $query = PO::query();
        $query->select(
            'po.*',
            'nama_supplier',
            'subtotal'
        );

        //Cek Maintenance
        $query->join('supplier', 'po.kode_supplier', '=', 'supplier.kode_supplier');
        $query->leftJoin(
            DB::raw('(
                SELECT no_bukti, SUM(jumlah * harga) as subtotal
                FROM po_detail
                GROUP BY no_bukti
            ) detailpo'),
            function ($join) {
                $join->on('po.no_bukti', '=', 'detailpo.no_bukti');
            }
        );

        if (!empty($request)) {
            if (!empty($request->dari) && !empty($request->sampai)) {
                $query->whereBetween('po.tanggal', [$request->dari, $request->sampai]);
            } else {
                $query->whereBetween('po.tanggal', [$start_date, $end_date]);
            }

            if (!empty($request->jatuhtempo_dari) && !empty($request->jatuhtempo_sampai)) {
                $query->whereBetween('po.jatuh_tempo', [$request->jatuhtempo_dari, $request->jatuhtempo_sampai]);
            }

            if (!empty($request->no_bukti_search)) {
                $query->where('po.no_bukti', $request->no_bukti_search);
            }

            if (!empty($request->kode_supplier_search)) {
                $query->where('po.kode_supplier', $request->kode_supplier_search);
            }


            if ($request->has('ppn_search')) {
                $ppn = $request->ppn_search;
                if ($ppn === '0' || $ppn == '1') {
                    $query->where('po.ppn', $ppn);
                }
            }

            if (!empty($request->jenis_transaksi_search)) {
                $query->where('po.jenis_transaksi', $request->jenis_transaksi_search);
            }
        } else {
            $query->whereBetween('po.tanggal', [$start_date, $end_date]);
        }
        if (!empty($no_bukti)) {
            $query->where('po.no_bukti', $no_bukti);
        }

        if (!empty($kode_supplier)) {
            $query->where('po.kode_supplier', $kode_supplier);
            $query->where('po.jenis_transaksi', '!=', 'T');
            $query->whereRaw('IFNULL(subtotal,0) != IFNULL(totalbayar,0)');
        }

        if ($user->hasRole(['admin gudang logistik'])) {
            $query->where('po.kode_asal_pengajuan', 'GDL');
        }

        if ($user->hasRole(['admin maintenance'])) {
            $query->where('po.kode_asal_pengajuan', 'GAF');
            $query->whereIn('po.no_bukti', function ($query) {
                $query->select('no_bukti')->from('po_detail')->where('kode_akun', '1-1505');
            });
            $query->where('po.tanggal', '>', '2021-02-01');
        }
        $query->orderBy('po.tanggal', 'desc');
        $query->orderBy('po.no_bukti', 'desc');
        return $query;
    }
}
