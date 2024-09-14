<?php

namespace App\Models;

use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Retur extends Model
{
    use HasFactory;
    protected $table = "marketing_retur";
    protected $primaryKey = "marketing_retur_detail";
    protected $guarded = [];
    public $incrementing = false;


    function getRetur($request, $no_retur, $jenis_retur = "")
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $start_date = config('global.start_date');
        $end_date = config('global.end_date');
        $query = Retur::query();
        $query->select(
            'marketing_retur.no_retur',
            'marketing_retur.tanggal',
            'marketing_retur.no_faktur',
            'marketing_penjualan.kode_pelanggan',
            'nama_pelanggan',
            'alamat_pelanggan',
            'kode_cabang_baru',
            'nama_cabang',
            'nama_salesman',
            'jenis_retur',
            DB::raw('(SELECT SUM(jumlah) FROM marketing_retur_detail WHERE no_retur = marketing_retur.no_retur) as total_qty_retur'),
            DB::raw('(SELECT SUM(jumlah) FROM worksheetom_retur_pelunasan WHERE no_retur = marketing_retur.no_retur) as total_qty_pelunasan'),
        );
        $query->addSelect(DB::raw('(SELECT SUM(subtotal) FROM marketing_retur_detail WHERE no_retur = marketing_retur.no_retur) as total_retur'));
        $query->join('marketing_penjualan', 'marketing_retur.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $query->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $query->leftJoin(
            DB::raw("(
                SELECT
                    marketing_penjualan.no_faktur,
                    IF( salesbaru IS NULL, marketing_penjualan.kode_salesman, salesbaru ) AS kode_salesman_baru,
                    IF( cabangbaru IS NULL, salesman.kode_cabang, cabangbaru ) AS kode_cabang_baru
                FROM
                    marketing_penjualan
                INNER JOIN salesman ON marketing_penjualan.kode_salesman = salesman.kode_salesman
                LEFT JOIN (
                SELECT
                    no_faktur,
                    marketing_penjualan_movefaktur.kode_salesman_baru AS salesbaru,
                    salesman.kode_cabang AS cabangbaru
                FROM
                    marketing_penjualan_movefaktur
                    INNER JOIN salesman ON marketing_penjualan_movefaktur.kode_salesman_baru = salesman.kode_salesman
                WHERE id IN (SELECT MAX(id) as id FROM marketing_penjualan_movefaktur GROUP BY no_faktur)
                ) movefaktur ON ( marketing_penjualan.no_faktur = movefaktur.no_faktur)
            ) pindahfaktur"),
            function ($join) {
                $join->on('marketing_penjualan.no_faktur', '=', 'pindahfaktur.no_faktur');
            }
        );

        $query->join('salesman', 'pindahfaktur.kode_salesman_baru', '=', 'salesman.kode_salesman');
        $query->join('cabang', 'pindahfaktur.kode_cabang_baru', '=', 'cabang.kode_cabang');
        if (!empty($no_retur)) {
            $query->where('marketing_retur.no_retur', $no_retur);
        }
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('marketing_retur.tanggal', [$request->dari, $request->sampai]);
        } else {
            $query->whereBetween('marketing_retur.tanggal', [$start_date, $end_date]);
        }

        if (!empty($request->no_faktur_search)) {
            $query->where('marketing_retur.no_faktur', $request->no_faktur_search);
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('kode_cabang_baru', $request->kode_cabang_search);
        }

        if (!empty($request->kode_salesman_search)) {
            $query->where('kode_salesman_baru', $request->kode_salesman_search);
        }

        if (!empty($request->kode_pelanggan_search)) {
            $query->where('marketing_penjualan.kode_pelanggan', $request->kode_pelanggan_search);
        }


        if (!empty($request->nama_pelanggan_search)) {
            $query->where('nama_pelanggan', 'like', '%' . $request->nama_pelanggan_search . '%');
        }

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('kode_cabang_baru', auth()->user()->kode_cabang);
            }
        }

        $query->orderBy('marketing_retur.tanggal', 'desc');
        $query->orderBy('marketing_retur.no_retur', 'desc');
        return $query;
    }


    function getDetailretur($no_retur)
    {
        $detail = Detailretur::select('marketing_retur_detail.*', 'produk_harga.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack', 'subtotal')
            ->join('produk_harga', 'marketing_retur_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->where('no_retur', $no_retur)
            ->get();
        return $detail;
    }
}
