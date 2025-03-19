<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Kontrabonpembelian extends Model
{
    use HasFactory;
    protected $table = "pembelian_kontrabon";
    protected $primaryKey = "no_kontrabon";
    protected $guarded = [];
    public $incrementing = false;


    function getKontrabonpembelian($no_kontrabon = "", Request $request = null)
    {
        $start_date = config('global.start_date');
        $end_date = config('global.end_date');
        $query = Kontrabonpembelian::query();
        $query->select('pembelian_kontrabon.*', 'nama_supplier', 'detailkontrabon.jumlah', 'pembelian_historibayar.tanggal as tglbayar',  'nama_bank');
        $query->leftJoin('pembelian_historibayar', 'pembelian_kontrabon.no_kontrabon', '=', 'pembelian_historibayar.no_kontrabon');
        $query->join('supplier', 'pembelian_kontrabon.kode_supplier', '=', 'supplier.kode_supplier');
        $query->leftJoin('bank', 'pembelian_historibayar.kode_bank', '=', 'bank.kode_bank');
        $query->leftJoin(
            DB::raw('(
                SELECT no_kontrabon,SUM(jumlah) as jumlah
                FROM pembelian_kontrabon_detail
                GROUP BY no_kontrabon
            ) detailkontrabon'),
            function ($join) {
                $join->on('pembelian_kontrabon.no_kontrabon', '=', 'detailkontrabon.no_kontrabon');
            }
        );
        if (!empty($request)) {
            if (!empty($request->dari) && !empty($request->sampai)) {
                $query->whereBetween('pembelian_kontrabon.tanggal', [$request->dari, $request->sampai]);
            } else {
                $query->whereBetween('pembelian_kontrabon.tanggal', [$start_date, $end_date]);
            }

            if (!empty($request->kode_supplier_search)) {
                $query->where('pembelian_kontrabon.kode_supplier', $request->kode_supplier_search);
            }

            if (!empty($request->kategori_search)) {
                $query->where('pembelian_kontrabon.kategori', $request->kategori_search);
            }

            if (!empty($request->status_search)) {
                if ($request->status_search == 'SP') {
                    $query->whereNotNull('pembelian_historibayar.tanggal');
                } else {
                    $query->whereNull('pembelian_historibayar.tanggal');
                }
            }
        }

        if (!empty($no_kontrabon)) {
            $query->where('pembelian_kontrabon.no_kontrabon', $no_kontrabon);
        }

        $query->whereRaw('detailkontrabon.jumlah != 0');
        $query->orderBy('pembelian_kontrabon.tanggal', 'desc');
        return $query;
    }
}
