<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailgiro;
use App\Models\Detailtransfer;
use App\Models\Giro;
use App\Models\Historibayarpenjualan;
use App\Models\Setoranpenjualan;
use App\Models\Transfer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class SetoranpenjualanController extends Controller
{
    public function index(Request $request)
    {


        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }

        $sp = new Setoranpenjualan();
        $data['setoran_penjualan'] = $sp->getSetoranpenjualan(request: $request)->get();

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

        $transfer = Detailtransfer::select(
            'marketing_penjualan_transfer.kode_salesman',
            DB::raw("SUM(jumlah) as lhp_transfer")
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
            ->where('marketing_penjualan_transfer.kode_salesman', $request->kode_salesman)
            ->where('marketing_penjualan_transfer.tanggal', $request->tanggal)
            ->whereNull('giro_to_cash')
            ->groupBy('marketing_penjualan_transfer.kode_salesman')
            ->first();


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


        $girototransfer = Historibayarpenjualan::select(
            'marketing_penjualan_historibayar.kode_salesman',
            DB::raw("SUM(jumlah) as girototransfer")
        )
            ->leftJoin('marketing_penjualan_historibayar_giro', 'marketing_penjualan_historibayar.no_bukti', '=', 'marketing_penjualan_historibayar_giro.no_bukti')
            ->leftJoin('marketing_penjualan_historibayar_transfer', 'marketing_penjualan_historibayar.no_bukti', '=', 'marketing_penjualan_historibayar_transfer.no_bukti')
            ->where('marketing_penjualan_historibayar.kode_salesman', $request->kode_salesman)
            ->where('marketing_penjualan_historibayar.tanggal', $request->tanggal)
            ->where('giro_to_cash', 1)
            ->whereNotNull('kode_transfer')
            ->groupBy('marketing_penjualan_historibayar.kode_salesman')
            ->first();
        $lhp_tunai = $tunaitagihan != null ? $tunaitagihan->lhp_tunai : 0;
        $lhp_tagihan = $tunaitagihan != null ? $tunaitagihan->lhp_tagihan : 0;
        $lhp_giro = $giro != null ? $giro->lhp_giro : 0;
        $lhp_transfer = $transfer != null ? $transfer->lhp_transfer : 0;

        $total_tagihan = $lhp_tagihan + $lhp_giro + $lhp_transfer;
        $giro_to_cash = $girotocash != null ? $girotocash->girotocash : 0;
        $giro_to_transfer = $girototransfer != null ? $girototransfer->girototransfer : 0;
        $data = [
            'lhp_tunai' => $lhp_tunai,
            'lhp_tagihan' => $total_tagihan,
            'setoran_giro' => $lhp_giro,
            'setoran_transfer' => $lhp_transfer,
            'giro_to_cash' => $giro_to_cash,
            'giro_to_transfer' => $giro_to_transfer,
            'giro' => $lhp_giro,
            'transfer' => $lhp_transfer
        ];
        return response()->json([
            'success' => true,
            'message' => 'Detail Pelanggan',
            'data'    => $data
        ]);
    }


    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($request->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $ceksetoranpenjualan = Setoranpenjualan::where('tanggal', $request->tanggal)->where('kode_salesman', $request->kode_salesman)->count();
            if ($ceksetoranpenjualan > 0) {
                return Redirect::back()->with(messageError('Data Sudah Ada'));
            }


            //Generate Kode Setoran

            $lastsetoranpenjualan = Setoranpenjualan::select('kode_setoran')
                ->whereRaw('LEFT(kode_setoran,4)="SP' . date('Y') . '"')
                ->orderBy('kode_setoran', 'desc')
                ->first();
            $last_kode_setoran = $lastsetoranpenjualan != null ? $lastsetoranpenjualan->kode_setoran : '';
            $kode_setoran = buatkode($last_kode_setoran, 'SP' . substr(date('Y'), 2, 2), 5);

            Setoranpenjualan::create([
                'kode_setoran' => $kode_setoran,
                'tanggal' => $request->tanggal,
                'kode_salesman' => $request->kode_salesman,
                'lhp_tunai' => toNumber($request->lhp_tunai),
                'lhp_tagihan' => toNumber($request->lhp_tagihan),
                'setoran_kertas' => toNumber($request->setoran_kertas),
                'setoran_logam' => toNumber($request->setoran_logam),
                'setoran_lainnya' => toNumber($request->setoran_lainnya),
                'setoran_giro' => toNumber($request->setoran_giro),
                'setoran_transfer' => toNumber($request->setoran_transfer),
                'giro_to_cash' => toNumber($request->giro_to_cash),
                'giro_to_transfer' => toNumber($request->giro_to_transfer),
                'keterangan' => $request->keterangan
            ]);

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
