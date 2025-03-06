<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Coa;
use App\Models\Costratio;
use App\Models\Jurnalumum;
use App\Models\Jurnalumumcostratio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class JurnalumumController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);

        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }

        $query = Jurnalumum::query();
        $query->select('accounting_jurnalumum.*', 'nama_akun', 'kode_cr');
        $query->join('coa', 'accounting_jurnalumum.kode_akun', '=', 'coa.kode_akun');
        $query->leftJoin('accounting_jurnalumum_costratio', 'accounting_jurnalumum.kode_ju', '=', 'accounting_jurnalumum_costratio.kode_ju');
        $query->whereBetween('accounting_jurnalumum.tanggal', [$request->dari, $request->sampai]);
        if (!empty($request->kode_cabang_search)) {
            $query->where('accounting_jurnalumum.kode_cabang', $request->kode_cabang_search);
        }

        if ($user->hasRole(['manager general affair', 'general affair'])) {
            $query->where('accounting_jurnalumum.kode_dept', 'GAF');
        } else if ($user->hasRole(['asst. manager hrd'])) {
            $query->where('accounting_jurnalumum.kode_dept', 'HRD');
        }
        $query->orderBy('accounting_jurnalumum.tanggal');
        $query->orderBy('accounting_jurnalumum.kode_ju');
        $data['jurnalumum'] = $query->get();
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('accounting.jurnalumum.index', $data);
    }

    public function create()
    {
        $data['coa'] = Coa::orderby('kode_akun')->whereNotIn('kode_akun', ['1', '2'])->get();
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('accounting.jurnalumum.create', $data);
    }

    public function store(Request $request)
    {
        $tanggal = $request->tanggal_item;
        $kode_akun = $request->kode_akun_item;
        $keterangan = $request->keterangan_item;
        $jumlah = $request->jumlah_item;
        $debet_kredit = $request->debet_kredit_item;
        $kode_peruntukan = $request->kode_peruntukan_item;
        $kode_cabang = $request->kode_cabang_item;
        $kode_dept_list = ['GAF', 'AKT'];
        $kode_dept = in_array(auth()->user()->kode_dept, $kode_dept_list) ? auth()->user()->kode_dept : 'AKT';
        DB::beginTransaction();
        try {


            if (count($kode_akun) === 0) {
                return Redirect::back()->with(messageError('Data Masih Kosong'));
            }

            for ($i = 0; $i < count($kode_akun); $i++) {
                $cektutuplaporan = cektutupLaporan($tanggal[$i], "jurnalumum");
                if ($cektutuplaporan > 0) {
                    return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
                }

                $lastjurnalumum = Jurnalumum::select('kode_ju')
                    ->whereRaw('LEFT(kode_ju,6)="JL' . date('ym', strtotime($tanggal[$i])) . '"')
                    ->orderBy('kode_ju', 'desc')
                    ->first();

                $last_kode_ju = $lastjurnalumum != null ?  $lastjurnalumum->kode_ju : '';
                $kode_ju = buatkode($last_kode_ju, 'JL' . date('ym', strtotime($tanggal[$i])), 3);

                Jurnalumum::create([
                    'kode_ju' => $kode_ju,
                    'tanggal' => $tanggal[$i],
                    'kode_akun' => $kode_akun[$i],
                    'keterangan' => $keterangan[$i],
                    'debet_kredit' => $debet_kredit[$i],
                    'kode_peruntukan' => $kode_peruntukan[$i],
                    'kode_cabang' => $kode_cabang[$i],
                    'kode_dept' => $kode_dept,
                    'jumlah' => toNumber($jumlah[$i]),
                    'id_user' => auth()->user()->id
                ]);

                if ($debet_kredit[$i] == 'D' && in_array(substr($kode_akun[$i], 0, 3), ['6-1', '6-2']) && $kode_peruntukan[$i] == 'PC') {
                    $lastcostratio = Costratio::select('kode_cr')
                        ->whereRaw('LEFT(kode_cr,6) ="CR' . date('my', strtotime($tanggal[$i])) . '"')
                        ->orderBy('kode_cr', 'desc')
                        ->first();
                    $last_kode_cr = $lastcostratio != null ? $lastcostratio->kode_cr : '';
                    $kode_cr =  buatkode($last_kode_cr, "CR" . date('my', strtotime($tanggal[$i])), 4);

                    Costratio::create([
                        'kode_cr' => $kode_cr,
                        'tanggal' => $tanggal[$i],
                        'kode_akun' => $kode_akun[$i],
                        'keterangan' => $keterangan[$i],
                        'kode_cabang' => $kode_cabang[$i],
                        'kode_sumber'   => 5,
                        'jumlah' => toNumber($jumlah[$i]),
                    ]);

                    Jurnalumumcostratio::create([
                        'kode_ju' => $kode_ju,
                        'kode_cr' => $kode_cr,
                    ]);
                }
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($kode_ju)
    {
        $kode_ju = Crypt::decrypt($kode_ju);
        $data['coa'] = Coa::orderby('kode_akun')->whereNotIn('kode_akun', ['1', '2'])->get();
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        $data['jurnalumum'] = Jurnalumum::where('kode_ju', $kode_ju)->first();
        return view('accounting.jurnalumum.edit', $data);
    }

    public function update($kode_ju, Request $request)
    {
        $kode_ju = Crypt::decrypt($kode_ju);
        $jurnalumum = Jurnalumum::where('kode_ju', $kode_ju)->first();
        if (!$jurnalumum) {
            return Redirect::back()->with(messageError('Data tidak ditemukan'));
        }

        $request->validate([
            'tanggal' => 'required|date',
            'kode_akun' => 'required',
            'keterangan' => 'required',
            'jumlah' => 'required',
            'debet_kredit' => 'required',
            'kode_peruntukan' => 'required',
            'kode_cabang' => 'required_if:kode_peruntukan,PC',
        ]);


        DB::beginTransaction();

        try {
            $cektutuplaporan = cektutupLaporan($request->tanggal, "jurnalumum");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $cektutuplaporanjurnalumum = cektutupLaporan($jurnalumum->tanggal, "jurnalumum");
            if ($cektutuplaporanjurnalumum > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }
            $jurnalumum->update([
                'tanggal' => $request->tanggal,
                'kode_akun' => $request->kode_akun,
                'keterangan' => $request->keterangan,
                'jumlah' => toNumber($request->jumlah),
                'debet_kredit' => $request->debet_kredit,
                'kode_peruntukan' => $request->kode_peruntukan,
                'kode_cabang' => $request->kode_cabang,
            ]);


            $costratio = Jurnalumumcostratio::where('kode_ju', $jurnalumum->kode_ju)->first();
            if ($request->debet_kredit == 'D' && in_array(substr($request->kode_akun, 0, 3), ['6-1', '6-2']) && $request->kode_peruntukan == 'PC') {
                if (!$costratio) {
                    $lastcostratio = Costratio::select('kode_cr')
                        ->whereRaw('LEFT(kode_cr,6) ="CR' . date('my', strtotime($request->tanggal)) . '"')
                        ->orderBy('kode_cr', 'desc')
                        ->first();
                    $last_kode_cr = $lastcostratio != null ? $lastcostratio->kode_cr : '';
                    $kode_cr =  buatkode($last_kode_cr, "CR" . date('my', strtotime($request->tanggal)), 4);
                    Costratio::create([
                        'kode_cr' => $kode_cr,
                        'tanggal' => $request->tanggal,
                        'kode_akun' => $request->kode_akun,
                        'keterangan' => $request->keterangan,
                        'kode_cabang' => $request->kode_cabang,
                        'kode_sumber'   => 5,
                        'jumlah' => toNumber($request->jumlah),
                    ]);
                    Jurnalumumcostratio::create([
                        'kode_ju' => $jurnalumum->kode_ju,
                        'kode_cr' => $kode_cr,
                    ]);
                } else {
                    Costratio::where('kode_cr', $costratio->kode_cr)->update([
                        'tanggal' => $request->tanggal,
                        'kode_akun' => $request->kode_akun,
                        'keterangan' => $request->keterangan,
                        'jumlah' => toNumber($request->jumlah),
                        'kode_cabang' => $request->kode_cabang,
                    ]);
                }
            } else {
                if ($costratio) {
                    Jurnalumumcostratio::where('kode_ju', $jurnalumum->kode_ju)->delete();
                }
            }


            DB::commit();
            return Redirect::back()->with(messageSuccess('Data berhasil diupdate'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_ju)
    {
        $kode_ju = Crypt::decrypt($kode_ju);
        DB::beginTransaction();
        try {
            $jurnalumum = Jurnalumum::where('kode_ju', $kode_ju)->first();
            $cektutuplaporan = cektutupLaporan($jurnalumum->tanggal, "jurnalumum");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }
            $jurnalumumcostratio = Jurnalumumcostratio::where('kode_ju', $kode_ju)->first();
            if ($jurnalumumcostratio) {
                Costratio::where('kode_cr', $jurnalumumcostratio->kode_cr)->delete();
            }
            $jurnalumum->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data berhasil dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
