<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Coacabang;
use App\Models\Costratio;
use App\Models\Kaskecil;
use App\Models\Kaskecilcostratio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class KaskecilController extends Controller
{
    //
    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');

        $awal_kas_kecil = '2019-04-30';
        $sehariSebelumDari = date('Y-m-d', strtotime('-1 day', strtotime($request->dari)));

        $query = Kaskecil::query();
        $query->select('keuangan_kaskecil.*', 'nama_akun', 'kode_klaim', 'kode_cr');
        $query->join('coa', 'keuangan_kaskecil.kode_akun', '=', 'coa.kode_akun');
        $query->leftJoin('keuangan_kaskecil_klaim_detail', 'keuangan_kaskecil.id', '=', 'keuangan_kaskecil_klaim_detail.id');
        $query->leftJoin('keuangan_kaskecil_costratio', 'keuangan_kaskecil.id', '=', 'keuangan_kaskecil_costratio.id');
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('kode_cabang', $request->kode_cabang_search);
            } else {
                $query->where('kode_cabang', auth()->user()->kode_cabang);
            }
        } else {
            $query->where('kode_cabang', $request->kode_cabang_search);
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
                $qsaldoawal->where('kode_cabang', $request->kode_cabang_search);
            } else {
                $qsaldoawal->where('kode_cabang', auth()->user()->kode_cabang);
            }
        } else {
            $qsaldoawal->where('kode_cabang', $request->kode_cabang_search);
        }

        // $qsaldoawal->where('kode_cabang', $request->kode_cabang_search);
        $saldoawal = $qsaldoawal->first();

        $data['saldoawal'] = $saldoawal;
        $data['kaskecil'] = $kaskecil;
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();

        return view('keuangan.kaskecil.index', $data);
    }


    public function create(Request $request)
    {

        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();

        $coacabang = new Coacabang();
        $data['coa'] = $coacabang->getCoacabang()->get();


        return view('keuangan.kaskecil.create', $data);
    }


    public function store(Request $request)
    {

        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);


        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
                $request->validate([
                    'no_bukti' => 'required',
                    'tanggal' => 'required',
                    'kode_cabang' => 'required',
                ]);
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang;
            $request->validate([
                'no_bukti' => 'required',
                'tanggal' => 'required',
                'kode_cabang' => 'required',
            ]);
        }

        $keterangan = $request->keterangan_item;
        $jumlah = $request->jumlah_item;
        $kode_akun = $request->kode_akun_item;
        $debet_kredit = $request->debet_kredit_item;
        DB::beginTransaction();
        try {
            $cektutuplaporankaskecil = cektutupLaporan($request->tanggal, "kaskecil");
            if ($cektutuplaporankaskecil > 0) {
                return redirect()->back()->with(['warning' => 'Laporan sudah tutup']);
            }
            $last_kodecr = null;
            for ($i = 0; $i < count($kode_akun); $i++) {


                $kaskecil = Kaskecil::create([
                    'no_bukti' => $request->no_bukti,
                    'tanggal' => $request->tanggal,
                    'keterangan' => $keterangan[$i],
                    'kode_akun' => $kode_akun[$i],
                    'jumlah' => toNumber($jumlah[$i]),
                    'debet_kredit' => $debet_kredit[$i],
                    'kode_cabang' => $kode_cabang
                ]);


                $cekAkun = substr($kode_akun[$i], 0, 3);


                //Inseert Cost Ratio
                if ($debet_kredit[$i] == 'D' and in_array($cekAkun, ['6-1', '6-2'])) {
                    $lastcostratio = Costratio::select('kode_cr')
                        ->whereRaw('LEFT(kode_cr,6) ="CR' . date('my', strtotime($request->tanggal)) . '"')
                        ->orderBy('kode_cr', 'desc')
                        ->first();
                    $last_kode_cr = $last_kodecr ?? ($lastcostratio ? $lastcostratio->kode_cr : '');

                    $kode_cr = buatkode($last_kode_cr, "CR" . date('my', strtotime($request->tanggal)), 4);
                    Costratio::create([
                        'kode_cr' => $kode_cr,
                        'tanggal' => $request->tanggal,
                        'kode_akun' => $kode_akun[$i],
                        'keterangan' => $keterangan[$i],
                        'kode_cabang' => $kode_cabang,
                        'kode_sumber' => 1,
                        'jumlah' => toNumber($jumlah[$i])
                    ]);

                    Kaskecilcostratio::create([
                        'kode_cr' => $kode_cr,
                        'id' => $kaskecil->id,
                    ]);
                    $last_kodecr = $kode_cr;
                }
            }

            DB::commit();
            return redirect()->back()->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e);
            return redirect()->back()->with(['warning' => $e->getMessage()]);
        }
    }


    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $data['kaskecil'] = Kaskecil::where('keuangan_kaskecil.id', $id)
            ->select('keuangan_kaskecil.*', 'kode_klaim')
            ->leftJoin('keuangan_kaskecil_klaim_detail', 'keuangan_kaskecil.id', '=', 'keuangan_kaskecil_klaim_detail.id')
            ->first();

        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();

        $coacabang = new Coacabang();
        $data['coa'] = $coacabang->getCoacabang()->get();


        return view('keuangan.kaskecil.edit', $data);
    }

    public function update(Request $request, $id)
    {


        $id = Crypt::decrypt($id);
        // dd($id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang;
        }

        // dd($id);
        DB::beginTransaction();
        try {

            $kaskecil = Kaskecil::where('keuangan_kaskecil.id', $id)
                ->select('keuangan_kaskecil.*', 'kode_klaim')
                ->leftJoin('keuangan_kaskecil_klaim_detail', 'keuangan_kaskecil.id', '=', 'keuangan_kaskecil_klaim_detail.id')
                ->first();

            //dd($kaskecil);
            $cektutuplaporankaskecil = cektutupLaporan($kaskecil->tanggal, "kaskecil");
            if ($cektutuplaporankaskecil > 0) {
                return Redirect::back()->with(['warning' => 'Laporan sudah tutup']);
            }

            $cektutuplaporan = cektutupLaporan($request->tanggal, "kaskecil");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(['warning' => 'Laporan sudah tutup']);
            }

            //dd($kaskecil);
            if (empty($kaskecil->kode_klaim)) {
                Kaskecil::where('id', $id)->update([
                    'kode_cabang' => $kode_cabang,
                    'no_bukti' => $request->no_bukti,
                    'tanggal' => $request->tanggal,
                    'keterangan' => $request->keterangan,
                    'debet_kredit' => $request->debet_kredit,
                    'kode_akun' => $request->kode_akun,
                    'jumlah' => toNumber($request->jumlah),
                ]);
                $debet_kredit = $request->debet_kredit;
            } else {
                Kaskecil::where('id', $id)->update([
                    // 'no_bukti' => $request->no_bukti,
                    'tanggal' => $request->tanggal,
                    'keterangan' => $request->keterangan,
                    'kode_akun' => $request->kode_akun,
                ]);
                $debet_kredit = $kaskecil->debet_kredit;
            }

            //dd($kaskecil);



            $cekCostratio = Kaskecilcostratio::where('id', $id)->first();

            $cekAkun = substr($request->kode_akun, 0, 3);

            //dd($kaskecil);
            //Inseert Cost Ratio

            // dd($cekCostratio);
            if ($debet_kredit == 'D' and in_array($cekAkun, ['6-1', '6-2'])) {
                // dd($cekCostratio);
                if (!$cekCostratio) {
                    $lastcostratio = Costratio::select('kode_cr')
                        ->whereRaw('LEFT(kode_cr,6) ="CR' . date('my', strtotime($request->tanggal)) . '"')
                        ->orderBy('kode_cr', 'desc')
                        ->first();
                    $last_kode_cr = $lastcostratio ? $lastcostratio->kode_cr : '';

                    $kode_cr = buatkode($last_kode_cr, "CR" . date('my', strtotime($request->tanggal)), 4);
                    Costratio::create([
                        'kode_cr' => $kode_cr,
                        'tanggal' => $request->tanggal,
                        'kode_akun' => $request->kode_akun,
                        'keterangan' => $request->keterangan,
                        'kode_cabang' => $kode_cabang,
                        'kode_sumber' => 1,
                        'jumlah' => toNumber($request->jumlah),
                    ]);

                    Kaskecilcostratio::create([
                        'kode_cr' => $kode_cr,
                        'id' => $kaskecil->id,
                    ]);
                } else {
                    // dd($kaskecil);
                    Costratio::where('kode_cr', $cekCostratio->kode_cr)->update([
                        'kode_akun' => $request->kode_akun,
                        'tanggal' => $request->tanggal,
                        'keterangan' => $request->keterangan,
                        'kode_cabang' => $kode_cabang,
                        'jumlah' => $kaskecil->jumlah,
                    ]);
                }
            } else {
                if ($cekCostratio) {
                    Costratio::where('kode_cr', $cekCostratio->kode_cr)->delete();
                }
            }

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($id)
    {

        $id = Crypt::decrypt($id);
        try {
            $kaskecil = Kaskecil::where('id', $id)->first();
            $cektutuplaporankaskecil = cektutupLaporan($kaskecil->tanggal, "kaskecil");
            if ($cektutuplaporankaskecil > 0) {
                return Redirect::back()->with(['warning' => 'Laporan sudah tutup']);
            }
            $cekcostratio = Kaskecilcostratio::where('id', $id)->first();
            if ($cekcostratio) {
                Costratio::where('kode_cr', $cekcostratio->kode_cr)->delete();
            }

            Kaskecil::where('id', $id)->delete();
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }
}
