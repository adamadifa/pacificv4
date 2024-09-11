<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Cabang;
use App\Models\Detailklaimkaskecil;
use App\Models\Kaskecil;
use App\Models\Klaimkaskecil;
use App\Models\Ledger;
use App\Models\Ledgerklaim;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class KlaimkaskecilController extends Controller
{
    public function index(Request $request)
    {

        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }
        $query = Klaimkaskecil::query();
        $query->select(
            'keuangan_kaskecil_klaim.*',
            'keuangan_ledger_klaim.no_bukti',
            'keuangan_ledger.tanggal as tgl_proses',
            'keuangan_kaskecil.id as cekvalidasi',
            'keuangan_ledger.jumlah'
        );
        $query->leftJoin('keuangan_ledger_klaim', 'keuangan_kaskecil_klaim.kode_klaim', '=', 'keuangan_ledger_klaim.kode_klaim');
        $query->leftJoin('keuangan_ledger', 'keuangan_ledger_klaim.no_bukti', '=', 'keuangan_ledger.no_bukti');
        $query->leftJoin('keuangan_kaskecil', 'keuangan_ledger_klaim.no_bukti', '=', 'keuangan_kaskecil.no_bukti');
        $query->join('cabang', 'keuangan_kaskecil_klaim.kode_cabang', '=', 'cabang.kode_cabang');
        $query->whereBetween('keuangan_kaskecil_klaim.tanggal', [$request->dari, $request->sampai]);
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                if (!empty($request->kode_cabang_search)) {
                    $query->where('kode_cabang', $request->kode_cabang_search);
                } else {
                    $query->where('cabang.kode_regional', auth()->user()->kode_regional);
                }
            } else {
                $query->where('keuangan_kaskecil_klaim.kode_cabang', auth()->user()->kode_cabang);
            }
        } else {
            if (!empty($request->kode_cabang_search)) {
                $query->where('keuangan_kaskecil_klaim.kode_cabang', $request->kode_cabang_search);
            }
        }
        $query->orderBy('keuangan_kaskecil_klaim.tanggal', 'desc');
        $query->orderBy('keuangan_kaskecil_klaim.kode_klaim', 'desc');
        $klaimkaskecil = $query->paginate(10);
        $klaimkaskecil->appends($request->all());
        $data['klaimkaskecil'] = $klaimkaskecil;
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('keuangan.klaimkaskecil.index', $data);
    }


    public function cetak($kode_klaim, $export = false)
    {
        $kode_klaim = Crypt::decrypt($kode_klaim);
        $klaim = Klaimkaskecil::findorfail($kode_klaim);
        $data['klaim'] = $klaim;
        $data['detail'] = Detailklaimkaskecil::where('kode_klaim', $kode_klaim)
            ->join('keuangan_kaskecil', 'keuangan_kaskecil_klaim_detail.id', '=', 'keuangan_kaskecil.id')
            ->orderBy('keuangan_kaskecil.tanggal')
            ->orderBy('debet_kredit', 'desc')
            ->orderBy('keuangan_kaskecil.no_bukti')
            ->get();
        $data['saldoawal'] = Klaimkaskecil::where('kode_klaim', '<', $kode_klaim)
            ->where('kode_cabang', $klaim->kode_cabang)->orderby('kode_klaim', 'desc')->first();
        if ($export) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Klaim Kas Keci.xls");
        }
        return view('keuangan.klaimkaskecil.cetak', $data);
    }

    public function proses($kode_klaim)
    {
        $kode_klaim = Crypt::decrypt($kode_klaim);
        $query = Klaimkaskecil::query();
        $query->select(
            'keuangan_kaskecil_klaim.*',
            'keuangan_ledger_klaim.no_bukti',
            'keuangan_ledger.tanggal as tgl_proses',
            'keuangan_kaskecil.id as cekvalidasi',
            'keuangan_ledger.jumlah',
            'nama_cabang'
        );
        $query->leftJoin('keuangan_ledger_klaim', 'keuangan_kaskecil_klaim.kode_klaim', '=', 'keuangan_ledger_klaim.kode_klaim');
        $query->leftJoin('keuangan_ledger', 'keuangan_ledger_klaim.no_bukti', '=', 'keuangan_ledger.no_bukti');
        $query->leftJoin('keuangan_kaskecil', 'keuangan_ledger_klaim.no_bukti', '=', 'keuangan_kaskecil.no_bukti');
        $query->join('cabang', 'keuangan_kaskecil_klaim.kode_cabang', '=', 'cabang.kode_cabang');
        $query->where('keuangan_kaskecil_klaim.kode_klaim', $kode_klaim);
        $klaim = $query->first();
        $data['klaim'] = $klaim;
        $data['detail'] = Detailklaimkaskecil::where('kode_klaim', $kode_klaim)
            ->join('keuangan_kaskecil', 'keuangan_kaskecil_klaim_detail.id', '=', 'keuangan_kaskecil.id')
            ->join('coa', 'keuangan_kaskecil.kode_akun', '=', 'coa.kode_akun')
            ->orderBy('keuangan_kaskecil.tanggal')
            ->orderBy('debet_kredit', 'desc')
            ->orderBy('keuangan_kaskecil.no_bukti')
            ->get();
        $data['saldoawal'] = Klaimkaskecil::where('kode_klaim', '<', $kode_klaim)
            ->where('kode_cabang', $klaim->kode_cabang)->orderby('kode_klaim', 'desc')->first();

        $data['bank'] = Bank::orderBy('nama_bank')->get();
        return view('keuangan.klaimkaskecil.proses', $data);
    }


    public function storeproses($kode_klaim, Request $request)
    {

        $kode_klaim = Crypt::decrypt($kode_klaim);
        $klaim = Klaimkaskecil::findorfail($kode_klaim);
        if ($request->kode_bank == 'BK070' && $klaim->kode_cabang != 'PST') {
            $kode_akun = getAkunkaskecil($klaim->kode_cabang);
        } else {
            $kode_akun = '1-1104';
        }

        if ($klaim->kode_cabang == 'PST') {
            $lastledger = Ledger::select('no_bukti')
                ->whereRaw('LEFT(no_bukti,7) ="LRPST' . date('y', strtotime($request->tanggal)) . '"')
                ->whereRaw('LENGTH(no_bukti)=12')
                ->orderBy('no_bukti', 'desc')
                ->first();
            $last_no_bukti = $lastledger != null ?  $lastledger->no_bukti : '';
            $no_bukti = buatkode($last_no_bukti, 'LRPST' . date('y', strtotime($request->tanggal)), 5);
        } else {
            $lastledger = Ledger::select('no_bukti')
                ->whereRaw('LEFT(no_bukti,7) ="LR' . $klaim->kode_cabang . date('y', strtotime($request->tanggal)) . '"')
                ->orderBy('no_bukti', 'desc')
                ->first();
            $last_no_bukti = $lastledger != null ?  $lastledger->no_bukti : '';
            $no_bukti = buatkode($last_no_bukti, 'LR' . $klaim->kode_cabang . date('y', strtotime($request->tanggal)), 4);
        }

        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutuplaporan($request->tanggal, 'ledger');
            if ($cektutuplaporan > 0) {
                return redirect()->back()->with(['error' => 'Maaf laporan untuk tanggal ' . $request->tanggal . ' sudah tutup']);
            }

            Ledger::create([
                'no_bukti' => $no_bukti,
                'tanggal' => $request->tanggal,
                'kode_bank' => $request->kode_bank,
                'pelanggan' => 'CAB ' . $klaim->kode_cabang,
                'keterangan' => $request->keterangan,
                'kode_akun' => $kode_akun,
                'jumlah' => toNumber($request->jml_penggantian),
                'debet_kredit' => 'D',
            ]);

            Ledgerklaim::create([
                'no_bukti' => $no_bukti,
                'kode_klaim' => $kode_klaim,
            ]);

            Klaimkaskecil::where('kode_klaim', $kode_klaim)->update([
                'status' => 1,
                'saldo_akhir' => toNumber($request->saldo_akhir),
            ]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function cancelproses($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $ledger = Ledger::where('no_bukti', $no_bukti)->first();
        $ledgerklaim = Ledgerklaim::where('no_bukti', $no_bukti)->first();
        try {
            $cektutuplaporan = cektutuplaporan($ledger->tanggal, 'ledger');
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }
            Ledger::where('no_bukti', $no_bukti)->delete();
            Klaimkaskecil::where('kode_klaim', $ledgerklaim->kode_klaim)->update([
                'status' => 0,
                'saldo_akhir' => 0,
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil di Dibatalkan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function approve($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $ledger = Ledger::where('no_bukti', $no_bukti)->first();
        $ledgerklaim = Ledgerklaim::where('no_bukti', $no_bukti)
            ->join('keuangan_kaskecil_klaim', 'keuangan_ledger_klaim.kode_klaim', '=', 'keuangan_kaskecil_klaim.kode_klaim')
            ->first();
        if ($ledger->kode_cabang == 'PST') {
            $kode_akun = '1-1104';
        } else {
            $kode_akun = getAkunkaskecil($ledgerklaim->kode_cabang);
        }
        try {
            $cektutuplaporan = cektutuplaporan($ledger->tanggal, 'kaskecil');
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }
            Kaskecil::create([
                'no_bukti' => $no_bukti,
                'tanggal' => $ledger->tanggal,
                'keterangan' => 'Penerimaan Kas Kecil',
                'jumlah' => $ledger->jumlah,
                'debet_kredit' => 'K',
                'kode_akun' => $kode_akun,
                'kode_cabang' => $ledgerklaim->kode_cabang,
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function cancelapprove($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $kaskecil = Kaskecil::where('no_bukti', $no_bukti)->first();

        try {
            $cektutuplaporan = cektutuplaporan($kaskecil->tanggal, 'kaskecil');
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }
            Kaskecil::where('no_bukti', $no_bukti)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil di Dibatalkan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function create()
    {

        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('keuangan.klaimkaskecil.create', $data);
    }

    public function getData(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');

        $awal_kas_kecil = '2019-04-30';
        $sehariSebelumDari = date('Y-m-d', strtotime('-1 day', strtotime($request->dari)));

        $query = Kaskecil::query();
        $query->select('keuangan_kaskecil.*', 'nama_akun', 'kode_klaim');
        $query->join('coa', 'keuangan_kaskecil.kode_akun', '=', 'coa.kode_akun');
        $query->leftJoin('keuangan_kaskecil_klaim_detail', 'keuangan_kaskecil.id', '=', 'keuangan_kaskecil_klaim_detail.id');
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('kode_cabang', $request->kode_cabang);
            } else {
                $query->where('kode_cabang', auth()->user()->kode_cabang);
            }
        } else {
            $query->where('kode_cabang', $request->kode_cabang);
        }



        $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
        $query->orderBy('tanggal');
        $query->orderBy('debet_kredit', 'desc');
        $query->orderBy('no_bukti');
        $kaskecil = $query->get();

        $qsaldoawal = Kaskecil::query();
        $qsaldoawal->selectRaw("SUM(IF( `debet_kredit` = 'K', jumlah, 0)) -SUM(IF( `debet_kredit` = 'D', jumlah, 0)) as saldo_awal");
        $qsaldoawal->whereBetween('tanggal', [$awal_kas_kecil, $sehariSebelumDari]);
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $qsaldoawal->where('kode_cabang', $request->kode_cabang);
            } else {
                $qsaldoawal->where('kode_cabang', auth()->user()->kode_cabang);
            }
        } else {
            $qsaldoawal->where('kode_cabang', $request->kode_cabang);
        }
        $saldoawal = $qsaldoawal->first();

        $data['saldoawal'] = $saldoawal;
        $data['kaskecil'] = $kaskecil;

        return view('keuangan.klaimkaskecil.getdata', $data);
    }

    public function store(Request $request)
    {

        $request->validate([
            'tanggal' => 'required',
            'keterangan' => 'required',
            'id_kaskecil' => 'required',
        ]);
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang =  auth()->user()->kode_cabang;
            }
        } else {
            $kode_cabang =  $request->kode_cabang;
        }

        $cektutuplaporan = cektutuplaporan($request->tanggal, 'kaskecil');
        if ($cektutuplaporan > 0) {
            return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
        }
        $id_kaskecil = $request->id_kaskecil;
        DB::beginTransaction();
        try {
            $lastklaim = Klaimkaskecil::select('kode_klaim')
                ->whereRaw('LEFT(kode_klaim,7) ="KL' . $kode_cabang . date('y', strtotime($request->tanggal)) . '"')
                ->orderBy('kode_klaim', 'desc')->first();
            $last_kode_klaim = $lastklaim != null ?  $lastklaim->kode_klaim : '';
            $kode_klaim = buatkode($last_kode_klaim, 'KL' . $kode_cabang . date('y', strtotime($request->tanggal)), 4);

            $cek_klaim = Klaimkaskecil::where('status', 0)->where('kode_cabang', $kode_cabang)->count();
            if ($cek_klaim > 0) {
                return Redirect::back()->with(messageError('Ada Klaim Yang Belum Di Proses'));
            }

            Klaimkaskecil::create([
                'kode_klaim' => $kode_klaim,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
                'status' => 0,
                'kode_cabang' => $kode_cabang,
                'saldo_akhir' => 0,
            ]);

            //dd($id_kaskecil);
            for ($i = 0; $i < count($id_kaskecil); $i++) {
                $datakaskecil[] = [
                    'kode_klaim' => $kode_klaim,
                    'id' => $id_kaskecil[$i],
                ];
            }

            Detailklaimkaskecil::insert($datakaskecil);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_klaim)
    {
        $kode_klaim = Crypt::decrypt($kode_klaim);
        $klaim = Klaimkaskecil::where('kode_klaim', $kode_klaim)->first();
        if (!$klaim) {
            return Redirect::back()->with(messageError('Data tidak ditemukan'));
        }
        $cektutuplaporan = cektutuplaporan($klaim->tanggal, 'kaskecil');
        if ($cektutuplaporan > 0) {
            return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
        }
        try {
            $klaim->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
