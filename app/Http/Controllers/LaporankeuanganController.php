<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Belumsetor;
use App\Models\Cabang;
use App\Models\Coa;
use App\Models\Departemen;
use App\Models\Detailbelumsetor;
use App\Models\Detailgiro;
use App\Models\Giro;
use App\Models\Karyawan;
use App\Models\Kasbon;
use App\Models\Kaskecil;
use App\Models\Kuranglebihsetor;
use App\Models\Ledger;
use App\Models\Ledgersetoranpusat;
use App\Models\Logamtokertas;
use App\Models\Mutasikeuangan;
use App\Models\Piutangkaryawan;
use App\Models\Pjp;
use App\Models\Saldoawalkasbesar;
use App\Models\Saldoawalledger;
use App\Models\Saldoawalmutasikeungan;
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
        $user = User::findorfail(auth()->user()->id);
        $b = new Bank();
        $data['bank'] = $b->getMutasibank()->get();
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();

        $data['coa'] = Coa::orderby('kode_akun')->get();
        if ($user->hasRole(['admin pajak', 'rom'])) {
            $data['coa'] = Coa::orderby('kode_akun')
                ->where(DB::raw('LEFT(kode_akun, 2)'), '5-')
                ->orWhere(DB::raw('LEFT(kode_akun, 2)'), '6-')
                ->orWhere(DB::raw('LEFT(kode_akun, 2)'), '7-')
                ->orWhere(DB::raw('LEFT(kode_akun, 2)'), '8-')
                ->orWhere(DB::raw('LEFT(kode_akun, 2)'), '9-')
                ->get();
        }
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('keuangan.laporan.index', $data);
    }

    public function cetakledger(Request $request)
    {

        $user = User::findorfail(auth()->user()->id);
        if (lockreport($request->dari) == "error" && !$user->hasRole(['admin pajak', 'rom'])) {
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
            if ($user->hasRole(['admin pajak', 'rom'])) {
                $query->whereIn(DB::raw('LEFT(keuangan_ledger.kode_akun, 2)'), ['5-', '6-', '7-', '8-', '9-']);
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
            if ($user->hasRole(['admin pajak', 'rom'])) {
                $query->whereIn(DB::raw('LEFT(keuangan_ledger.kode_akun, 2)'), ['5-', '6-', '7-', '8-', '9-']);
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



    public function cetakrekapledger(Request $request)
    {
        $query = Ledger::query();
        $query->select(
            'keuangan_ledger.kode_bank',
            'nama_bank',
            'no_rekening',
            DB::raw('SUM(IF(debet_kredit="D",jumlah,0)) as jmldebet'),
            DB::raw('SUM(IF(debet_kredit="K",jumlah,0)) as jmlkredit')
        );

        $query->join('bank', 'keuangan_ledger.kode_bank', '=', 'bank.kode_bank');
        $query->whereBetween('keuangan_ledger.tanggal', [$request->dari, $request->sampai]);
        $query->orderBy('nama_bank');
        $query->groupBy('keuangan_ledger.kode_bank', 'nama_bank');
        $data['ledger'] = $query->get();
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Rekap Ledger $request->dari-$request->sampai.xls");
        }
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        return view('keuangan.laporan.rekapledger_all_cetak', $data);
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

        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Laporan Penerimaan Uang (LPU) $request->bulan-$request->tahun.xls");
        }
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
            ->select('keuangan_ledger.kode_bank', 'bank.nama_bank', 'bank.nama_bank_alias')
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
            // $selectColumnLhpsetoranpusat[] = DB::raw("0 as lhp_" . $d->kode_salesman);
            $selectColumnGiro[] = DB::raw("SUM(IF(IFNULL(historibayar.kode_salesman,marketing_penjualan_giro.kode_salesman) = '$d->kode_salesman', jumlah, 0)) as giro_" . $d->kode_salesman);
            $selectColumbelumsetor[] = DB::raw("SUM(IF(keuangan_belumsetor_detail.kode_salesman = '$d->kode_salesman', jumlah, 0)) as belumetor_" . $d->kode_salesman);
        }


        foreach ($bank as $d) {
            $selectColumnsetoranpusat[] = DB::raw("SUM(IF(keuangan_ledger.kode_bank = '$d->kode_bank', setoran_kertas + setoran_logam + setoran_transfer + setoran_giro + setoran_lainnya, 0)) as setoranpusat_" . $d->kode_bank);
            // $selectColumnsetoranpusatlhp[] = DB::raw("0 as setoranpusat_" . $d->kode_bank);
        }

        $q_lhp = Setoranpenjualan::select('keuangan_setoranpenjualan.tanggal', ...$selectColumnLhp)
            ->join('salesman', 'keuangan_setoranpenjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->where('salesman.kode_cabang', $kode_cabang)
            ->whereBetween('keuangan_setoranpenjualan.tanggal', [$tgl_awal_setoran, $setoran_sampai])
            ->groupBy('keuangan_setoranpenjualan.tanggal')
            ->orderBy('keuangan_setoranpenjualan.tanggal')
            ->get();

        $q_setoranpusat = Setoranpusat::select('keuangan_ledger.tanggal', ...$selectColumnsetoranpusat)
            ->leftjoin('keuangan_ledger_setoranpusat', 'keuangan_setoranpusat.kode_setoran', '=', 'keuangan_ledger_setoranpusat.kode_setoran')
            ->leftjoin('keuangan_ledger', 'keuangan_ledger_setoranpusat.no_bukti', '=', 'keuangan_ledger.no_bukti')
            ->whereBetween('keuangan_ledger.tanggal', [$tgl_awal_setoran, $setoran_sampai])
            ->where('keuangan_setoranpusat.kode_cabang', $kode_cabang)
            ->groupBy('keuangan_ledger.tanggal')
            ->orderBy('keuangan_ledger.tanggal')
            ->get();

        // dd($q_setoranpusat);


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

    public function cetakpenjualan(Request $request)
    {


        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang_penjualan;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang_penjualan;
        }

        $salesman = Setoranpenjualan::select('keuangan_setoranpenjualan.kode_salesman', 'nama_salesman')
            ->join('salesman', 'keuangan_setoranpenjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->where('salesman.kode_cabang', $kode_cabang)
            ->whereBetween('keuangan_setoranpenjualan.tanggal', [$request->dari, $request->sampai])
            ->groupBy('keuangan_setoranpenjualan.kode_salesman')
            ->get();

        //dd($salesman);
        $selectColumnsalesman = [];
        foreach ($salesman as $d) {
            $selectColumnsalesman[] = DB::raw("SUM(IF(keuangan_setoranpenjualan.kode_salesman = '$d->kode_salesman',lhp_tunai,0)) as lhptunai_" . $d->kode_salesman);
            $selectColumnsalesman[] = DB::raw("SUM(IF(keuangan_setoranpenjualan.kode_salesman = '$d->kode_salesman',lhp_tagihan,0)) as lhptagihan_" . $d->kode_salesman);
            $selectColumnsalesman[] = DB::raw("SUM(IF(keuangan_setoranpenjualan.kode_salesman = '$d->kode_salesman',lhp_tunai + lhp_tagihan,0)) as lhptotal_" . $d->kode_salesman);
        }


        $data['setoranpenjualan'] = Setoranpenjualan::select('keuangan_setoranpenjualan.tanggal', ...$selectColumnsalesman)
            ->join('salesman', 'keuangan_setoranpenjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->whereBetween('keuangan_setoranpenjualan.tanggal', [$request->dari, $request->sampai])
            ->where('salesman.kode_cabang', $kode_cabang)
            ->groupBy('keuangan_setoranpenjualan.tanggal')
            ->get();



        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();
        $data['salesman'] = $salesman;

        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Rekap LHP Penjualan $request->dari-$request->sampai.xls");
        }
        return view('keuangan.laporan.penjualan_cetak', $data);
    }

    public function cetakuanglogam(Request $request)
    {

        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang_uanglogam;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang_uanglogam;
        }

        $setoran_dari = $request->tahun . "-" . $request->bulan . "-01";
        $setoran_sampai = date('Y-m-t', strtotime($setoran_dari));
        $tgl_akhir_setoran = $setoran_sampai;
        $tgl_awal_setoran = $setoran_dari;

        $nextbulan = getbulandantahunberikutnya($request->bulan, $request->tahun, "bulan");
        $nexttahun = getbulandantahunberikutnya($request->bulan, $request->tahun, "tahun");

        // $lastbulan = getbulandantahunlalu($request->bulan, $request->tahun, "bulan");
        // $lasttahun = getbulandantahunlalu($request->bulan, $request->tahun, "tahun");


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

        $data['saldo_awal'] = Saldoawalkasbesar::where('kode_cabang', $kode_cabang)
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->first();



        $q_lhp = Setoranpenjualan::select(
            'keuangan_setoranpenjualan.tanggal as tanggal',
            DB::raw("SUM(setoran_logam) as lhp_logam"),
            DB::raw("0 as kurang_logam"),
            DB::raw("0 as lebih_logam"),
            DB::raw("0 as setoran_logam"),
            DB::raw("0 as logamtokertas")
        )
            ->join('salesman', 'keuangan_setoranpenjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->whereBetween('keuangan_setoranpenjualan.tanggal', [$tgl_awal_setoran, $tgl_akhir_setoran])
            ->where('salesman.kode_cabang', $kode_cabang)
            ->groupBy('keuangan_setoranpenjualan.tanggal');

        $q_kuranglebihsetor = Kuranglebihsetor::select(
            'keuangan_kuranglebihsetor.tanggal as tanggal',
            DB::raw("0 as lhp_logam"),
            DB::raw("SUM(IF(jenis_bayar='1',uang_logam,0)) as kurang_logam"),
            DB::raw("SUM(IF(jenis_bayar='2',uang_logam,0)) as lebih_logam"),
            DB::raw("0 as setoran_logam"),
            DB::raw("0 as logamtokertas")
        )
            ->join('salesman', 'keuangan_kuranglebihsetor.kode_salesman', '=', 'salesman.kode_salesman')
            ->whereBetween('tanggal', [$tgl_awal_setoran, $tgl_akhir_setoran])
            ->where('salesman.kode_cabang', $kode_cabang)
            ->groupBy('keuangan_kuranglebihsetor.tanggal');


        $q_setoranpusat = Setoranpusat::select(
            'keuangan_setoranpusat.tanggal as tanggal',
            DB::raw("0 as lhp_logam"),
            DB::raw("0 as kurang_logam"),
            DB::raw("0 as lebih_logam"),
            DB::raw("SUM(setoran_logam) as setoran_logam"),
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
            DB::raw("0 as lhp_logam"),
            DB::raw("0 as kurang_logam"),
            DB::raw("0 as lebih_logam"),
            DB::raw("0 as setoran_logam"),
            DB::raw("SUM(jumlah) as logamtokertas")
        )
            ->whereBetween('keuangan_logamtokertas.tanggal', [$setoran_dari, $tgl_akhir_setoran])
            ->where('keuangan_logamtokertas.kode_cabang', $kode_cabang)
            ->groupBy('keuangan_logamtokertas.tanggal');

        $unionquery = $q_lhp->unionAll($q_kuranglebihsetor)->unionAll($q_setoranpusat)->unionAll($q_logamtokertas)->get();
        $data['saldologam'] = $unionquery->groupBy('tanggal')
            ->map(function ($item) {
                return [
                    'tanggal' => $item->first()->tanggal,
                    'lhp_logam' => $item->sum('lhp_logam'),
                    'kurang_logam' => $item->sum('kurang_logam'),
                    'lebih_logam' => $item->sum('lebih_logam'),
                    'setoran_logam' => $item->sum('setoran_logam'),
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
            header("Content-Disposition: attachment; filename=Saldo Logam $request->bulan-$request->tahun.xls");
        }

        return view('keuangan.laporan.uanglogam_cetak', $data);
    }

    public function cetakrekapbg(Request $request)
    {
        $dari = $request->tahun . "-" . $request->bulan . "-01";
        $sampai = date("Y-m-t", strtotime($dari));
        if (lockreport($dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang_rekapbg;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang_rekapbg;
        }

        $nextbulan = getbulandantahunberikutnya($request->bulan, $request->tahun, "bulan");
        $nexttahun = getbulandantahunberikutnya($request->bulan, $request->tahun, "tahun");

        //2 Bulan dari Bulan Sekarang
        if ($request->bulan == 1) {
            $lastduabulan = 11;
            $lastdubaulantahun = $request->tahun - 1;
        } else if ($request->bulan == 2) {
            $lastduabulan = 12;
            $lastdubaulantahun = $request->tahun - 1;
        } else {
            $lastduabulan = $request->bulan - 2;
            $lastdubaulantahun = $request->tahun;
        }

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
        } else {
            $setoran_sampai = $sampai;
        }

        $tglduabulanlalu = $lastdubaulantahun . "-" . $lastduabulan . "-01";

        $rekapbg = Detailgiro::select(
            'marketing_penjualan_giro.tanggal',
            'marketing_penjualan_giro_detail.no_faktur',
            'nama_salesman',
            'nama_pelanggan',
            'bank_pengirim',
            'no_giro',
            'marketing_penjualan_giro.jatuh_tempo',
            'marketing_penjualan_giro_detail.jumlah',
            'hb.tanggal as tanggal_bayar'
        )
            ->join('marketing_penjualan_giro', 'marketing_penjualan_giro.kode_giro', '=', 'marketing_penjualan_giro_detail.kode_giro')
            ->join('marketing_penjualan', 'marketing_penjualan_giro_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->leftJoin(
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
                WHERE tanggal <= '$dari'
                GROUP BY
                    no_faktur,
                    marketing_penjualan_movefaktur.kode_salesman_baru,
                    salesman.kode_cabang
                ) movefaktur ON ( marketing_penjualan.no_faktur = movefaktur.no_faktur)
            ) pindahpiutang"),
                function ($join) {
                    $join->on('marketing_penjualan.no_faktur', '=', 'pindahpiutang.no_faktur');
                }
            )
            ->join('salesman', 'pindahpiutang.kode_salesman_baru', '=', 'salesman.kode_salesman')
            ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->leftJoin(
                DB::raw("(SELECT kode_giro,no_faktur,marketing_penjualan_historibayar.tanggal
                FROM marketing_penjualan_historibayar_giro
                INNER JOIN marketing_penjualan_historibayar ON marketing_penjualan_historibayar_giro.no_bukti = marketing_penjualan_historibayar.no_bukti
                WHERE marketing_penjualan_historibayar.tanggal BETWEEN '$dari' AND '$setoran_sampai' GROUP BY kode_giro,no_faktur,marketing_penjualan_historibayar.tanggal) hb"),
                function ($join) {
                    $join->on('marketing_penjualan_giro_detail.kode_giro', '=', 'hb.kode_giro');
                    $join->on('marketing_penjualan_giro_detail.no_faktur', '=', 'hb.no_faktur');
                }
            )
            ->whereBetween('marketing_penjualan_giro.tanggal', [$dari, $sampai])
            ->where('kode_cabang_baru', $kode_cabang)
            ->orWhere('omset_bulan', $request->bulan)
            ->where('omset_tahun', $request->tahun)
            ->where('kode_cabang_baru', $kode_cabang)
            ->orWhereBetween('marketing_penjualan_giro.tanggal', [$tglduabulanlalu, $sampai])
            ->whereNull('omset_bulan')
            ->where('kode_cabang_baru', $kode_cabang)
            ->orWhereBetween('marketing_penjualan_giro.tanggal', [$tglduabulanlalu, $sampai])
            ->where('omset_bulan', '>', $request->bulan)
            ->where('omset_tahun', $request->tahun)
            ->where('kode_cabang_baru', $kode_cabang)
            ->orderBy('marketing_penjualan_giro.tanggal')
            ->get();

        $data['rekapbg'] = $rekapbg;
        $data['dari'] = $dari;
        $data['sampai'] = $sampai;
        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();
        return view('keuangan.laporan.rekapbg_cetak', $data);
    }


    public function cetakpinjaman(Request $request)
    {

        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $pj = new Pjp();
        $pjp = $pj->getPjp(request: $request)->get();
        $data['pjp'] = $pjp;

        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['cabang'] = Cabang::where('kode_cabang', $request->kode_cabang_pinjaman)->first();
        $data['departemen'] = Departemen::where('kode_dept', $request->kode_dept_pinjaman)->first();

        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=PJP $request->dari-$request->sampai.xls");
        }
        return view('keuangan.laporan.pinjaman_cetak', $data);
    }

    public function cetakkasbon(Request $request)
    {

        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $kb = new Kasbon();
        $kasbon = $kb->getKasbon(request: $request)->get();
        $data['kasbon'] = $kasbon;

        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['cabang'] = Cabang::where('kode_cabang', $request->kode_cabang_kasbon)->first();
        $data['departemen'] = Departemen::where('kode_dept', $request->kode_dept_kasbon)->first();

        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=KASBON $request->dari-$request->sampai.xls");
        }
        return view('keuangan.laporan.kasbon_cetak', $data);
    }

    public function cetakpiutangkaryawan(Request $request)
    {

        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $pk = new Piutangkaryawan();
        $piutangkaryawan = $pk->getPiutangkaryawan(request: $request)->get();
        $data['piutangkaryawan'] = $piutangkaryawan;

        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['cabang'] = Cabang::where('kode_cabang', $request->kode_cabang_piutangkaryawan)->first();
        $data['departemen'] = Departemen::where('kode_dept', $request->kode_cabang_piutangkaryawan)->first();

        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Piutang Karyawan $request->dari-$request->sampai.xls");
        }
        return view('keuangan.laporan.piutangkaryawan_cetak', $data);
    }

    public function cetakrekapkartupiutang(Request $request)
    {
        $dari = $request->tahun . '-' . $request->bulan . '-01';
        $sampai = date('Y-m-t', strtotime($dari));
        if (lockreport($dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $bulanpotongan = getbulandantahunberikutnya($request->bulan, $request->tahun, "bulan");
        $tahunpotongan = getbulandantahunberikutnya($request->bulan, $request->tahun, "tahun");
        $tanggal_potongan = $tahunpotongan . '-' . $bulanpotongan . '-01';

        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_pjp = config('global.roles_access_all_pjp');
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $dept_access = json_decode($user->dept_access, true) != null  ? json_decode($user->dept_access, true) : [];

        $query = Karyawan::query();
        $query->select(
            'hrd_karyawan.nik',
            'hrd_karyawan.nama_karyawan',
            'pjp.jumlah_pinjamanlast as pjp_jumlah_pinjamanlast',
            'pjp.total_pembayaranlast as pjp_total_pembayaranlast',
            'pjp.total_pelunasanlast as pjp_total_pelunasanlast',
            'pjp.jumlah_pinjamannow as pjp_jumlah_pinjamannow',
            'pjp.total_pembayarannow as pjp_total_pembayarannow',
            'pjp.total_pelunasannow as pjp_total_pelunasannow',

            'kasbon.jumlah_kasbonlast as kasbon_jumlah_kasbonlast',
            'kasbon.total_pembayaranlast as kasbon_total_pembayaranlast',
            'kasbon.total_pelunasanlast as kasbon_total_pelunasanlast',
            'kasbon.jumlah_kasbonnow as kasbon_jumlah_kasbonnow',
            'kasbon.total_pembayarannow as kasbon_total_pembayarannow',
            'kasbon.total_pelunasannow as kasbon_total_pelunasannow',

            'piutang.jumlah_pinjamanlast as piutang_jumlah_pinjamanlast',
            'piutang.total_pembayaranlast as piutang_total_pembayaranlast',
            'piutang.total_pelunasanlast as piutang_total_pelunasanlast',
            'piutang.jumlah_pinjamannow as piutang_jumlah_pinjamannow',
            'piutang.total_pembayarannow as piutang_total_pembayarannow',
            'piutang.total_pembayaranpotongkomisi as piutang_total_pembayaranpotongkomisi',
            'piutang.total_pembayarantitipan as piutang_total_pembayarantitipan',
            'piutang.total_pembayaranlainnya as piutang_total_pembayaranlainnya',
            'piutang.total_pelunasannow as piutang_total_pelunasannow'
        );

        $query->leftJoin(
            DB::raw("(
            SELECT keuangan_pjp.nik,
            SUM(IF(tanggal < '$dari',jumlah_pinjaman,0)) as jumlah_pinjamanlast,
            SUM(totalpembayaranlast) as total_pembayaranlast,
            SUM(totalpelunasanlast) as total_pelunasanlast,
            SUM(IF(tanggal BETWEEN '$dari' AND '$sampai',jumlah_pinjaman,0)) as jumlah_pinjamannow,
            SUM(totalpembayarannow) as total_pembayarannow,
            SUM(totalpelunasannow) as total_pelunasannow
            FROM keuangan_pjp
            LEFT JOIN (
                SELECT no_pinjaman,SUM(jumlah) as totalpembayaranlast FROM keuangan_pjp_historibayar
                WHERE tanggal < '$tanggal_potongan' AND kode_potongan IS NOT NULL
                GROUP BY no_pinjaman
            ) hb ON (keuangan_pjp.no_pinjaman = hb.no_pinjaman)

            LEFT JOIN (
                SELECT no_pinjaman,SUM(jumlah) as totalpelunasanlast FROM keuangan_pjp_historibayar
                WHERE tanggal < '$dari' AND kode_potongan IS NULL
                GROUP BY no_pinjaman
            ) hbplast ON (keuangan_pjp.no_pinjaman = hbplast.no_pinjaman)

            LEFT JOIN (
                SELECT no_pinjaman,SUM(jumlah) as totalpelunasannow FROM keuangan_pjp_historibayar
                WHERE tanggal BETWEEN '$dari' AND '$sampai' AND kode_potongan IS NULL
                GROUP BY no_pinjaman
            ) hbplnow ON (keuangan_pjp.no_pinjaman = hbplnow.no_pinjaman)

            LEFT JOIN (
                SELECT no_pinjaman,SUM(jumlah) as totalpembayarannow FROM keuangan_pjp_historibayar
                WHERE tanggal = '$tanggal_potongan' AND kode_potongan IS NOT NULL
                GROUP BY no_pinjaman
            ) hbnow ON (keuangan_pjp.no_pinjaman = hbnow.no_pinjaman)
            WHERE tanggal <= '$sampai'
            GROUP BY keuangan_pjp.nik
        ) pjp"),
            function ($join) {
                $join->on('hrd_karyawan.nik', '=', 'pjp.nik');
            }
        );


        $query->leftJoin(
            DB::raw("(
            SELECT keuangan_kasbon.nik,
            SUM(IF(tanggal < '$dari',jumlah,0)) as jumlah_kasbonlast,
            SUM(totalpembayaranlast) as total_pembayaranlast,
            SUM(totalpelunasanlast) as total_pelunasanlast,
            SUM(IF(tanggal BETWEEN '$dari' AND '$sampai',jumlah,0)) as jumlah_kasbonnow,
            SUM(totalpembayarannow) as total_pembayarannow,
            SUM(totalpelunasannow) as total_pelunasannow
            FROM keuangan_kasbon
            LEFT JOIN (
                SELECT no_kasbon,SUM(jumlah) as totalpembayaranlast FROM keuangan_kasbon_historibayar
                WHERE tanggal < '$tanggal_potongan' AND kode_potongan IS NOT NULL
                GROUP BY no_kasbon
            ) hb ON (keuangan_kasbon.no_kasbon = hb.no_kasbon)

            LEFT JOIN (
                SELECT no_kasbon,SUM(jumlah) as totalpelunasanlast FROM    keuangan_kasbon_historibayar
                WHERE tanggal < '$dari' AND kode_potongan IS NULL
                GROUP BY no_kasbon
            ) hbpllast ON (keuangan_kasbon.no_kasbon = hbpllast.no_kasbon)

            LEFT JOIN (
                SELECT no_kasbon,SUM(jumlah) as totalpelunasannow FROM keuangan_kasbon_historibayar
                WHERE tanggal BETWEEN '$dari' AND '$sampai' AND kode_potongan IS NULL
                GROUP BY no_kasbon
            ) hbplnow ON (keuangan_kasbon.no_kasbon = hbplnow.no_kasbon)

            LEFT JOIN (
                SELECT no_kasbon,SUM(jumlah) as totalpembayarannow FROM keuangan_kasbon_historibayar
                WHERE tanggal = '$tanggal_potongan' AND kode_potongan IS NOT NULL
                GROUP BY no_kasbon
            ) hbnow ON (keuangan_kasbon.no_kasbon = hbnow.no_kasbon)

            WHERE tanggal <= '$sampai'
            GROUP BY keuangan_kasbon.nik
        ) kasbon"),
            function ($join) {
                $join->on('hrd_karyawan.nik', '=', 'kasbon.nik');
            }
        );


        $query->leftJoin(
            DB::raw("(
            SELECT keuangan_piutangkaryawan.nik,
            SUM(IF(tanggal < '$dari',jumlah,0)) as jumlah_pinjamanlast,
            SUM(totalpembayaranlast) as total_pembayaranlast,
            SUM(totalpelunasanlast) as total_pelunasanlast,
            SUM(IF(tanggal BETWEEN '$dari' AND '$sampai',jumlah,0)) as jumlah_pinjamannow,
            SUM(totalpembayarannow) as total_pembayarannow,
            SUM(totalpembayaranpotongkomisi) as total_pembayaranpotongkomisi,
            SUM(totalpembayarantitipan) as total_pembayarantitipan,
            SUM(totalpembayaranlainnya) as total_pembayaranlainnya,
            SUM(totalpelunasannow) as total_pelunasannow
            FROM keuangan_piutangkaryawan
            LEFT JOIN (
                SELECT no_pinjaman,SUM(jumlah) as totalpembayaranlast FROM keuangan_piutangkaryawan_historibayar
                WHERE tanggal < '$tanggal_potongan' AND kode_potongan IS NOT NULL
                GROUP BY no_pinjaman
            ) hb ON (keuangan_piutangkaryawan.no_pinjaman = hb.no_pinjaman)


            LEFT JOIN (
                SELECT no_pinjaman,SUM(jumlah) as totalpelunasanlast FROM keuangan_piutangkaryawan_historibayar
                WHERE tanggal < '$tanggal_potongan' AND kode_potongan IS NULL
                GROUP BY no_pinjaman
            ) hbpllast ON (keuangan_piutangkaryawan.no_pinjaman = hbpllast.no_pinjaman)


            LEFT JOIN (
                SELECT no_pinjaman,SUM(jumlah) as totalpelunasannow FROM keuangan_piutangkaryawan_historibayar
                WHERE tanggal BETWEEN '$dari' AND '$sampai' AND kode_potongan IS NULL
                GROUP BY no_pinjaman
            ) hbplnow ON (keuangan_piutangkaryawan.no_pinjaman = hbplnow.no_pinjaman)


            LEFT JOIN (
                SELECT no_pinjaman,
                SUM(IF(jenis_bayar=1,jumlah,0)) as totalpembayarannow,
                SUM(IF(jenis_bayar=2,jumlah,0)) as totalpembayaranpotongkomisi,
                SUM(IF(jenis_bayar=3,jumlah,0)) as totalpembayarantitipan,
                SUM(IF(jenis_bayar=4,jumlah,0)) as totalpembayaranlainnya
                FROM keuangan_piutangkaryawan_historibayar
                WHERE tanggal = '$tanggal_potongan'
                GROUP BY no_pinjaman
            ) hbnow ON (keuangan_piutangkaryawan.no_pinjaman = hbnow.no_pinjaman)

            WHERE tanggal <= '$sampai'
            GROUP BY keuangan_piutangkaryawan.nik
        ) piutang"),
            function ($join) {
                $join->on('hrd_karyawan.nik', '=', 'piutang.nik');
            }
        );

        $query->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->join('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang');
        if (!empty($request->kode_cabang_rekapkartupiutang)) {
            $query->where('hrd_karyawan.kode_cabang', $request->kode_cabang_rekapkartupiutang);
        }

        if (!empty($request->kode_dept_rekapkartupiutang)) {
            $query->where('hrd_karyawan.kode_dept', $request->kode_dept_rekapkartupiutang);
        }

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', $user->kode_regional);
                $query->where('hrd_karyawan.kode_jabatan', '!=', 'J03');
                // $query->where('hrd_karyawan.kode_dept', $user->kode_dept);
            } else {
                $query->where('hrd_jabatan.kategori', 'NM');
                $query->where('hrd_karyawan.kode_cabang', $user->kode_cabang);
                // $query->where('hrd_karyawan.kode_dept', $user->kode_dept);
            }
        } else {
            if (!$user->hasRole($roles_access_all_pjp)) {
                $query->where('hrd_jabatan.kategori', 'NM');
            }
        }

        $query->whereIn('hrd_karyawan.kode_dept', $dept_access);
        $query->orderBy('nama_karyawan');
        $piutangkaryawan = $query->get();
        $data['piutangkaryawan'] = $piutangkaryawan;
        $data['bulan'] = $request->bulan;
        $data['tahun'] = $request->tahun;
        $data['cabang'] = Cabang::where('kode_cabang', $request->kode_cabang_rekapkartupiutang)->first();
        $data['departemen'] = Departemen::where('kode_dept', $request->kode_dept_rekapkartupiutang)->first();
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Rekap Kartu Piutang $request->dari-$request->sampai.xls");
        }
        return view('keuangan.laporan.rekapkartupiutang_cetak', $data);
    }

    public function cetakkartupjp(Request $request)
    {
        $dari = $request->tahun . '-' . $request->bulan . '-01';
        $sampai = date('Y-m-t', strtotime($dari));

        if (lockreport($dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $bulanpotongan = getbulandantahunberikutnya($request->bulan, $request->tahun, "bulan");
        $tahunpotongan = getbulandantahunberikutnya($request->bulan, $request->tahun, "tahun");
        $tanggal_potongan = $tahunpotongan . '-' . $bulanpotongan . '-01';

        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_pjp = config('global.roles_access_all_pjp');
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $dept_access = json_decode($user->dept_access, true) != null  ? json_decode($user->dept_access, true) : [];

        $query = Pjp::query();
        $query->select(
            'keuangan_pjp.nik',
            'nama_karyawan',
            DB::raw("SUM(IF(tanggal < '$dari',jumlah_pinjaman,0)) as jumlah_pinjamanlast"),
            DB::raw("SUM(totalpembayaranlast) as total_pembayaranlast"),
            DB::raw("SUM(totalpelunasanlast) as total_pelunasanlast"),
            DB::raw("SUM(IF(tanggal BETWEEN '$dari' AND '$sampai',jumlah_pinjaman,0)) as jumlah_pinjamannow"),
            DB::raw("SUM(totalpembayarannow) as total_pembayarannow"),
            DB::raw("SUM(totalpelunasannow) as total_pelunasannow")
        );
        $query->join('hrd_karyawan', 'keuangan_pjp.nik', '=', 'hrd_karyawan.nik');
        $query->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->join('hrd_departemen', 'hrd_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept');
        $query->join('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftJoin(
            DB::raw("(
            SELECT no_pinjaman,SUM(jumlah) as totalpembayaranlast FROM keuangan_pjp_historibayar
            WHERE tanggal < '$tanggal_potongan' AND kode_potongan IS NOT NULL
            GROUP BY no_pinjaman
        ) hb"),
            function ($join) {
                $join->on('keuangan_pjp.no_pinjaman', '=', 'hb.no_pinjaman');
            }
        );

        $query->leftJoin(
            DB::raw("(
            SELECT no_pinjaman,SUM(jumlah) as totalpelunasanlast FROM keuangan_pjp_historibayar
            WHERE tanggal < '$dari' AND kode_potongan IS NULL
            GROUP BY no_pinjaman
        ) hbpllast"),
            function ($join) {
                $join->on('keuangan_pjp.no_pinjaman', '=', 'hbpllast.no_pinjaman');
            }
        );

        $query->leftJoin(
            DB::raw("(
            SELECT no_pinjaman,SUM(jumlah) as totalpelunasannow FROM keuangan_pjp_historibayar
            WHERE tanggal BETWEEN '$dari' AND '$sampai' AND kode_potongan IS NULL
            GROUP BY no_pinjaman
        ) hbplnow"),
            function ($join) {
                $join->on('keuangan_pjp.no_pinjaman', '=', 'hbplnow.no_pinjaman');
            }
        );
        $query->leftJoin(
            DB::raw("(
            SELECT no_pinjaman,SUM(jumlah) as totalpembayarannow FROM keuangan_pjp_historibayar
            WHERE tanggal = '$tanggal_potongan' AND kode_potongan IS NOT NULL
            GROUP BY no_pinjaman
        ) hbnow"),
            function ($join) {
                $join->on('keuangan_pjp.no_pinjaman', '=', 'hbnow.no_pinjaman');
            }
        );
        $query->where('keuangan_pjp.tanggal', '<=', $sampai);

        if (!empty($request->kode_cabang_kartupjp)) {
            $query->where('hrd_karyawan.kode_cabang', $request->kode_cabang_kartupjp);
        }

        if (!empty($request->kode_dept_kartupjp)) {
            $query->where('hrd_karyawan.kode_dept', $request->kode_dept_kartupjp);
        }

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', $user->kode_regional);
                $query->where('hrd_karyawan.kode_jabatan', '!=', 'J03');
                // $query->where('hrd_karyawan.kode_dept', $user->kode_dept);
            } else {
                $query->where('hrd_jabatan.kategori', 'NM');
                $query->where('hrd_karyawan.kode_cabang', $user->kode_cabang);
                // $query->where('hrd_karyawan.kode_dept', $user->kode_dept);
            }
        } else {
            if (!$user->hasRole($roles_access_all_pjp)) {
                $query->where('hrd_jabatan.kategori', 'NM');
            }
        }

        $query->whereIn('hrd_karyawan.kode_dept', $dept_access);
        $query->groupByRaw('keuangan_pjp.nik,nama_karyawan');
        $query->orderBy('nama_karyawan');
        $data['pjp'] = $query->get();
        $data['bulan'] = $request->bulan;
        $data['tahun'] = $request->tahun;
        $data['cabang'] = Cabang::where('kode_cabang', $request->kode_cabang_kartupjp)->first();
        $data['departemen'] = Departemen::where('kode_dept', $request->kode_dept_kartupjp)->first();
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Kartu PJP.xls");
        }
        return view('keuangan.laporan.kartupjp_cetak', $data);
    }


    public function cetakkartukasbon(Request $request)
    {
        $dari = $request->tahun . '-' . $request->bulan . '-01';
        $sampai = date('Y-m-t', strtotime($dari));

        if (lockreport($dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $bulanpotongan = getbulandantahunberikutnya($request->bulan, $request->tahun, "bulan");
        $tahunpotongan = getbulandantahunberikutnya($request->bulan, $request->tahun, "tahun");
        $tanggal_potongan = $tahunpotongan . '-' . $bulanpotongan . '-01';

        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_pjp = config('global.roles_access_all_pjp');
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $dept_access = json_decode($user->dept_access, true) != null  ? json_decode($user->dept_access, true) : [];

        $query = Kasbon::query();
        $query->select(
            'keuangan_kasbon.nik',
            'nama_karyawan',
            DB::raw("SUM(IF(tanggal < '$dari',jumlah,0)) as jumlah_kasbonlast"),
            DB::raw("SUM(totalpembayaranlast) as total_pembayaranlast"),
            DB::raw("SUM(totalpelunasanlast) as total_pelunasanlast"),
            DB::raw("SUM(IF(tanggal BETWEEN '$dari' AND '$sampai',jumlah,0)) as jumlah_kasbonnow"),
            DB::raw("SUM(totalpembayarannow) as total_pembayarannow"),
            DB::raw("SUM(totalpelunasannow) as total_pelunasannow")
        );
        $query->join('hrd_karyawan', 'keuangan_kasbon.nik', '=', 'hrd_karyawan.nik');
        $query->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->join('hrd_departemen', 'hrd_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept');
        $query->join('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftJoin(
            DB::raw("(
            SELECT no_kasbon,SUM(jumlah) as totalpembayaranlast FROM keuangan_kasbon_historibayar
            WHERE tanggal < '$tanggal_potongan' AND kode_potongan IS NOT NULL
            GROUP BY no_kasbon
        ) hb"),
            function ($join) {
                $join->on('keuangan_kasbon.no_kasbon', '=', 'hb.no_kasbon');
            }
        );

        $query->leftJoin(
            DB::raw("(
            SELECT no_kasbon,SUM(jumlah) as totalpelunasanlast FROM keuangan_kasbon_historibayar
            WHERE tanggal < '$dari' AND kode_potongan IS NULL
            GROUP BY no_kasbon
        ) hbpllast"),
            function ($join) {
                $join->on('keuangan_kasbon.no_kasbon', '=', 'hbpllast.no_kasbon');
            }
        );

        $query->leftJoin(
            DB::raw("(
            SELECT no_kasbon,SUM(jumlah) as totalpelunasannow FROM keuangan_kasbon_historibayar
            WHERE tanggal BETWEEN '$dari' AND '$sampai' AND kode_potongan IS NULL
            GROUP BY no_kasbon
        ) hbplnow"),
            function ($join) {
                $join->on('keuangan_kasbon.no_kasbon', '=', 'hbplnow.no_kasbon');
            }
        );
        $query->leftJoin(
            DB::raw("(
            SELECT no_kasbon,SUM(jumlah) as totalpembayarannow FROM keuangan_kasbon_historibayar
            WHERE tanggal = '$tanggal_potongan' AND kode_potongan IS NOT NULL
            GROUP BY no_kasbon
        ) hbnow"),
            function ($join) {
                $join->on('keuangan_kasbon.no_kasbon', '=', 'hbnow.no_kasbon');
            }
        );
        $query->where('keuangan_kasbon.tanggal', '<=', $sampai);

        if (!empty($request->kode_cabang_kartukasbon)) {
            $query->where('hrd_karyawan.kode_cabang', $request->kode_cabang_kartukasbon);
        }

        if (!empty($request->kode_dept_kartukasbon)) {
            $query->where('hrd_karyawan.kode_dept', $request->kode_dept_kartukasbon);
        }

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', $user->kode_regional);
                $query->where('hrd_karyawan.kode_jabatan', '!=', 'J03');
                // $query->where('hrd_karyawan.kode_dept', $user->kode_dept);
            } else {
                $query->where('hrd_jabatan.kategori', 'NM');
                $query->where('hrd_karyawan.kode_cabang', $user->kode_cabang);
                // $query->where('hrd_karyawan.kode_dept', $user->kode_dept);
            }
        } else {
            if (!$user->hasRole($roles_access_all_pjp)) {
                $query->where('hrd_jabatan.kategori', 'NM');
            }
        }

        $query->whereIn('hrd_karyawan.kode_dept', $dept_access);
        $query->groupByRaw('keuangan_kasbon.nik,nama_karyawan');
        $query->orderBy('nama_karyawan');

        $data['kasbon'] = $query->get();
        //dd($data['kasbon']);
        $data['bulan'] = $request->bulan;
        $data['tahun'] = $request->tahun;
        $data['cabang'] = Cabang::where('kode_cabang', $request->kode_cabang_kartukasbon)->first();
        $data['departemen'] = Departemen::where('kode_dept', $request->kode_dept_kartukasbon)->first();
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Kartu Kasbon.xls");
        }
        return view('keuangan.laporan.kartukasbon_cetak', $data);
    }

    public function cetakkartupiutangkaryawan(Request $request)
    {
        $dari = $request->tahun . '-' . $request->bulan . '-01';
        $sampai = date('Y-m-t', strtotime($dari));

        if (lockreport($dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $bulanpotongan = getbulandantahunberikutnya($request->bulan, $request->tahun, "bulan");
        $tahunpotongan = getbulandantahunberikutnya($request->bulan, $request->tahun, "tahun");
        $tanggal_potongan = $tahunpotongan . '-' . $bulanpotongan . '-01';

        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_pjp = config('global.roles_access_all_pjp');
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $dept_access = json_decode($user->dept_access, true) != null  ? json_decode($user->dept_access, true) : [];

        $query = Piutangkaryawan::query();
        $query->selectRaw("keuangan_piutangkaryawan.nik, nama_karyawan,
        SUM(IF(tanggal < '$dari',jumlah,0)) as jumlah_pinjamanlast,
        SUM(totalpembayaranlast) as total_pembayaranlast,
        SUM(IF(tanggal BETWEEN '$dari' AND '$sampai',jumlah,0)) as jumlah_pinjamannow,
        SUM(totalpembayarannow) as total_pembayarannow,
        SUM(totalpembayaranpotongkomisi) as total_pembayaranpotongkomisi,
        SUM(totalpembayarantitipan) as total_pembayarantitipan,
        SUM(totalpembayaranlainnya) as total_pembayaranlainnya
        ");
        $query->join('hrd_karyawan', 'keuangan_piutangkaryawan.nik', '=', 'hrd_karyawan.nik');
        $query->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->join('hrd_departemen', 'hrd_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept');
        $query->join('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftJoin(
            DB::raw("(
            SELECT no_pinjaman,SUM(jumlah) as totalpembayaranlast FROM keuangan_piutangkaryawan_historibayar
            WHERE tanggal < '$tanggal_potongan'
            GROUP BY no_pinjaman
        ) hb"),
            function ($join) {
                $join->on('keuangan_piutangkaryawan.no_pinjaman', '=', 'hb.no_pinjaman');
            }
        );

        // $query->leftJoin(
        //     DB::raw("(
        //     SELECT no_pinjaman,SUM(jumlah) as totalpelunasanlast FROM keuangan_piutangkaryawan_historibayar
        //     WHERE tanggal < '$dari' AND kode_potongan IS NULL
        //     GROUP BY no_pinjaman
        // ) hbpllast"),
        //     function ($join) {
        //         $join->on('keuangan_piutangkaryawan.no_pinjaman', '=', 'hbpllast.no_pinjaman');
        //     }
        // );

        // $query->leftJoin(
        //     DB::raw("(
        //     SELECT no_pinjaman,SUM(jumlah) as totalpelunasannow FROM keuangan_piutangkaryawan_historibayar
        //     WHERE tanggal BETWEEN '$dari' AND '$sampai' AND kode_potongan IS NULL
        //     GROUP BY no_pinjaman
        // ) hbplnow"),
        //     function ($join) {
        //         $join->on('keuangan_piutangkaryawan.no_pinjaman', '=', 'hbplnow.no_pinjaman');
        //     }
        // );
        $query->leftJoin(
            DB::raw("(
            SELECT no_pinjaman,
            SUM(IF(jenis_bayar=1,jumlah,0)) as totalpembayarannow,
            SUM(IF(jenis_bayar=2,jumlah,0)) as totalpembayaranpotongkomisi,
            SUM(IF(jenis_bayar=3,jumlah,0)) as totalpembayarantitipan,
            SUM(IF(jenis_bayar=4,jumlah,0)) as totalpembayaranlainnya
            FROM keuangan_piutangkaryawan_historibayar
            WHERE tanggal = '$tanggal_potongan'
            GROUP BY no_pinjaman
        ) hbnow"),
            function ($join) {
                $join->on('keuangan_piutangkaryawan.no_pinjaman', '=', 'hbnow.no_pinjaman');
            }
        );
        $query->where('tanggal', '<=', $sampai);
        if (!empty($request->kode_cabang_kartupiutangkaryawan)) {
            $query->where('hrd_karyawan.kode_cabang', $request->kode_cabang_kartupiutangkaryawan);
        }

        if (!empty($request->kode_dept_kartupiutangkaryawan)) {
            $query->where('hrd_karyawan.kode_dept', $request->kode_dept_kartupiutangkaryawan);
        }

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', $user->kode_regional);
                $query->where('hrd_karyawan.kode_jabatan', '!=', 'J03');
                // $query->where('hrd_karyawan.kode_dept', $user->kode_dept);
            } else {
                $query->where('hrd_jabatan.kategori', 'NM');
                $query->where('hrd_karyawan.kode_cabang', $user->kode_cabang);
                // $query->where('hrd_karyawan.kode_dept', $user->kode_dept);
            }
        } else {
            if (!$user->hasRole($roles_access_all_pjp)) {
                $query->where('keuangan_piutangkaryawan.status', '0');
            }
        }

        $query->whereIn('hrd_karyawan.kode_dept', $dept_access);
        $query->groupByRaw('keuangan_piutangkaryawan.nik,nama_karyawan');
        $query->orderBy('nama_karyawan');

        $data['piutangkaryawan'] = $query->get();
        // dd($data['piutangkaryawan']);
        $data['bulan'] = $request->bulan;
        $data['tahun'] = $request->tahun;
        $data['cabang'] = Cabang::where('kode_cabang', $request->kode_cabang_kartupiutangkaryawan)->first();
        $data['departemen'] = Departemen::where('kode_dept', $request->kode_dept_kartupiutangkaryawan)->first();
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Kartu Piutang karyawan.xls");
        }
        return view('keuangan.laporan.kartupiutangkaryawan_cetak', $data);
    }

    public function cetakkaskecil(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        if (lockreport($request->dari) == "error" && !$user->hasRole(['admin pajak', 'manager audit', 'rom'])) {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }


        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $awal_kas_kecil = '2019-04-30';
        $sehariSebelumDari = date('Y-m-d', strtotime('-1 day', strtotime($request->dari)));

        $qsaldoawal = Kaskecil::query();
        $qsaldoawal->selectRaw("SUM(IF( `debet_kredit` = 'K', jumlah, 0)) -SUM(IF( `debet_kredit` = 'D', jumlah, 0)) as saldo_awal");
        $qsaldoawal->whereBetween('tanggal', [$awal_kas_kecil, $sehariSebelumDari]);
        // $qsaldoawal->where('kode_cabang', $request->kode_cabang);
        //dd($user->hasRole('admin pusat'));
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager') || $user->hasRole('admin pusat')) {
                $qsaldoawal->where('kode_cabang', $request->kode_cabang);
            } else {
                $qsaldoawal->where('kode_cabang', auth()->user()->kode_cabang);
            }
        } else {
            if (!empty($request->kode_cabang)) {
                $qsaldoawal->where('kode_cabang', $request->kode_cabang);
            }
        }
        $saldoawal = $qsaldoawal->first();


        if ($request->formatlaporan == '1') {
            $query = Kaskecil::query();
            $query->select('keuangan_kaskecil.*', 'nama_akun', 'kode_klaim');
            $query->join('coa', 'keuangan_kaskecil.kode_akun', '=', 'coa.kode_akun');
            $query->leftJoin('keuangan_kaskecil_klaim_detail', 'keuangan_kaskecil.id', '=', 'keuangan_kaskecil_klaim_detail.id');
            if (!$user->hasRole($roles_access_all_cabang)) {
                if ($user->hasRole('regional sales manager') || $user->hasRole('admin pusat')) {
                    $query->where('kode_cabang', $request->kode_cabang);
                } else {
                    $query->where('kode_cabang', auth()->user()->kode_cabang);
                }
            } else {
                if (!empty($request->kode_cabang)) {
                    $query->where('kode_cabang', $request->kode_cabang);
                }
            }



            $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
            if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
                $query->whereBetween('keuangan_kaskecil.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
            }
            $query->orderBy('tanggal');
            $query->orderBy('debet_kredit', 'desc');
            $query->orderBy('no_bukti');
            $kaskecil = $query->get();



            $data['saldoawal'] = $saldoawal;
            $data['kaskecil'] = $kaskecil;
            $data['dari'] = $request->dari;
            $data['sampai'] = $request->sampai;
            $data['cabang'] = Cabang::where('kode_cabang', $request->kode_cabang)->first();

            if (isset($_POST['exportButton'])) {
                header("Content-type: application/vnd-ms-excel");
                // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
                header("Content-Disposition: attachment; filename=Kas Kecil $request->dari-$request->sampai.xls");
            }
            return view('keuangan.laporan.kaskecil_cetak', $data);
        } else {

            $query = Kaskecil::query();
            $query->select(
                'keuangan_kaskecil.kode_akun',
                'nama_akun',
                DB::raw("SUM(IF('D' = debet_kredit, jumlah, 0)) as pengeluaran"),
                DB::raw("SUM(IF('K' = debet_kredit, jumlah, 0)) as penerimaan"),
            );
            $query->join('coa', 'keuangan_kaskecil.kode_akun', '=', 'coa.kode_akun');
            $query->leftJoin('keuangan_kaskecil_klaim_detail', 'keuangan_kaskecil.id', '=', 'keuangan_kaskecil_klaim_detail.id');
            if (!$user->hasRole($roles_access_all_cabang)) {
                if ($user->hasRole('regional sales manager') || $user->hasRole('admin pusat')) {
                    $query->where('kode_cabang', $request->kode_cabang);
                } else {
                    $query->where('kode_cabang', auth()->user()->kode_cabang);
                }
            } else {
                if (!empty($request->kode_cabang)) {
                    $query->where('kode_cabang', $request->kode_cabang);
                }
            }



            $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
            $query->groupBy('keuangan_kaskecil.kode_akun', 'nama_akun');
            $query->orderBy('keuangan_kaskecil.kode_akun');
            $kaskecil = $query->get();



            $data['saldoawal'] = $saldoawal;
            $data['kaskecil'] = $kaskecil;
            $data['dari'] = $request->dari;
            $data['sampai'] = $request->sampai;
            $data['cabang'] = Cabang::where('kode_cabang', $request->kode_cabang)->first();

            if (isset($_POST['exportButton'])) {
                header("Content-type: application/vnd-ms-excel");
                // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
                header("Content-Disposition: attachment; filename=Kas Kecil $request->dari-$request->sampai.xls");
            }
            return view('keuangan.laporan.kaskecil_rekap_cetak', $data);
        }
    }

    public function cetakmutasikeuangan(Request $request)
    {

        $user = User::findorfail(auth()->user()->id);
        if (lockreport($request->dari) == "error" && !$user->hasRole(['admin pajak', 'rom'])) {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['bank'] = Bank::where('kode_bank', $request->kode_bank_ledger)->first();

        $bulan = !empty($request->dari) ? date('m', strtotime($request->dari)) : '';
        $tahun = !empty($request->dari) ? date('Y', strtotime($request->dari)) : '';
        if ($request->formatlaporan == '1') {
            $query = Mutasikeuangan::query();
            $query->select(
                'keuangan_mutasi.*',
                'nama_bank',
                'bank.no_rekening',
            );
            $query->join('bank', 'keuangan_mutasi.kode_bank', '=', 'bank.kode_bank');
            $query->orderBy('keuangan_mutasi.tanggal');
            $query->orderBy('keuangan_mutasi.created_at');
            $query->whereBetween('keuangan_mutasi.tanggal', [$request->dari, $request->sampai]);
            if ($request->kode_bank_ledger != "") {
                $query->where('keuangan_mutasi.kode_bank', $request->kode_bank_ledger);
            }


            $data['ledger'] = $query->get();

            if ($user->hasRole('staff keuangan 2')) {
                $data['saldo_awal']  = Saldoawalledger::where('bulan', $bulan)->where('tahun', $tahun)->where('kode_bank', 'BK070')->first();
            } else {

                $data['saldo_awal']  = Saldoawalledger::where('bulan', $bulan)->where('tahun', $tahun)->where('kode_bank', $request->kode_bank_ledger)->first();
            }

            $start_date = $tahun . "-" . $bulan . "-01";
            if (!empty($request->dari && !empty($request->sampai))) {

                if ($user->hasRole('staff keuangan 2')) {
                    $data['mutasi']  = Mutasikeuangan::select(
                        DB::raw("SUM(IF(debet_kredit='K',jumlah,0))as kredit"),
                        DB::raw("SUM(IF(debet_kredit='D',jumlah,0))as debet"),
                    )
                        ->where('tanggal', '>=', $start_date)
                        ->where('tanggal', '<', $request->dari)
                        ->where('kode_bank', 'BK070')
                        ->first();
                } else {
                    $data['mutasi']  = Mutasikeuangan::select(
                        DB::raw("SUM(IF(debet_kredit='K',jumlah,0))as kredit"),
                        DB::raw("SUM(IF(debet_kredit='D',jumlah,0))as debet"),
                    )
                        ->where('tanggal', '>=', $start_date)
                        ->where('tanggal', '<', $request->dari)
                        ->where('kode_bank', $request->kode_bank_ledger)
                        ->first();
                }
            } else {
                $data['mutasi'] = null;
            }

            if (isset($_POST['exportButton'])) {
                header("Content-type: application/vnd-ms-excel");
                // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
                header("Content-Disposition: attachment; filename=Ledger $request->dari-$request->sampai.xls");
            }
            return view('keuangan.laporan.mutasikeuangan_cetak', $data);
        } else {
            $query = Mutasikeuangan::query();
            $query->select(
                'keuangan_mutasi.kode_bank',
                'nama_bank',
                DB::raw('SUM(IF(debet_kredit="D",jumlah,0)) as jmldebet'),
                DB::raw('SUM(IF(debet_kredit="K",jumlah,0)) as jmlkredit')
            );

            $query->join('bank', 'keuangan_mutasi.kode_bank', '=', 'bank.kode_bank');
            $query->orderBy('keuangan_mutasi.kode_bank');
            $query->whereBetween('keuangan_mutasi.tanggal', [$request->dari, $request->sampai]);
            if (!empty($request->kode_bank_ledger)) {
                $query->where('keuangan_mutasi.kode_bank', $request->kode_bank_ledger);
            }

            $query->groupBy('keuangan_mutasi.kode_bank', 'nama_bank');
            $data['ledger'] = $query->get();
            if (isset($_POST['exportButton'])) {
                header("Content-type: application/vnd-ms-excel");
                // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
                header("Content-Disposition: attachment; filename=Rekap Mutasi Keuangan $request->dari-$request->sampai.xls");
            }
            return view('keuangan.laporan.rekapmutasikeuangan_cetak', $data);
        }
    }
}
