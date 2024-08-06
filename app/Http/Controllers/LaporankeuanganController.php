<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Belumsetor;
use App\Models\Cabang;
use App\Models\Coa;
use App\Models\Detailbelumsetor;
use App\Models\Detailgiro;
use App\Models\Giro;
use App\Models\Kuranglebihsetor;
use App\Models\Ledger;
use App\Models\Ledgersetoranpusat;
use App\Models\Logamtokertas;
use App\Models\Saldoawalkasbesar;
use App\Models\Saldoawalledger;
use App\Models\Setoranpenjualan;
use App\Models\Setoranpusat;
use App\Models\Setoranpusatgiro;
use App\Models\Setoranpusattransfer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class LaporankeuanganController extends Controller
{
    public function index()
    {
        $b = new Bank();
        $data['bank'] = $b->getBank()->get();
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        $data['coa'] = Coa::orderby('kode_akun')->get();
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('keuangan.laporan.index', $data);
    }

    public function cetakledger(Request $request)
    {

        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['bank'] = Bank::where('kode_bank', $request->kode_bank_ledger)->first();
        if ($request->formatlaporan == '1') {
            $query = Ledger::query();
            $query->select(
                'keuangan_ledger.*',
                'nama_akun',
                'nama_bank',
                'bank.no_rekening',
                'hrd_jabatan.kategori',
                DB::raw('IFNULL(marketing_penjualan_transfer.tanggal,marketing_penjualan_giro.tanggal) as tanggal_penerimaan')
            );
            $query->join('coa', 'keuangan_ledger.kode_akun', '=', 'coa.kode_akun');
            $query->join('bank', 'keuangan_ledger.kode_bank', '=', 'bank.kode_bank');
            //PJP
            $query->leftJoin('keuangan_ledger_pjp', 'keuangan_ledger.no_bukti', '=', 'keuangan_ledger_pjp.no_bukti');
            $query->leftJoin('keuangan_pjp', 'keuangan_ledger_pjp.no_pinjaman', '=', 'keuangan_pjp.no_pinjaman');
            $query->leftJoin('hrd_karyawan', 'keuangan_pjp.nik', '=', 'hrd_karyawan.nik');
            $query->leftJoin('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');

            //Transfer
            $query->leftJoin('keuangan_ledger_transfer', 'keuangan_ledger.no_bukti', '=', 'keuangan_ledger_transfer.no_bukti');
            $query->leftJoin('marketing_penjualan_transfer', 'keuangan_ledger_transfer.kode_transfer', '=', 'marketing_penjualan_transfer.kode_transfer');

            //Giro
            $query->leftJoin('keuangan_ledger_giro', 'keuangan_ledger.no_bukti', '=', 'keuangan_ledger_giro.no_bukti');
            $query->leftJoin('marketing_penjualan_giro', 'keuangan_ledger_giro.kode_giro', '=', 'marketing_penjualan_giro.kode_giro');

            $query->orderBy('keuangan_ledger.tanggal');
            $query->orderBy('keuangan_ledger.created_at');
            $query->whereBetween('keuangan_ledger.tanggal', [$request->dari, $request->sampai]);
            if ($request->kode_bank_ledger != "") {
                $query->where('keuangan_ledger.kode_bank', $request->kode_bank_ledger);
            }
            if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
                $query->whereBetween('keuangan_ledger.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
            }
            $data['ledger'] = $query->get();

            $data['saldo_awal'] = Saldoawalledger::where('bulan', date('m', strtotime($request->dari)))
                ->where('tahun', date('Y', strtotime($request->dari)))
                ->where('kode_bank', $request->kode_bank_ledger)
                ->first();

            if (isset($_POST['exportButton'])) {
                header("Content-type: application/vnd-ms-excel");
                // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
                header("Content-Disposition: attachment; filename=Ledger $request->dari-$request->sampai.xls");
            }
            return view('keuangan.laporan.ledger_cetak', $data);
        } else {
            $query = Ledger::query();
            $query->select(
                'keuangan_ledger.kode_akun',
                'nama_akun',
                DB::raw('SUM(IF(debet_kredit="D",jumlah,0)) as jmldebet'),
                DB::raw('SUM(IF(debet_kredit="K",jumlah,0)) as jmlkredit')
            );

            $query->join('coa', 'keuangan_ledger.kode_akun', '=', 'coa.kode_akun');
            $query->orderBy('keuangan_ledger.kode_akun');
            $query->whereBetween('keuangan_ledger.tanggal', [$request->dari, $request->sampai]);
            if (!empty($request->kode_bank_ledger)) {
                $query->where('keuangan_ledger.kode_bank', $request->kode_bank_ledger);
            }
            $query->groupBy('keuangan_ledger.kode_akun', 'nama_akun');
            $data['ledger'] = $query->get();
            if (isset($_POST['exportButton'])) {
                header("Content-type: application/vnd-ms-excel");
                // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
                header("Content-Disposition: attachment; filename=Rekap Ledger $request->dari-$request->sampai.xls");
            }
            return view('keuangan.laporan.rekapledger_cetak', $data);
        }
    }

    public function cetaksaldokasbesar(Request $request)
    {
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang_saldokasbesar;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang_saldokasbesar;
        }

        $setoran_dari = $request->tahun . "-" . $request->bulan . "-01";
        $setoran_sampai = date('Y-m-t', strtotime($setoran_dari));
        $tgl_akhir_setoran = $setoran_sampai;
        $tgl_awal_setoran = $setoran_dari;

        $nextbulan = getbulandantahunberikutnya($request->bulan, $request->tahun, "bulan");
        $nexttahun = getbulandantahunberikutnya($request->bulan, $request->tahun, "tahun");

        $lastbulan = getbulandantahunlalu($request->bulan, $request->tahun, "bulan");
        $lasttahun = getbulandantahunlalu($request->bulan, $request->tahun, "tahun");


        //Jika Ada Setoran Omset Bulan Ini yang disetorkan di Bulan Berikutnya
        $ceksetordibulanberikutnya = Setoranpusat::where('omset_bulan', $request->bulan)->where('omset_tahun', $request->tahun)
            ->select('keuangan_ledger.tanggal as tanggal')
            ->leftJoin('keuangan_ledger_setoranpusat', 'keuangan_setoranpusat.kode_setoran', '=', 'keuangan_ledger_setoranpusat.kode_setoran')
            ->leftJoin('keuangan_ledger', 'keuangan_ledger_setoranpusat.no_bukti', '=', 'keuangan_ledger.no_bukti')
            ->whereRaw('MONTH(keuangan_ledger.tanggal) = ' . $nextbulan)
            ->whereRaw('YEAR(keuangan_ledger.tanggal) = ' . $nexttahun)
            ->where('kode_cabang', $kode_cabang)
            ->orderBy('keuangan_ledger.tanggal', 'desc')
            ->first();

        if ($ceksetordibulanberikutnya) {
            $setoran_sampai = $ceksetordibulanberikutnya->tanggal;
        }


        //Jika Ada Setoran Omset Bulan Ini yang disetorkan di Bulan Lalu
        $ceksetordibulanlalu = Setoranpusat::where('omset_bulan', $request->bulan)->where('omset_tahun', $request->tahun)
            ->select('keuangan_ledger.tanggal as tanggal')
            ->leftJoin('keuangan_ledger_setoranpusat', 'keuangan_setoranpusat.kode_setoran', '=', 'keuangan_ledger_setoranpusat.kode_setoran')
            ->leftJoin('keuangan_ledger', 'keuangan_ledger_setoranpusat.no_bukti', '=', 'keuangan_ledger.no_bukti')
            ->whereRaw('MONTH(keuangan_ledger.tanggal) = ' . $lastbulan)
            ->whereRaw('YEAR(keuangan_ledger.tanggal) = ' . $lasttahun)
            ->where('kode_cabang', $kode_cabang)
            ->orderBy('keuangan_ledger.tanggal', 'desc')
            ->first();

        if ($ceksetordibulanlalu) {
            $setoran_dari = $ceksetordibulanlalu->tanggal;
        }

        $data['saldo_awal'] = Saldoawalkasbesar::where('kode_cabang', $kode_cabang)
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->first();


        $q_lhp = Setoranpenjualan::select(
            'keuangan_setoranpenjualan.tanggal as tanggal',
            DB::raw("SUM(setoran_kertas) as lhp_kertas"),
            DB::raw("SUM(setoran_logam) as lhp_logam"),
            DB::raw("SUM(setoran_giro) as lhp_giro"),
            DB::raw("SUM(setoran_transfer) as lhp_transfer"),
            DB::raw("SUM(setoran_lainnya) as lhp_lainnya"),
            DB::raw("SUM(giro_to_cash) as lhp_giro_to_cash"),
            DB::raw("SUM(giro_to_transfer) as lhp_giro_to_transfer"),
            DB::raw("0 as kurang_logam"),
            DB::raw("0 as kurang_kertas"),
            DB::raw("0 as lebih_logam"),
            DB::raw("0 as lebih_kertas"),
            DB::raw("0 as setoran_kertas"),
            DB::raw("0 as setoran_logam"),
            DB::raw("0 as setoran_giro"),
            DB::raw("0 as setoran_transfer"),
            DB::raw("0 as setoran_lainnya"),
            DB::raw("0 as logamtokertas")
        )
            ->join('salesman', 'keuangan_setoranpenjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->whereBetween('keuangan_setoranpenjualan.tanggal', [$tgl_awal_setoran, $tgl_akhir_setoran])
            ->where('salesman.kode_cabang', $kode_cabang)
            ->groupBy('keuangan_setoranpenjualan.tanggal');



        $q_kuranglebihsetor = Kuranglebihsetor::select(
            'keuangan_kuranglebihsetor.tanggal as tanggal',
            DB::raw("0 as lhp_kertas"),
            DB::raw("0 as lhp_logam"),
            DB::raw("0 as lhp_giro"),
            DB::raw("0 as lhp_transfer"),
            DB::raw("0 as lhp_lainnya"),
            DB::raw("0 as lhp_giro_to_cash"),
            DB::raw("0 as lhp_giro_to_transfer"),
            DB::raw("SUM(IF(jenis_bayar='1',uang_logam,0)) as kurang_logam"),
            DB::raw("SUM(IF(jenis_bayar='1',uang_kertas,0)) as kurang_kertas"),
            DB::raw("SUM(IF(jenis_bayar='2',uang_logam,0)) as lebih_logam"),
            DB::raw("SUM(IF(jenis_bayar='2',uang_kertas,0)) as lebih_kertas"),
            DB::raw("0 as setoran_kertas"),
            DB::raw("0 as setoran_logam"),
            DB::raw("0 as setoran_giro"),
            DB::raw("0 as setoran_transfer"),
            DB::raw("0 as setoran_lainnya"),
            DB::raw("0 as logamtokertas")
        )
            ->join('salesman', 'keuangan_kuranglebihsetor.kode_salesman', '=', 'salesman.kode_salesman')
            ->whereBetween('tanggal', [$tgl_awal_setoran, $tgl_akhir_setoran])
            ->where('salesman.kode_cabang', $kode_cabang)
            ->groupBy('keuangan_kuranglebihsetor.tanggal');

        $q_setoranpusat = Setoranpusat::select(
            'keuangan_setoranpusat.tanggal as tanggal',
            DB::raw("0 as lhp_kertas"),
            DB::raw("0 as lhp_logam"),
            DB::raw("0 as lhp_giro"),
            DB::raw("0 as lhp_transfer"),
            DB::raw("0 as lhp_lainnya"),
            DB::raw("0 as lhp_giro_to_cash"),
            DB::raw("0 as lhp_giro_to_transfer"),
            DB::raw("0 as kurang_logam"),
            DB::raw("0 as kurang_kertas"),
            DB::raw("0 as lebih_logam"),
            DB::raw("0 as lebih_kertas"),
            DB::raw("SUM(setoran_kertas) as setoran_kertas"),
            DB::raw("SUM(setoran_logam) as setoran_logam"),
            DB::raw("SUM(setoran_giro) as setoran_giro"),
            DB::raw("SUM(setoran_transfer) as setoran_transfer"),
            DB::raw("SUM(setoran_lainnya) as setoran_lainnya"),
            DB::raw("0 as logamtokertas")
        )
            ->whereBetween('keuangan_setoranpusat.tanggal', [$setoran_dari, $setoran_sampai])
            ->where('keuangan_setoranpusat.kode_cabang', $kode_cabang)
            ->where('keuangan_setoranpusat.status', '1')
            ->where('omset_bulan', $request->bulan)
            ->where('omset_tahun', $request->tahun)
            ->groupBy('keuangan_setoranpusat.tanggal');


        $q_logamtokertas = Logamtokertas::select(
            'keuangan_logamtokertas.tanggal',
            DB::raw("0 as lhp_kertas"),
            DB::raw("0 as lhp_logam"),
            DB::raw("0 as lhp_giro"),
            DB::raw("0 as lhp_transfer"),
            DB::raw("0 as lhp_lainnya"),
            DB::raw("0 as lhp_giro_to_cash"),
            DB::raw("0 as lhp_giro_to_transfer"),
            DB::raw("0 as kurang_logam"),
            DB::raw("0 as kurang_kertas"),
            DB::raw("0 as lebih_logam"),
            DB::raw("0 as lebih_kertas"),
            DB::raw("0 as setoran_kertas"),
            DB::raw("0 as setoran_logam"),
            DB::raw("0 as setoran_giro"),
            DB::raw("0 as setoran_transfer"),
            DB::raw("0 as setoran_lainnya"),
            DB::raw("SUM(jumlah) as logamtokertas")
        )
            ->whereBetween('keuangan_logamtokertas.tanggal', [$setoran_dari, $tgl_akhir_setoran])
            ->where('keuangan_logamtokertas.kode_cabang', $kode_cabang)
            ->groupBy('keuangan_logamtokertas.tanggal');

        $unionquery = $q_lhp->unionAll($q_kuranglebihsetor)->unionAll($q_setoranpusat)->unionAll($q_logamtokertas)->get();
        $data['saldokasbesar'] = $unionquery->groupBy('tanggal')
            ->map(function ($item) {
                return [
                    'tanggal' => $item->first()->tanggal,
                    'lhp_kertas' => $item->sum('lhp_kertas'),
                    'lhp_logam' => $item->sum('lhp_logam'),
                    'lhp_giro' => $item->sum('lhp_giro'),
                    'lhp_transfer' => $item->sum('lhp_transfer'),
                    'lhp_lainnya' => $item->sum('lhp_lainnya'),
                    'lhp_giro_to_cash' => $item->sum('lhp_giro_to_cash'),
                    'lhp_giro_to_transfer' => $item->sum('lhp_giro_to_transfer'),
                    'kurang_logam' => $item->sum('kurang_logam'),
                    'kurang_kertas' => $item->sum('kurang_kertas'),
                    'lebih_logam' => $item->sum('lebih_logam'),
                    'lebih_kertas' => $item->sum('lebih_kertas'),
                    'setoran_kertas' => $item->sum('setoran_kertas'),
                    'setoran_logam' => $item->sum('setoran_logam'),
                    'setoran_giro' => $item->sum('setoran_giro'),
                    'setoran_transfer' => $item->sum('setoran_transfer'),
                    'setoran_lainnya' => $item->sum('setoran_lainnya'),
                    'logamtokertas' => $item->sum('logamtokertas'),

                ];
            })
            ->sortBy('tanggal')
            ->values()
            ->all();
        $data['bulan'] = $request->bulan;
        $data['tahun'] = $request->tahun;
        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Saldo Kas Besar $request->bulan-$request->tahun.xls");
        }
        return view('keuangan.laporan.saldokasbesar_cetak', $data);
    }


    public function cetaklpu(Request $request)
    {
        if ($request->formatlaporan == '1') {
            return $this->cetaklpusetoranpenjualan($request);
        } else if ($request->formatlaporan == '2') {
            return $this->cetaklpulhpsetoranpusat($request);
        }
    }

    public function cetaklpusetoranpenjualan(Request $request)
    {
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang_lpu;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang_lpu;
        }

        $setoran_dari = $request->tahun . "-" . $request->bulan . "-01";
        $setoran_sampai = date('Y-m-t', strtotime($setoran_dari));
        $tgl_awal_setoran = $setoran_dari;
        $tgl_akhir_setoran = $setoran_sampai;


        $nextbulan = getbulandantahunberikutnya($request->bulan, $request->tahun, "bulan");
        $nexttahun = getbulandantahunberikutnya($request->bulan, $request->tahun, "tahun");

        $lastbulan = getbulandantahunlalu($request->bulan, $request->tahun, "bulan");
        $lasttahun = getbulandantahunlalu($request->bulan, $request->tahun, "tahun");
        $dari_lastbulan = $lasttahun . "-" . $lastbulan . "-01";
        $sampai_lastbulan = date('Y-m-t', strtotime($dari_lastbulan));

        $lastduabulan = getbulandantahunlalu($lastbulan, $lasttahun, "bulan");
        $lastduabulantahun = getbulandantahunlalu($lastbulan, $lasttahun, "tahun");
        $dari_lastduabulan = $lastduabulantahun . "-" . $lastduabulan . "-01";
        $sampai_lastduabulan = date('Y-m-t', strtotime($dari_lastduabulan));


        //Jika Ada Setoran Omset Bulan Ini yang disetorkan di Bulan Berikutnya
        $ceksetordibulanberikutnya = Setoranpusat::where('omset_bulan', $request->bulan)->where('omset_tahun', $request->tahun)
            ->select('keuangan_ledger.tanggal as tanggal')
            ->leftJoin('keuangan_ledger_setoranpusat', 'keuangan_setoranpusat.kode_setoran', '=', 'keuangan_ledger_setoranpusat.kode_setoran')
            ->leftJoin('keuangan_ledger', 'keuangan_ledger_setoranpusat.no_bukti', '=', 'keuangan_ledger.no_bukti')
            ->whereRaw('MONTH(keuangan_ledger.tanggal) = ' . $nextbulan)
            ->whereRaw('YEAR(keuangan_ledger.tanggal) = ' . $nexttahun)
            ->where('kode_cabang', $kode_cabang)
            ->orderBy('keuangan_ledger.tanggal', 'desc')
            ->first();

        if ($ceksetordibulanberikutnya) {
            $setoran_sampai = $ceksetordibulanberikutnya->tanggal;
        }


        //Jika Ada Setoran Omset Bulan Ini yang disetorkan di Bulan Lalu
        $ceksetordibulanlalu = Setoranpusat::where('omset_bulan', $request->bulan)->where('omset_tahun', $request->tahun)
            ->select('keuangan_ledger.tanggal as tanggal')
            ->leftJoin('keuangan_ledger_setoranpusat', 'keuangan_setoranpusat.kode_setoran', '=', 'keuangan_ledger_setoranpusat.kode_setoran')
            ->leftJoin('keuangan_ledger', 'keuangan_ledger_setoranpusat.no_bukti', '=', 'keuangan_ledger.no_bukti')
            ->whereRaw('MONTH(keuangan_ledger.tanggal) = ' . $lastbulan)
            ->whereRaw('YEAR(keuangan_ledger.tanggal) = ' . $lasttahun)
            ->where('kode_cabang', $kode_cabang)
            ->orderBy('keuangan_ledger.tanggal', 'desc')
            ->first();

        if ($ceksetordibulanlalu) {
            $setoran_dari = $ceksetordibulanlalu->tanggal;
        }

        $salesman = Setoranpenjualan::select('keuangan_setoranpenjualan.kode_salesman', 'nama_salesman')
            ->join('salesman', 'keuangan_setoranpenjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->where('salesman.kode_cabang', $kode_cabang)
            ->whereBetween('keuangan_setoranpenjualan.tanggal', [$tgl_awal_setoran, $setoran_sampai])
            ->orderBy('salesman.nama_salesman')
            ->groupBy('keuangan_setoranpenjualan.kode_salesman', 'nama_salesman')
            ->get();

        $selectColumnLhp = [];
        $selectColumnSetoran = [];
        $selectColumnGiro = [];
        $selectColumbelumsetor = [];

        foreach ($salesman as $d) {
            $selectColumnLhp[] = DB::raw("SUM(IF(salesman.kode_salesman = '$d->kode_salesman', lhp_tunai + lhp_tagihan, 0)) as lhp_" . $d->kode_salesman);
            $selectColumnSetoran[] = DB::raw("SUM(IF(salesman.kode_salesman = '$d->kode_salesman', setoran_kertas + setoran_logam + setoran_transfer + setoran_giro + setoran_lainnya, 0)) as setoran_" . $d->kode_salesman);
            $selectColumnGiro[] = DB::raw("SUM(IF(IFNULL(historibayar.kode_salesman,marketing_penjualan_giro.kode_salesman) = '$d->kode_salesman', jumlah, 0)) as giro_" . $d->kode_salesman);
            $selectColumbelumsetor[] = DB::raw("SUM(IF(keuangan_belumsetor_detail.kode_salesman = '$d->kode_salesman', jumlah, 0)) as belumetor_" . $d->kode_salesman);
        }

        $data['lpu'] = Setoranpenjualan::select('keuangan_setoranpenjualan.tanggal', ...$selectColumnLhp, ...$selectColumnSetoran)
            ->join('salesman', 'keuangan_setoranpenjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->where('salesman.kode_cabang', $kode_cabang)
            ->whereBetween('keuangan_setoranpenjualan.tanggal', [$tgl_awal_setoran, $setoran_sampai])
            ->groupBy('keuangan_setoranpenjualan.tanggal')
            ->get();

        //Giro Bulan Lalu Cair Bulan Ini
        $girobulanlalu = Detailgiro::join('marketing_penjualan_giro', 'marketing_penjualan_giro_detail.kode_giro', '=', 'marketing_penjualan_giro.kode_giro')
            ->join('salesman', 'marketing_penjualan_giro.kode_salesman', '=', 'salesman.kode_salesman')
            ->leftJoin(
                DB::raw("(SELECT kode_giro,kode_salesman,marketing_penjualan_historibayar.tanggal
            FROM marketing_penjualan_historibayar_giro
            INNER JOIN marketing_penjualan_historibayar ON marketing_penjualan_historibayar_giro.no_bukti = marketing_penjualan_historibayar.no_bukti
            GROUP BY kode_giro,kode_salesman,marketing_penjualan_historibayar.tanggal
            ) historibayar"),
                function ($join) {
                    $join->on('marketing_penjualan_giro.kode_giro', '=', 'historibayar.kode_giro');
                }
            )
            ->select(...$selectColumnGiro)
            ->whereBetween('marketing_penjualan_giro.tanggal', [$dari_lastduabulan, $sampai_lastbulan])
            ->where('salesman.kode_cabang', $kode_cabang)
            ->where('omset_bulan', $request->bulan)
            ->where('omset_tahun', $request->tahun)
            ->orWherebetween('marketing_penjualan_giro.tanggal', [$dari_lastbulan, $sampai_lastbulan])
            ->whereBetween('historibayar.tanggal', [$tgl_awal_setoran, $tgl_akhir_setoran])
            ->where('salesman.kode_cabang', $kode_cabang)
            ->first();


        //Giro Bulan Ini Yang Tidak Cair

        $girobulanini = Detailgiro::join('marketing_penjualan_giro', 'marketing_penjualan_giro_detail.kode_giro', '=', 'marketing_penjualan_giro.kode_giro')
            ->join('salesman', 'marketing_penjualan_giro.kode_salesman', '=', 'salesman.kode_salesman')
            ->leftJoin(
                DB::raw("(SELECT kode_giro,kode_salesman,marketing_penjualan_historibayar.tanggal
            FROM marketing_penjualan_historibayar_giro
            INNER JOIN marketing_penjualan_historibayar ON marketing_penjualan_historibayar_giro.no_bukti = marketing_penjualan_historibayar.no_bukti
            GROUP BY kode_giro,kode_salesman,marketing_penjualan_historibayar.tanggal
            ) historibayar"),
                function ($join) {
                    $join->on('marketing_penjualan_giro.kode_giro', '=', 'historibayar.kode_giro');
                }
            )
            ->select(...$selectColumnGiro)
            ->whereBetween('marketing_penjualan_giro.tanggal', [$tgl_awal_setoran, $tgl_akhir_setoran])
            ->where('salesman.kode_cabang', $kode_cabang)
            ->whereNull('historibayar.tanggal')
            ->whereNull('omset_bulan')
            ->whereNull('omset_tahun')
            ->whereNull('penggantian')
            ->orWherebetween('marketing_penjualan_giro.tanggal', [$tgl_awal_setoran, $tgl_akhir_setoran])
            ->where('historibayar.tanggal', '>', $tgl_akhir_setoran)
            ->where('salesman.kode_cabang', $kode_cabang)
            //Tambahkan Where Jika $request->bulan == 12
            ->where(function ($query) use ($request) {
                if ($request->bulan == 12) {
                    $query->where('omset_bulan', '>=', 1);
                    $query->where('omset_tahun', '>=', $request->tahun);
                } else {
                    $query->where('omset_bulan', '>', $request->bulan);
                    $query->where('omset_tahun', '>=', $request->tahun);
                }
            })
            ->whereNull('penggantian')
            ->orWherebetween('marketing_penjualan_giro.tanggal', [$tgl_awal_setoran, $tgl_akhir_setoran])
            ->whereNull('historibayar.tanggal')
            ->where('salesman.kode_cabang', $kode_cabang)
            ->where(function ($query) use ($request) {
                if ($request->bulan == 12) {
                    $query->where('omset_bulan', '>=', 1);
                    $query->where('omset_tahun', '>=', $request->tahun);
                } else {
                    $query->where('omset_bulan', '>', $request->bulan);
                    $query->where('omset_tahun', '>=', $request->tahun);
                }
            })
            ->where('penggantian', 1)
            ->orWherebetween('marketing_penjualan_giro.tanggal', [$tgl_awal_setoran, $tgl_akhir_setoran])
            ->where('historibayar.tanggal', '>', $tgl_akhir_setoran)
            ->where('salesman.kode_cabang', $kode_cabang)
            ->whereNull('omset_bulan')
            ->whereNull('omset_tahun')
            ->whereNull('penggantian')
            ->first();

        $belumsetorbulanini = Detailbelumsetor::select(...$selectColumbelumsetor)
            ->join('keuangan_belumsetor', 'keuangan_belumsetor_detail.kode_belumsetor', '=', 'keuangan_belumsetor.kode_belumsetor')
            ->where('kode_cabang', $kode_cabang)
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->first();

        $belumsetorbulanlalu = Detailbelumsetor::select(...$selectColumbelumsetor)
            ->join('keuangan_belumsetor', 'keuangan_belumsetor_detail.kode_belumsetor', '=', 'keuangan_belumsetor.kode_belumsetor')
            ->where('kode_cabang', $kode_cabang)
            ->where('bulan', $lastbulan)
            ->where('tahun', $lasttahun)
            ->first();
        $data['girobulanlalu'] = $girobulanlalu;
        $data['girobulanini'] = $girobulanini;
        $data['belumsetorbulanini'] = $belumsetorbulanini;
        $data['belumsetorbulanlalu'] = $belumsetorbulanlalu;
        $data['salesman'] = $salesman;
        $data['bulan'] = $request->bulan;
        $data['tahun'] = $request->tahun;
        $data['lastbulan'] = $lastbulan;
        $data['lasttahun'] = $lasttahun;
        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();
        return view('keuangan.laporan.lpu_cetak', $data);
    }


    public function cetaklpulhpsetoranpusat(Request $request)
    {
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang_lpu;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang_lpu;
        }

        $setoran_dari = $request->tahun . "-" . $request->bulan . "-01";
        $setoran_sampai = date('Y-m-t', strtotime($setoran_dari));
        $tgl_awal_setoran = $setoran_dari;
        $tgl_akhir_setoran = $setoran_sampai;


        $nextbulan = getbulandantahunberikutnya($request->bulan, $request->tahun, "bulan");
        $nexttahun = getbulandantahunberikutnya($request->bulan, $request->tahun, "tahun");

        $lastbulan = getbulandantahunlalu($request->bulan, $request->tahun, "bulan");
        $lasttahun = getbulandantahunlalu($request->bulan, $request->tahun, "tahun");
        $dari_lastbulan = $lasttahun . "-" . $lastbulan . "-01";
        $sampai_lastbulan = date('Y-m-t', strtotime($dari_lastbulan));

        $lastduabulan = getbulandantahunlalu($lastbulan, $lasttahun, "bulan");
        $lastduabulantahun = getbulandantahunlalu($lastbulan, $lasttahun, "tahun");
        $dari_lastduabulan = $lastduabulantahun . "-" . $lastduabulan . "-01";
        $sampai_lastduabulan = date('Y-m-t', strtotime($dari_lastduabulan));


        //Jika Ada Setoran Omset Bulan Ini yang disetorkan di Bulan Berikutnya
        $ceksetordibulanberikutnya = Setoranpusat::where('omset_bulan', $request->bulan)->where('omset_tahun', $request->tahun)
            ->select('keuangan_ledger.tanggal as tanggal')
            ->leftJoin('keuangan_ledger_setoranpusat', 'keuangan_setoranpusat.kode_setoran', '=', 'keuangan_ledger_setoranpusat.kode_setoran')
            ->leftJoin('keuangan_ledger', 'keuangan_ledger_setoranpusat.no_bukti', '=', 'keuangan_ledger.no_bukti')
            ->whereRaw('MONTH(keuangan_ledger.tanggal) = ' . $nextbulan)
            ->whereRaw('YEAR(keuangan_ledger.tanggal) = ' . $nexttahun)
            ->where('kode_cabang', $kode_cabang)
            ->orderBy('keuangan_ledger.tanggal', 'desc')
            ->first();

        if ($ceksetordibulanberikutnya) {
            $setoran_sampai = $ceksetordibulanberikutnya->tanggal;
        }


        //Jika Ada Setoran Omset Bulan Ini yang disetorkan di Bulan Lalu
        $ceksetordibulanlalu = Setoranpusat::where('omset_bulan', $request->bulan)->where('omset_tahun', $request->tahun)
            ->select('keuangan_ledger.tanggal as tanggal')
            ->leftJoin('keuangan_ledger_setoranpusat', 'keuangan_setoranpusat.kode_setoran', '=', 'keuangan_ledger_setoranpusat.kode_setoran')
            ->leftJoin('keuangan_ledger', 'keuangan_ledger_setoranpusat.no_bukti', '=', 'keuangan_ledger.no_bukti')
            ->whereRaw('MONTH(keuangan_ledger.tanggal) = ' . $lastbulan)
            ->whereRaw('YEAR(keuangan_ledger.tanggal) = ' . $lasttahun)
            ->where('kode_cabang', $kode_cabang)
            ->orderBy('keuangan_ledger.tanggal', 'desc')
            ->first();

        if ($ceksetordibulanlalu) {
            $setoran_dari = $ceksetordibulanlalu->tanggal;
        }

        $salesman = Setoranpenjualan::select('keuangan_setoranpenjualan.kode_salesman', 'nama_salesman')
            ->join('salesman', 'keuangan_setoranpenjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->where('salesman.kode_cabang', $kode_cabang)
            ->whereBetween('keuangan_setoranpenjualan.tanggal', [$tgl_awal_setoran, $setoran_sampai])
            ->orderBy('salesman.nama_salesman')
            ->groupBy('keuangan_setoranpenjualan.kode_salesman', 'nama_salesman')
            ->get();

        $bank = Ledgersetoranpusat::join('keuangan_ledger', 'keuangan_ledger_setoranpusat.no_bukti', '=', 'keuangan_ledger.no_bukti')
            ->join('keuangan_setoranpusat', 'keuangan_setoranpusat.kode_setoran', '=', 'keuangan_ledger_setoranpusat.kode_setoran')
            ->join('bank', 'keuangan_ledger.kode_bank', '=', 'bank.kode_bank')
            ->select('keuangan_ledger.kode_bank', 'bank.nama_bank')
            ->where('omset_bulan', $request->bulan)
            ->where('omset_tahun', $request->tahun)
            ->where('keuangan_setoranpusat.kode_cabang', $kode_cabang)
            ->where('status', '1')
            ->whereBetween('keuangan_ledger.tanggal', [$tgl_awal_setoran, $setoran_sampai])
            ->groupBy('keuangan_ledger.kode_bank')
            ->get();



        $selectColumnLhp = [];
        $selectColumnLhpsetoranpusat = [];
        $selectColumnGiro = [];
        $selectColumbelumsetor = [];
        $selectColumnsetoranpusat = [];
        $selectColumnsetoranpusatlhp = [];


        foreach ($salesman as $d) {
            $selectColumnLhp[] = DB::raw("SUM(IF(salesman.kode_salesman = '$d->kode_salesman', lhp_tunai + lhp_tagihan, 0)) as lhp_" . $d->kode_salesman);
            $selectColumnLhpsetoranpusat[] = DB::raw("0 as lhp_" . $d->kode_salesman);
            $selectColumnGiro[] = DB::raw("SUM(IF(IFNULL(historibayar.kode_salesman,marketing_penjualan_giro.kode_salesman) = '$d->kode_salesman', jumlah, 0)) as giro_" . $d->kode_salesman);
            $selectColumbelumsetor[] = DB::raw("SUM(IF(keuangan_belumsetor_detail.kode_salesman = '$d->kode_salesman', jumlah, 0)) as belumetor_" . $d->kode_salesman);
        }


        foreach ($bank as $d) {
            $selectColumnsetoranpusat[] = DB::raw("SUM(IF(keuangan_ledger.kode_bank = '$d->kode_bank', setoran_kertas + setoran_logam + setoran_transfer + setoran_giro + setoran_lainnya, 0)) as setoranpusat_" . $d->kode_bank);
            $selectColumnsetoranpusatlhp[] = DB::raw("0 as setoranpusat_" . $d->kode_bank);
        }

        $q_lhp = Setoranpenjualan::select('keuangan_setoranpenjualan.tanggal', ...$selectColumnLhp, ...$selectColumnsetoranpusatlhp)
            ->join('salesman', 'keuangan_setoranpenjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->where('salesman.kode_cabang', $kode_cabang)
            ->whereBetween('keuangan_setoranpenjualan.tanggal', [$tgl_awal_setoran, $setoran_sampai])
            ->groupBy('keuangan_setoranpenjualan.tanggal')
            ->orderBy('keuangan_setoranpenjualan.tanggal')
            ->get();

        $q_setoranpusat = Setoranpusat::select('keuangan_ledger.tanggal', ...$selectColumnLhpsetoranpusat, ...$selectColumnsetoranpusat)
            ->leftjoin('keuangan_ledger_setoranpusat', 'keuangan_setoranpusat.kode_setoran', '=', 'keuangan_ledger_setoranpusat.kode_setoran')
            ->leftjoin('keuangan_ledger', 'keuangan_ledger_setoranpusat.no_bukti', '=', 'keuangan_ledger.no_bukti')
            ->whereBetween('keuangan_ledger.tanggal', [$tgl_awal_setoran, $setoran_sampai])
            ->where('keuangan_setoranpusat.kode_cabang', $kode_cabang)
            ->groupBy('keuangan_ledger.tanggal')
            ->orderBy('keuangan_ledger.tanggal')
            ->get();


        $data['lhp'] = $q_lhp;
        $data['setoranpusat'] = $q_setoranpusat;


        //Giro Bulan Lalu Cair Bulan Ini
        $girobulanlalu = Detailgiro::join('marketing_penjualan_giro', 'marketing_penjualan_giro_detail.kode_giro', '=', 'marketing_penjualan_giro.kode_giro')
            ->join('salesman', 'marketing_penjualan_giro.kode_salesman', '=', 'salesman.kode_salesman')
            ->leftJoin(
                DB::raw("(SELECT kode_giro,kode_salesman,marketing_penjualan_historibayar.tanggal
            FROM marketing_penjualan_historibayar_giro
            INNER JOIN marketing_penjualan_historibayar ON marketing_penjualan_historibayar_giro.no_bukti = marketing_penjualan_historibayar.no_bukti
            GROUP BY kode_giro,kode_salesman,marketing_penjualan_historibayar.tanggal
            ) historibayar"),
                function ($join) {
                    $join->on('marketing_penjualan_giro.kode_giro', '=', 'historibayar.kode_giro');
                }
            )
            ->select(...$selectColumnGiro)
            ->whereBetween('marketing_penjualan_giro.tanggal', [$dari_lastduabulan, $sampai_lastbulan])
            ->where('salesman.kode_cabang', $kode_cabang)
            ->where('omset_bulan', $request->bulan)
            ->where('omset_tahun', $request->tahun)
            ->orWherebetween('marketing_penjualan_giro.tanggal', [$dari_lastbulan, $sampai_lastbulan])
            ->whereBetween('historibayar.tanggal', [$tgl_awal_setoran, $tgl_akhir_setoran])
            ->where('salesman.kode_cabang', $kode_cabang)
            ->first();


        //Giro Bulan Ini Yang Tidak Cair

        $girobulanini = Detailgiro::join('marketing_penjualan_giro', 'marketing_penjualan_giro_detail.kode_giro', '=', 'marketing_penjualan_giro.kode_giro')
            ->join('salesman', 'marketing_penjualan_giro.kode_salesman', '=', 'salesman.kode_salesman')
            ->leftJoin(
                DB::raw("(SELECT kode_giro,kode_salesman,marketing_penjualan_historibayar.tanggal
            FROM marketing_penjualan_historibayar_giro
            INNER JOIN marketing_penjualan_historibayar ON marketing_penjualan_historibayar_giro.no_bukti = marketing_penjualan_historibayar.no_bukti
            GROUP BY kode_giro,kode_salesman,marketing_penjualan_historibayar.tanggal
            ) historibayar"),
                function ($join) {
                    $join->on('marketing_penjualan_giro.kode_giro', '=', 'historibayar.kode_giro');
                }
            )
            ->select(...$selectColumnGiro)
            ->whereBetween('marketing_penjualan_giro.tanggal', [$tgl_awal_setoran, $tgl_akhir_setoran])
            ->where('salesman.kode_cabang', $kode_cabang)
            ->whereNull('historibayar.tanggal')
            ->whereNull('omset_bulan')
            ->whereNull('omset_tahun')
            ->whereNull('penggantian')
            ->orWherebetween('marketing_penjualan_giro.tanggal', [$tgl_awal_setoran, $tgl_akhir_setoran])
            ->where('historibayar.tanggal', '>', $tgl_akhir_setoran)
            ->where('salesman.kode_cabang', $kode_cabang)
            //Tambahkan Where Jika $request->bulan == 12
            ->where(function ($query) use ($request) {
                if ($request->bulan == 12) {
                    $query->where('omset_bulan', '>=', 1);
                    $query->where('omset_tahun', '>=', $request->tahun);
                } else {
                    $query->where('omset_bulan', '>', $request->bulan);
                    $query->where('omset_tahun', '>=', $request->tahun);
                }
            })
            ->whereNull('penggantian')
            ->orWherebetween('marketing_penjualan_giro.tanggal', [$tgl_awal_setoran, $tgl_akhir_setoran])
            ->whereNull('historibayar.tanggal')
            ->where('salesman.kode_cabang', $kode_cabang)
            ->where(function ($query) use ($request) {
                if ($request->bulan == 12) {
                    $query->where('omset_bulan', '>=', 1);
                    $query->where('omset_tahun', '>=', $request->tahun);
                } else {
                    $query->where('omset_bulan', '>', $request->bulan);
                    $query->where('omset_tahun', '>=', $request->tahun);
                }
            })
            ->where('penggantian', 1)
            ->orWherebetween('marketing_penjualan_giro.tanggal', [$tgl_awal_setoran, $tgl_akhir_setoran])
            ->where('historibayar.tanggal', '>', $tgl_akhir_setoran)
            ->where('salesman.kode_cabang', $kode_cabang)
            ->whereNull('omset_bulan')
            ->whereNull('omset_tahun')
            ->whereNull('penggantian')
            ->first();

        $belumsetorbulanini = Detailbelumsetor::select(...$selectColumbelumsetor)
            ->join('keuangan_belumsetor', 'keuangan_belumsetor_detail.kode_belumsetor', '=', 'keuangan_belumsetor.kode_belumsetor')
            ->where('kode_cabang', $kode_cabang)
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->first();

        $belumsetorbulanlalu = Detailbelumsetor::select(...$selectColumbelumsetor)
            ->join('keuangan_belumsetor', 'keuangan_belumsetor_detail.kode_belumsetor', '=', 'keuangan_belumsetor.kode_belumsetor')
            ->where('kode_cabang', $kode_cabang)
            ->where('bulan', $lastbulan)
            ->where('tahun', $lasttahun)
            ->first();
        $data['girobulanlalu'] = $girobulanlalu;
        $data['girobulanini'] = $girobulanini;
        $data['belumsetorbulanini'] = $belumsetorbulanini;
        $data['belumsetorbulanlalu'] = $belumsetorbulanlalu;
        $data['salesman'] = $salesman;
        $data['bulan'] = $request->bulan;
        $data['tahun'] = $request->tahun;
        $data['lastbulan'] = $lastbulan;
        $data['lasttahun'] = $lasttahun;
        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();
        $data['bank'] = $bank;
        return view('keuangan.laporan.lpu_setoranpusat_cetak', $data);
    }
}
