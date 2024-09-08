<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Setoranpenjualan extends Model
{
    use HasFactory;
    protected $table = "keuangan_setoranpenjualan";
    protected $primaryKey = "kode_setoran";
    protected $guarded = [];
    public $incrementing  = false;

    function getSetoranpenjualan($kode_setoran = "", Request $request = null)
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang_search;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang_search;
        }

        $subquerycekLHP = Historibayarpenjualan::select(
            'marketing_penjualan_historibayar.kode_salesman',
            'marketing_penjualan_historibayar.tanggal',
            DB::raw("SUM(IF(marketing_penjualan_historibayar.jenis_bayar='TN' AND giro_to_cash IS NULL AND voucher = 0,jumlah,0)) as cek_lhp_tunai"),
            DB::raw("SUM(IF(marketing_penjualan_historibayar.jenis_bayar='TP' AND giro_to_cash IS NULL AND voucher = 0,jumlah,0)) as cek_lhp_tagihan"),
            DB::raw("SUM(IF(giro_to_cash ='1',jumlah,0)) AS cek_giro_to_cash_transfer")
        )
            ->leftJoin('marketing_penjualan_historibayar_giro', 'marketing_penjualan_historibayar.no_bukti', '=', 'marketing_penjualan_historibayar_giro.no_bukti')
            ->leftJoin('marketing_penjualan_historibayar_transfer', 'marketing_penjualan_historibayar.no_bukti', '=', 'marketing_penjualan_historibayar_transfer.no_bukti')
            ->where('voucher', 0)
            ->whereBetween('marketing_penjualan_historibayar.tanggal', [$request->dari, $request->sampai])
            ->groupBy('marketing_penjualan_historibayar.kode_salesman', 'marketing_penjualan_historibayar.tanggal');


        $subquerycekGiro = Detailgiro::select(
            'marketing_penjualan_giro.kode_salesman',
            'marketing_penjualan_giro.tanggal',
            DB::raw("SUM(jumlah) as cek_lhp_giro")
        )
            ->join('marketing_penjualan_giro', 'marketing_penjualan_giro_detail.kode_giro', '=', 'marketing_penjualan_giro.kode_giro')
            ->whereBetween('marketing_penjualan_giro.tanggal', [$request->dari, $request->sampai])
            ->groupBy('marketing_penjualan_giro.kode_salesman', 'marketing_penjualan_giro.tanggal');


        $subquerycekTransfer = Detailtransfer::select(
            'marketing_penjualan_transfer.kode_salesman',
            'marketing_penjualan_transfer.tanggal',
            DB::raw("SUM(jumlah) as cek_lhp_transfer")
        )
            ->join('marketing_penjualan_transfer', 'marketing_penjualan_transfer_detail.kode_transfer', '=', 'marketing_penjualan_transfer.kode_transfer')
            ->leftJoin(
                DB::raw("(
                        SELECT marketing_penjualan_historibayar_transfer.no_bukti,kode_transfer,no_faktur,tanggal,giro_to_cash
                        FROM marketing_penjualan_historibayar_transfer
                        INNER JOIN marketing_penjualan_historibayar ON marketing_penjualan_historibayar_transfer.no_bukti = marketing_penjualan_historibayar.no_bukti
                        INNER JOIN marketing_penjualan_historibayar_giro ON marketing_penjualan_historibayar.no_bukti = marketing_penjualan_historibayar_giro.no_bukti
                        ) historibayartransfer"),
                function ($join) {
                    $join->on('marketing_penjualan_transfer_detail.kode_transfer', '=', 'historibayartransfer.kode_transfer');
                    $join->on('marketing_penjualan_transfer_detail.no_faktur', '=', 'historibayartransfer.no_faktur');
                }
            )

            ->whereBetween('marketing_penjualan_transfer.tanggal', [$request->dari, $request->sampai])
            ->whereNull('giro_to_cash')
            ->groupBy('marketing_penjualan_transfer.kode_salesman', 'marketing_penjualan_transfer.tanggal');


        $subquerycekKuranglebihsetor = Kuranglebihsetor::select(
            'keuangan_kuranglebihsetor.kode_salesman',
            'keuangan_kuranglebihsetor.tanggal',
            DB::raw("SUM(IF(jenis_bayar='1', uang_logam, 0)) AS kurangsetorlogam"),
            DB::raw("SUM(IF(jenis_bayar='1', uang_kertas, 0)) AS kurangsetorkertas"),
            DB::raw("SUM(IF(jenis_bayar='2', uang_logam, 0)) AS lebihsetorlogam"),
            DB::raw("SUM(IF(jenis_bayar='2', uang_kertas, 0)) AS lebihsetorkertas")
        )
            ->whereBetween('tanggal', [$request->dari, $request->sampai])
            ->groupBy('keuangan_kuranglebihsetor.kode_salesman', 'keuangan_kuranglebihsetor.tanggal');

        $query = Setoranpenjualan::query();
        $query->select(
            'keuangan_setoranpenjualan.*',
            'nama_salesman',
            'cek_lhp_tunai',
            'cek_lhp_tagihan',
            'cek_lhp_giro',
            'cek_lhp_transfer',
            'cek_giro_to_cash_transfer',
            'kurangsetorkertas',
            'kurangsetorlogam',
            'lebihsetorkertas',
            'lebihsetorlogam'
        );
        $query->join('salesman', 'keuangan_setoranpenjualan.kode_salesman', '=', 'salesman.kode_salesman');
        $query->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftJoinSub($subquerycekLHP, 'ceklhp', function ($join) {
            $join->on('keuangan_setoranpenjualan.kode_salesman', '=', 'ceklhp.kode_salesman')
                ->on('keuangan_setoranpenjualan.tanggal', '=', 'ceklhp.tanggal');
        });
        $query->leftJoinSub($subquerycekGiro, 'cekgiro', function ($join) {
            $join->on('keuangan_setoranpenjualan.kode_salesman', '=', 'cekgiro.kode_salesman')
                ->on('keuangan_setoranpenjualan.tanggal', '=', 'cekgiro.tanggal');
        });

        $query->leftJoinSub($subquerycekTransfer, 'cektransfer', function ($join) {
            $join->on('keuangan_setoranpenjualan.kode_salesman', '=', 'cektransfer.kode_salesman')
                ->on('keuangan_setoranpenjualan.tanggal', '=', 'cektransfer.tanggal');
        });

        $query->leftJoinSub($subquerycekKuranglebihsetor, 'cekkuranglebihsetor', function ($join) {
            $join->on('keuangan_setoranpenjualan.kode_salesman', '=', 'cekkuranglebihsetor.kode_salesman')
                ->on('keuangan_setoranpenjualan.tanggal', '=', 'cekkuranglebihsetor.tanggal');
        });
        // if (!$user->hasRole($roles_access_all_cabang)) {
        //     if ($user->hasRole('regional sales manager')) {
        //         $query->where('cabang.kode_regional', auth()->user()->kode_regional);
        //     } else {
        //         $query->where('salesman.kode_cabang', auth()->user()->kode_cabang);
        //     }
        // }

        $query->whereBetween('keuangan_setoranpenjualan.tanggal', [$request->dari, $request->sampai]);
        $query->where('salesman.kode_cabang', $kode_cabang);
        if (!empty($request->kode_salesman_search)) {
            $query->where('keuangan_setoranpenjualan.kode_salesman', $request->kode_salesman_search);
        }

        $query->orderBy('keuangan_setoranpenjualan.tanggal');
        $query->orderBy('nama_salesman');

        return $query;
    }
}
