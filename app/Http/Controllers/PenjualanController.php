<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Checkinpenjualan;
use App\Models\Detailgiro;
use App\Models\Detailpenjualan;
use App\Models\Detailretur;
use App\Models\Detailtransfer;
use App\Models\Historibayarpenjualan;
use App\Models\Penjualan;
use App\Models\Retur;
use App\Models\Salesman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {

        $start_year = config('global.start_year');
        $start_date = config('global.start_date');
        $end_date = config('global.end_date');


        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }
        $query = Penjualan::query();
        $query->select(
            'marketing_penjualan.*',
            'nama_pelanggan',
            'nama_salesman',
            'nama_cabang'
        );
        $query->addSelect(DB::raw('(SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) as total_bruto'));
        $query->addSelect(DB::raw('(SELECT SUM(subtotal) FROM marketing_retur_detail
        INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur
        WHERE no_faktur = marketing_penjualan.no_faktur AND jenis_retur="PF") as total_retur'));
        $query->addSelect(DB::raw('(SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE no_faktur = marketing_penjualan.no_faktur) as total_bayar'));
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
                    MAX(id) AS id,
                    no_faktur,
                    marketing_penjualan_movefaktur.kode_salesman_baru AS salesbaru,
                    salesman.kode_cabang AS cabangbaru
                FROM
                    marketing_penjualan_movefaktur
                    INNER JOIN salesman ON marketing_penjualan_movefaktur.kode_salesman_baru = salesman.kode_salesman
                GROUP BY
                    no_faktur,
                    marketing_penjualan_movefaktur.kode_salesman_baru,
                    salesman.kode_cabang
                ) movefaktur ON ( marketing_penjualan.no_faktur = movefaktur.no_faktur)
            ) pindahfaktur"),
            function ($join) {
                $join->on('marketing_penjualan.no_faktur', '=', 'pindahfaktur.no_faktur');
            }
        );

        $query->join('salesman', 'pindahfaktur.kode_salesman_baru', '=', 'salesman.kode_salesman');
        $query->join('cabang', 'pindahfaktur.kode_cabang_baru', '=', 'cabang.kode_cabang');

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('marketing_penjualan.tanggal', [$request->dari, $request->sampai]);
        } else {
            $query->whereBetween('marketing_penjualan.tanggal', [$start_date, $end_date]);
        }

        if (!empty($request->no_faktur_search)) {
            $query->where('marketing_penjualan.no_faktur', $request->no_faktur_search);
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
            $query->WhereRaw("MATCH(nama_pelanggan) AGAINST('" . $request->nama_pelanggan_search .  "')");
        }

        $query->orderBy('marketing_penjualan.tanggal', 'desc');
        $query->orderBy('marketing_penjualan.no_faktur', 'desc');
        $penjualan = $query->cursorPaginate();
        $penjualan->appends(request()->all());
        $data['penjualan'] = $penjualan;
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('marketing.penjualan.index', $data);
    }

    public function show($no_faktur)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        $data['kepemilikan'] = config('pelanggan.kepemilikan');
        $data['lama_berjualan'] = config('pelanggan.lama_berjualan');
        $data['status_outlet'] = config('pelanggan.status_outlet');
        $data['type_outlet'] = config('pelanggan.type_outlet');
        $data['cara_pembayaran'] = config('pelanggan.cara_pembayaran');
        $data['lama_langganan'] = config('pelanggan.lama_langganan');
        $data['jenis_bayar'] = config('penjualan.jenis_bayar');
        $pnj = new Penjualan();
        $penjualan = $pnj->getFaktur($no_faktur);
        $data['penjualan'] = $penjualan;

        $detailpenjualan = new Penjualan();
        $data['detail'] = $detailpenjualan->getDetailpenjualan($no_faktur);

        $data['retur'] = Detailretur::select(
            'tanggal',
            'marketing_retur_detail.*',
            'jenis_retur',
            'produk_harga.kode_produk',
            'nama_produk',
            'isi_pcs_dus',
            'isi_pcs_pack',
            'subtotal'
        )
            ->join('produk_harga', 'marketing_retur_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur')
            ->where('no_faktur', $no_faktur)
            ->get();

        $data['historibayar'] = Historibayarpenjualan::select(
            'marketing_penjualan_historibayar.*',
            'nama_salesman',
            'marketing_penjualan_historibayar_giro.kode_giro',
            'no_giro',
            'giro_to_cash',
            'nama_voucher'
        )

            ->leftJoin('jenis_voucher', 'marketing_penjualan_historibayar.jenis_voucher', '=', 'jenis_voucher.id')
            ->leftJoin('marketing_penjualan_historibayar_giro', 'marketing_penjualan_historibayar.no_bukti', '=', 'marketing_penjualan_historibayar_giro.no_bukti')
            ->leftJoin('marketing_penjualan_giro', 'marketing_penjualan_historibayar_giro.kode_giro', '=', 'marketing_penjualan_giro.kode_giro')
            ->join('salesman', 'marketing_penjualan_historibayar.kode_salesman', '=', 'salesman.kode_salesman')
            ->where('no_faktur', $no_faktur)
            ->orderBy('created_at', 'desc')
            ->get();

        $data['giro'] = Detailgiro::select(
            'no_giro',
            'marketing_penjualan_giro.tanggal',
            'bank_pengirim',
            'marketing_penjualan_giro_detail.*',
            'jatuh_tempo',
            'status',
            'tanggal_ditolak',
            'keterangan',
            'marketing_penjualan_historibayar.tanggal as tanggal_diterima',
            'marketing_penjualan_historibayar_giro.no_bukti as no_bukti_giro',
            'nama_salesman'
        )
            ->join('marketing_penjualan_giro', 'marketing_penjualan_giro_detail.kode_giro', '=', 'marketing_penjualan_giro.kode_giro')
            ->join('salesman', 'marketing_penjualan_giro.kode_salesman', '=', 'salesman.kode_salesman')
            ->leftJoin('marketing_penjualan_historibayar_giro', 'marketing_penjualan_giro.kode_giro', '=', 'marketing_penjualan_historibayar_giro.kode_giro')
            ->leftJoin('marketing_penjualan_historibayar', 'marketing_penjualan_historibayar_giro.no_bukti', '=', 'marketing_penjualan_historibayar.no_bukti')
            ->where('marketing_penjualan_giro_detail.no_faktur', $no_faktur)
            ->get();

        $data['transfer'] = Detailtransfer::select(
            'marketing_penjualan_transfer_detail.*',
            'marketing_penjualan_transfer.tanggal',
            'bank_pengirim',
            'jatuh_tempo',
            'status',
            'tanggal_ditolak',
            'keterangan',
            'marketing_penjualan_historibayar.tanggal as tanggal_diterima',
            'nama_salesman'
        )
            ->join('marketing_penjualan_transfer', 'marketing_penjualan_transfer_detail.kode_transfer', '=', 'marketing_penjualan_transfer.kode_transfer')
            ->join('salesman', 'marketing_penjualan_transfer.kode_salesman', '=', 'salesman.kode_salesman')
            ->leftJoin('marketing_penjualan_historibayar_transfer', 'marketing_penjualan_transfer.kode_transfer', '=', 'marketing_penjualan_historibayar_transfer.kode_transfer')
            ->leftJoin('marketing_penjualan_historibayar', 'marketing_penjualan_historibayar_transfer.no_bukti', '=', 'marketing_penjualan_historibayar.no_bukti')
            ->where('marketing_penjualan_transfer_detail.no_faktur', $no_faktur)
            ->get();

        //dd($data['detail']);
        $data['checkin'] = Checkinpenjualan::where('tanggal', $penjualan->tanggal)->where('kode_pelanggan', $penjualan->kode_pelanggan)->first();
        return view('marketing.penjualan.show', $data);
    }


    public function cetakfaktur($no_faktur)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        $pnj = new Penjualan();
        $penjualan = $pnj->getFaktur($no_faktur);
        $data['penjualan'] = $penjualan;

        $detailpenjualan = new Penjualan();
        $data['detail'] = $detailpenjualan->getDetailpenjualan($no_faktur);

        return view('marketing.penjualan.cetakfaktur', $data);
    }


    public function cetaksuratjalan($type, $no_faktur)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        $pnj = new Penjualan();
        $penjualan = $pnj->getFaktur($no_faktur);
        $data['penjualan'] = $penjualan;

        $detailpenjualan = new Penjualan();
        $data['detail'] = $detailpenjualan->getDetailpenjualan($no_faktur);
        if ($type == 1) {
            return view('marketing.penjualan.cetaksuratjalan1', $data);
        } else {
            return view('marketing.penjualan.cetaksuratjalan2', $data);
        }
    }


    public function filtersuratjalan()
    {
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('marketing.penjualan.cetaksuratjalan_filter', $data);
    }


    public function cetaksuratjalanrange(Request $request)
    {
        $pnj = new Penjualan();
        $penjualan = $pnj->getFakturwithDetail($request);
        $data['pj'] = $penjualan;

        return view('marketing.penjualan.cetaksuratjalan_range', $data);
    }


    public function batalfaktur($no_faktur)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        $pnj = new Penjualan();
        $data['penjualan'] = $pnj->getFaktur($no_faktur);
        return view('marketing.penjualan.batalkanfaktur', $data);
    }

    public function updatefakturbatal($no_faktur, Request $request)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        try {
            Penjualan::where('no_faktur', $no_faktur)->update([
                'status_batal' => 1,
                'keterangan' => $request->keterangan,
            ]);
            return Redirect::back()->with(messageSuccess('Faktur Berhasil Dibatalkan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function generatefaktur($no_faktur)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        $penjualan = Penjualan::where('no_faktur', $no_faktur)->first();
        $tanggal = $penjualan->tanggal;
        $kode_salesman = $penjualan->kode_salesman;
        //$id_karyawan = "SBDG09";
        $salesman = Salesman::where('kode_salesman', $penjualan->kode_salesman)
            ->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();



        $lastpenjualan = Penjualan::where('kode_salesman', $penjualan->kode_salesman)
            ->where('tanggal', $penjualan->tanggal)
            ->whereRaw('MID(no_faktur,4,2) != "PR"')
            ->orderBy('tanggal', 'desc')->first();

        $lasttanggal = $lastpenjualan != null ? $penjualan->tanggal : date('Y-m-d', strtotime("-3 day", strtotime($penjualan->tanggal)));


        // $start_date = date('Y-m-d', strtotime("-1 month", strtotime(date('Y-m-d'))));
        // $end_date = date('Y-m-t');

        $cekpenjualan = Penjualan::where('kode_salesman', $penjualan->kode_salesman)
            ->where('tanggal', '>=', $penjualan->tanggal)
            ->whereRaw('MID(no_faktur,4,2) != "PR"')
            ->orderBy('no_faktur', 'desc')
            ->first();



        $last_no_faktur = $cekpenjualan != null ? $cekpenjualan->no_faktur : '';


        // echo $lastnofak;
        // die;
        $kode_cabang = $salesman->kode_cabang;
        $kode_faktur = substr($cekpenjualan->no_faktur, 3, 1);
        $nomor_awal = substr($cekpenjualan->no_faktur, 4);
        $jmlchar = strlen($nomor_awal);
        $no_faktur_auto  =  buatkode($last_no_faktur, $kode_cabang . $kode_faktur, $jmlchar);

        $kode_sales = $salesman->kode_sales;
        $kode_pt = $salesman->kode_pt;

        $tahun = date('y', strtotime($penjualan->tanggal));
        $thn = date('Y', strtotime($penjualan->tanggal));

        $start_date = "2024-03-01";
        if ($penjualan->tanggal >= '2024-03-01') {
            $lastransaksi = Penjualan::join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
                ->where('tanggal', '>=', $start_date)
                ->where('kode_sales', $kode_sales)
                ->where('kode_cabang', $kode_cabang)
                ->whereRaw('YEAR(tanggal)="' . $thn . '"')
                ->whereRaw('LEFT(no_faktur,3)="' . $kode_pt . '"')
                ->orderBy('no_faktur', 'desc')
                ->first();
            $last_no_faktur = $lastransaksi != NULL ? $lastransaksi->no_faktur : "";
            $no_faktur_auto = buatkode($last_no_faktur, $kode_pt . $tahun . $kode_sales, 6);
        }

        // echo $no_fak_penj_auto;
        // die;
        try {

            Penjualan::where('no_faktur', $no_faktur)
                ->update([
                    'no_faktur' => $no_faktur_auto
                ]);
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            dd($e);
            return Redirect::back()->with(['warning' => 'No. Faktur Gagal Dibuat']);
        }
    }
}
