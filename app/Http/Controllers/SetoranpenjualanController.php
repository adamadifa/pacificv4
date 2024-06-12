<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailgiro;
use App\Models\Detailtransfer;
use App\Models\Giro;
use App\Models\Historibayarpenjualan;
use App\Models\Salesman;
use App\Models\Setoranpenjualan;
use App\Models\Transfer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
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

        $request->validate([
            'tanggal' => 'required',
            'kode_salesman' => 'required'
        ]);
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
                ->whereRaw('LEFT(kode_setoran,4)="SP' . substr(date('Y'), 2, 2) . '"')
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

    public function showlhp(Request $request)
    {

        $data['lhp'] = Historibayarpenjualan::select(
            'marketing_penjualan_historibayar.no_bukti',
            'marketing_penjualan_historibayar.tanggal',
            'marketing_penjualan_historibayar.no_faktur',
            'marketing_penjualan.kode_pelanggan',
            'nama_pelanggan',
            DB::raw("SUM(IF(marketing_penjualan_historibayar.jenis_bayar='TN' AND giro_to_cash IS NULL,jumlah,0)) as lhp_tunai"),
            DB::raw("SUM(IF(marketing_penjualan_historibayar.jenis_bayar='TP' AND giro_to_cash IS NULL,jumlah,0)) as lhp_tagihan"),
            DB::raw("SUM(IF(giro_to_cash ='1',jumlah,0)) AS giro_to_cash_transfer")
        )
            ->join('marketing_penjualan', 'marketing_penjualan_historibayar.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->leftJoin('marketing_penjualan_historibayar_giro', 'marketing_penjualan_historibayar.no_bukti', '=', 'marketing_penjualan_historibayar_giro.no_bukti')
            ->leftJoin('marketing_penjualan_historibayar_transfer', 'marketing_penjualan_historibayar.no_bukti', '=', 'marketing_penjualan_historibayar_transfer.no_bukti')
            ->where('voucher', 0)
            ->where('marketing_penjualan_historibayar.tanggal', $request->tanggal)
            ->where('marketing_penjualan_historibayar.kode_salesman', $request->kode_salesman)
            ->whereNotIn('marketing_penjualan_historibayar.jenis_bayar', ['TR', 'GR'])
            ->groupBy(
                'marketing_penjualan_historibayar.no_bukti',
                'marketing_penjualan_historibayar.tanggal',
                'marketing_penjualan_historibayar.no_faktur',
                'marketing_penjualan.kode_pelanggan',
                'nama_pelanggan'
            )
            ->orderBy('marketing_penjualan_historibayar.no_bukti', 'asc')
            ->get();

        $data['giro'] =  Detailgiro::select(
            'marketing_penjualan_giro.no_giro',
            'marketing_penjualan_giro_detail.no_faktur',
            'nama_pelanggan',
            'marketing_penjualan_giro.tanggal',
            'marketing_penjualan_giro.kode_salesman',
            'marketing_penjualan_giro.jatuh_tempo',
            'marketing_penjualan_giro.status',
            DB::raw("SUM(jumlah) as lhp_giro")
        )
            ->join('marketing_penjualan_giro', 'marketing_penjualan_giro_detail.kode_giro', '=', 'marketing_penjualan_giro.kode_giro')
            ->join('marketing_penjualan', 'marketing_penjualan_giro_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->where('marketing_penjualan_giro.tanggal', $request->tanggal)
            ->where('marketing_penjualan_giro.kode_salesman', $request->kode_salesman)
            ->groupBy(
                'marketing_penjualan_giro.no_giro',
                'marketing_penjualan_giro_detail.no_faktur',
                'nama_pelanggan',
                'marketing_penjualan_giro.tanggal',
                'marketing_penjualan_giro.kode_salesman',
                'marketing_penjualan_giro.jatuh_tempo',
                'marketing_penjualan_giro.status'
            )
            ->get();

        $data['transfer'] = Detailtransfer::select(
            'marketing_penjualan_transfer.tanggal',
            'marketing_penjualan_transfer.kode_transfer',
            'marketing_penjualan_transfer_detail.no_faktur',
            'nama_pelanggan',
            'marketing_penjualan_transfer.kode_salesman',
            'marketing_penjualan_transfer.status',
            DB::raw("SUM(IF(giro_to_cash='1',jumlah,0)) as gito_to_transfer"),
            DB::raw("SUM(IF(giro_to_cash IS NULL,jumlah,0)) as lhp_transfer")
        )
            ->join('marketing_penjualan_transfer', 'marketing_penjualan_transfer_detail.kode_transfer', '=', 'marketing_penjualan_transfer.kode_transfer')
            ->join('marketing_penjualan', 'marketing_penjualan_transfer_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
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

            ->where('marketing_penjualan_transfer.tanggal', $request->tanggal)
            ->where('marketing_penjualan_transfer.kode_salesman', $request->kode_salesman)
            ->groupBy(
                'marketing_penjualan_transfer.tanggal',
                'marketing_penjualan_transfer.kode_transfer',
                'marketing_penjualan_transfer_detail.no_faktur',
                'nama_pelanggan',
                'marketing_penjualan_transfer.kode_salesman',
                'marketing_penjualan_transfer.status'
            )
            ->get();

        $data['salesman'] = Salesman::where('kode_salesman', $request->kode_salesman)->first();
        $data['tanggal'] = $request->tanggal;
        return view('keuangan.kasbesar.setoranpenjualan.showlhp', $data);
    }


    public function edit($kode_setoran)
    {
        $kode_setoran = Crypt::decrypt($kode_setoran);
        $data['setoranpenjualan'] = Setoranpenjualan::join('salesman', 'keuangan_setoranpenjualan.kode_salesman', '=', 'salesman.kode_salesman')->where('kode_setoran', $kode_setoran)->first();

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('keuangan.kasbesar.setoranpenjualan.edit', $data);
    }


    public function update(Request $request, $kode_setoran)
    {
        $kode_setoran = Crypt::decrypt($kode_setoran);

        DB::beginTransaction();
        try {

            $setoranpenjualan = Setoranpenjualan::where('kode_setoran', $kode_setoran)->first();


            $cektutuplaporansetoranpenjualan = cektutupLaporan($setoranpenjualan->tanggal, "penjualan");
            if ($cektutuplaporansetoranpenjualan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }


            Setoranpenjualan::where('kode_setoran', $kode_setoran)->update([
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

    public function destroy($kode_setoran)
    {
        $kode_setoran = Crypt::decrypt($kode_setoran);
        $setoranpenjualan = Setoranpenjualan::where('kode_setoran', $kode_setoran)->first();

        if (!$setoranpenjualan) {
            return Redirect::back()->with(messageError('Data Setoran Penjualan tidak ditemukan'));
        }

        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($setoranpenjualan->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            Setoranpenjualan::where('kode_setoran', $kode_setoran)->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function cetak(Request $request)
    {
        $sp = new Setoranpenjualan();
        $data['setoran_penjualan'] = $sp->getSetoranpenjualan(request: $request)->get();
        // dd($data['setoran_penjualan']);
        $data['cabang'] = Cabang::where('kode_cabang', $request->kode_cabang)->first();
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;

        if (isset($_GET['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Setoran Penjualan $request->dari-$request->sampai.xls");
        }
        return view('keuangan.kasbesar.setoranpenjualan.cetak', $data);
    }
}
