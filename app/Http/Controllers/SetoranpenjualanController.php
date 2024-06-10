<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailgiro;
use App\Models\Detailtransfer;
use App\Models\Giro;
use App\Models\Historibayarpenjualan;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetoranpenjualanController extends Controller
{
    public function index(Request $request)
    {

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('keuangan.kasbesar.setoranpenjualan.index', $data);
    }

    public function create()
    {
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('keuangan.kasbesar.setoranpenjualan.create', $data);
    }

    public function getlhp(Request $request)
    {

        $tunaitagihan = Historibayarpenjualan::select(
            'marketing_penjualan_historibayar.kode_salesman',
            DB::raw("SUM(IF(marketing_penjualan_historibayar.jenis_bayar='TP',jumlah,0)) as lhp_tagihan"),
            DB::raw("SUM(IF(marketing_penjualan_historibayar.jenis_bayar='TN',jumlah,0)) as lhp_tunai")
        )
            ->leftJoin('marketing_penjualan_historibayar_giro', 'marketing_penjualan_historibayar.no_bukti', '=', 'marketing_penjualan_historibayar_giro.no_bukti')
            ->leftJoin('marketing_penjualan_historibayar_transfer', 'marketing_penjualan_historibayar.no_bukti', '=', 'marketing_penjualan_historibayar_transfer.no_bukti')
            ->whereNull('kode_giro')
            ->whereNull('kode_transfer')
            ->where('voucher', 0)
            ->where('marketing_penjualan_historibayar.kode_salesman', $request->kode_salesman)
            ->where('marketing_penjualan_historibayar.tanggal', $request->tanggal)
            ->groupBy('marketing_penjualan_historibayar.kode_salesman')
            ->first();

        $giro = Detailgiro::select(
            'marketing_penjualan_giro.kode_salesman',
            DB::raw("SUM(jumlah) as lhp_giro")
        )
            ->join('marketing_penjualan_giro', 'marketing_penjualan_giro_detail.kode_giro', '=', 'marketing_penjualan_giro.kode_giro')
            ->where('marketing_penjualan_giro.kode_salesman', $request->kode_salesman)
            ->where('marketing_penjualan_giro.tanggal', $request->tanggal)
            ->groupBy('marketing_penjualan_giro.kode_salesman')
            ->first();

        // $transfer = Detailtransfer::select(
        //     'marketing_penjualan_transfer.kode_salesman',
        //     DB::raw("SUM(jumlah) as lhp_transfer")
        // )
        //     ->join('marketing_penjualan_transfer', 'marketing_penjualan_transfer_detail.kode_transfer', '=', 'marketing_penjualan_transfer.kode_transfer')

        //     ->where('marketing_penjualan_transfer.kode_salesman', $request->kode_salesman)
        //     ->where('marketing_penjualan_transfer', $request->tanggal)
        //     ->groupBy('marketing_penjualan_transfer.kode_salesman')
        //     ->first();


        $girotocash = Historibayarpenjualan::select(
            'marketing_penjualan_historibayar.kode_salesman',
            DB::raw('SUM(jumlah) as girotocash')
        )
            ->leftJoin('marketing_penjualan_historibayar_giro', 'marketing_penjualan_historibayar.no_bukti', '=', 'marketing_penjualan_historibayar_giro.no_bukti')
            ->leftJoin('marketing_penjualan_historibayar_transfer', 'marketing_penjualan_historibayar.no_bukti', '=', 'marketing_penjualan_historibayar_transfer.no_bukti')
            ->where('marketing_penjualan_historibayar.kode_salesman', $request->kode_salesman)
            ->where('marketing_penjualan_historibayar.tanggal', $request->tanggal)
            ->where('giro_to_cash', 1)
            ->whereNull('kode_transfer')
            ->groupBy('marketing_penjualan_historibayar.kode_salesman')
            ->first();

        $lhp_tunai = $tunaitagihan != null ? $tunaitagihan->lhp_tunai : 0;
        $lhp_tagihan = $tunaitagihan != null ? $tunaitagihan->lhp_tagihan : 0;
        $lhp_giro = $giro != null ? $giro->lhp_giro : 0;

        $total_tagihan = $lhp_tagihan + $lhp_giro;
        $giro_to_cash = $girotocash != null ? $girotocash->girotocash : 0;
        $data = [
            'lhp_tunai' => $lhp_tunai,
            'lhp_tagihan' => $total_tagihan,
            'giro_to_cash' => $giro_to_cash,
            'giro' => $lhp_giro
        ];
        return response()->json([
            'success' => true,
            'message' => 'Detail Pelanggan',
            'data'    => $data
        ]);
    }
}
