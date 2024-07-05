<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Pembelian extends Model
{
    use HasFactory;
    protected $table = "pembelian";
    protected $primaryKey = "no_bukti";
    protected $guarded = [];
    public $incrementing = false;

    function getPembelian($no_bukti = "", Request $request = null, $kode_supplier = "")
    {
        $start_date = config('global.start_date');
        $end_date = config('global.end_date');
        $query = Pembelian::query();
        $query->select('pembelian.*', 'nama_supplier', 'subtotal', 'penyesuaian_jk', 'totalbayar', 'cek_kontrabon', DB::raw('IFNULL(subtotal,0) + IFNULL(penyesuaian_jk,0) as total_pembelian'));
        $query->join('supplier', 'pembelian.kode_supplier', '=', 'supplier.kode_supplier');
        $query->leftJoin(
            DB::raw('(
                SELECT no_bukti, SUM( IF ( kode_transaksi = "PMB", ( ( jumlah * harga ) + penyesuaian ), 0 ) ) - SUM( IF ( kode_transaksi = "PNJ", ( jumlah * harga ), 0 ) ) as subtotal
                FROM pembelian_detail
                GROUP BY no_bukti
            ) detailpembelian'),
            function ($join) {
                $join->on('pembelian.no_bukti', '=', 'detailpembelian.no_bukti');
            }
        );

        $query->leftJoin(
            DB::raw('(
                SELECT
                no_bukti,
                (SUM(IF( debet_kredit = "K" AND kode_akun = "2-1200" OR debet_kredit = "K" AND kode_akun = "2-1300", (jumlah * harga), 0))
                - SUM(IF( debet_kredit = "D" AND kode_akun = "2-1200" OR debet_kredit = "D" AND kode_akun = "2-1300", (jumlah * harga), 0))
                ) as penyesuaian_jk
                FROM
                pembelian_jurnalkoreksi
                GROUP BY no_bukti
            ) jurnalkoreksi'),
            function ($join) {
                $join->on('pembelian.no_bukti', '=', 'jurnalkoreksi.no_bukti');
            }
        );

        $query->leftJoin(
            DB::raw('(
                SELECT
                no_bukti,
                SUM(pembelian_kontrabon_detail.jumlah) as totalbayar
                FROM
                pembelian_historibayar hb
                INNER JOIN pembelian_kontrabon_detail ON hb.no_kontrabon = pembelian_kontrabon_detail.no_kontrabon
                GROUP BY
                no_bukti
            ) historibayar'),
            function ($join) {
                $join->on('pembelian.no_bukti', '=', 'historibayar.no_bukti');
            }
        );

        $query->leftJoin(
            DB::raw('(
                SELECT no_bukti, COUNT( no_bukti ) as cek_kontrabon
                FROM pembelian_kontrabon_detail
                GROUP BY no_bukti
            ) kontrabon'),
            function ($join) {
                $join->on('pembelian.no_bukti', '=', 'kontrabon.no_bukti');
            }
        );
        if (!empty($request)) {
            if (!empty($request->dari) && !empty($request->sampai)) {
                $query->whereBetween('pembelian.tanggal', [$request->dari, $request->sampai]);
            } else {
                $query->whereBetween('pembelian.tanggal', [$start_date, $end_date]);
            }

            if (!empty($request->jatuhtempo_dari) && !empty($request->jatuhtempo_sampai)) {
                $query->whereBetween('pembelian.jatuh_tempo', [$request->jatuhtempo_dari, $request->jatuhtempo_sampai]);
            }

            if (!empty($request->no_bukti_search)) {
                $query->where('pembelian.no_bukti', $request->no_bukti_search);
            }

            if (!empty($request->kode_asal_pengajuan_search)) {
                $query->where('pembelian.kode_asal_pengajuan', $request->kode_asal_pengajuan_search);
            }

            if (!empty($request->kode_supplier_search)) {
                $query->where('pembelian.kode_supplier', $request->kode_supplier_search);
            }


            if ($request->has('ppn_search')) {
                $ppn = $request->ppn_search;
                if ($ppn === '0' || $ppn == '1') {
                    $query->where('pembelian.ppn', $ppn);
                }
            }



            if (!empty($request->jenis_transaksi_search)) {
                $query->where('pembelian.jenis_transaksi', $request->jenis_transaksi_search);
            }
        } else {
            $query->whereBetween('pembelian.tanggal', [$start_date, $end_date]);
        }
        if (!empty($no_bukti)) {
            $query->where('pembelian.no_bukti', $no_bukti);
        }

        if (!empty($kode_supplier)) {
            $query->where('pembelian.kode_supplier', $kode_supplier);
            $query->where('pembelian.jenis_transaksi', '!=', 'T');
            $query->whereRaw('IFNULL(subtotal,0) != IFNULL(totalbayar,0)');
        }
        $query->orderBy('pembelian.tanggal', 'desc');
        $query->orderBy('pembelian.no_bukti', 'desc');
        return $query;
    }
}
