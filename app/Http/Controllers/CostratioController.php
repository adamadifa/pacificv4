<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Costratio;
use App\Models\Jurnalumum;
use App\Models\Kaskecil;
use App\Models\Ledger;
use App\Models\Sumbercostratio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CostratioController extends Controller
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

        $query = Costratio::query();
        $query->select('accounting_costratio.*', 'sumber', 'nama_cabang', 'nama_akun');
        $query->join('accounting_costratio_sumber', 'accounting_costratio.kode_sumber', '=', 'accounting_costratio_sumber.kode_sumber');
        $query->join('cabang', 'accounting_costratio.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('coa', 'accounting_costratio.kode_akun', '=', 'coa.kode_akun');
        $query->whereBetween('accounting_costratio.tanggal', [$request->dari, $request->sampai]);

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('accounting_costratio.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('accounting_costratio.kode_cabang', $request->kode_cabang_search);
        }
        if (!empty($request->kode_sumber_search)) {
            $query->where('accounting_costratio.kode_sumber', $request->kode_sumber_search);
        }


        $query->orderBy('accounting_costratio.tanggal');
        $costratio = $query->paginate(15);
        $costratio->appends(request()->all());
        $data['costratio'] = $costratio;
        $data['sumber'] = Sumbercostratio::orderBy('kode_sumber')->get();
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('accounting.costratio.index', $data);
    }

    public function create()
    {

        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('accounting.costratio.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required',
            'keterangan' => 'required',
            'jumlah' => 'required',
            'kode_cabang' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $lastcostratio = Costratio::select('kode_cr')
                ->whereRaw('LEFT(kode_cr,6) ="CR' . date('my', strtotime($request->tanggal)) . '"')
                ->orderBy('kode_cr', 'desc')
                ->first();
            $last_kode_cr = $lastcostratio != null ? $lastcostratio->kode_cr : '';

            $kode_cr = buatkode($last_kode_cr, "CR" . date('my', strtotime($request->tanggal)), 4);


            Costratio::create([
                'kode_cr' => $kode_cr,
                'tanggal' => $request->tanggal,
                'kode_akun' => $request->keterangan == "Sewa Gedung" ? 1 : 2,
                'jumlah' => toNumber($request->jumlah),
                'keterangan' => $request->keterangan,
                'kode_cabang' => $request->kode_cabang,
                'kode_sumber' => 3
            ]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_cr)
    {
        $kode_cr = Crypt::decrypt($kode_cr);
        try {
            $costratio = Costratio::where('kode_cr', $kode_cr);
            if (!$costratio) {
                return Redirect::back()->with(messageError('Data tidak ditemukan'));
            }
            $costratio->delete();
            return Redirect::back()->with(messageSuccess('Data berhasil dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function cetak(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');

        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }

        $query = Costratio::query();
        $query->select('accounting_costratio.*', 'sumber', 'nama_cabang', 'nama_akun', 
            'keuangan_kaskecil_costratio.id as id_kaskecil',
            'keuangan_ledger_costratio.no_bukti as no_bukti_ledger',
            'accounting_jurnalumum_costratio.kode_ju as kode_ju_jurnalumum');
        $query->join('accounting_costratio_sumber', 'accounting_costratio.kode_sumber', '=', 'accounting_costratio_sumber.kode_sumber');
        $query->join('cabang', 'accounting_costratio.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('coa', 'accounting_costratio.kode_akun', '=', 'coa.kode_akun');
        $query->leftJoin('keuangan_kaskecil_costratio', 'accounting_costratio.kode_cr', '=', 'keuangan_kaskecil_costratio.kode_cr');
        $query->leftJoin('keuangan_ledger_costratio', 'accounting_costratio.kode_cr', '=', 'keuangan_ledger_costratio.kode_cr');
        $query->leftJoin('accounting_jurnalumum_costratio', 'accounting_costratio.kode_cr', '=', 'accounting_jurnalumum_costratio.kode_cr');
        $query->whereBetween('accounting_costratio.tanggal', [$request->dari, $request->sampai]);

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('accounting_costratio.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('accounting_costratio.kode_cabang', $request->kode_cabang_search);
        }
        if (!empty($request->kode_sumber_search)) {
            $query->where('accounting_costratio.kode_sumber', $request->kode_sumber_search);
        }


        $query->orderBy('accounting_costratio.tanggal');
        $costratio = $query->get();
        
        // Load relasi kaskecil untuk data yang memiliki id_kaskecil (sumber = 1)
        // Load relasi ledger untuk data yang memiliki no_bukti_ledger (sumber = 2)
        foreach ($costratio as $item) {
            if ($item->id_kaskecil) {
                $item->kaskecil = Kaskecil::find($item->id_kaskecil);
            }
            if ($item->no_bukti_ledger) {
                $item->ledger = Ledger::find($item->no_bukti_ledger);
            }
            if ($item->kode_ju_jurnalumum) {
                $item->jurnalumum = Jurnalumum::find($item->kode_ju_jurnalumum);
            }
        }
        
        $data['costratio'] = $costratio;
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;


        if (isset($_GET['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Ajuan Transfer Dana $request->dari-$request->sampai.xls");
        }
        return view('accounting.costratio.cetak', $data);
    }

    public function syncnominal(Request $request)
    {
        $dari = $request->dari;
        $sampai = $request->sampai;
        $kode_cabang = $request->kode_cabang;

        $query = DB::table('accounting_costratio')
            ->select(
                'accounting_costratio.kode_cr',
                'accounting_costratio.jumlah as jumlah_cr',
                'accounting_costratio.kode_akun as kode_akun_cr',
                'kk.jumlah as jumlah_kaskecil',
                'kk.kode_akun as kode_akun_kaskecil',
                'l.jumlah as jumlah_ledger',
                'l.kode_akun as kode_akun_ledger',
                'ju.jumlah as jumlah_jurnalumum',
                'ju.kode_akun as kode_akun_jurnalumum'
            )
            ->leftJoin('keuangan_kaskecil_costratio', 'accounting_costratio.kode_cr', '=', 'keuangan_kaskecil_costratio.kode_cr')
            ->leftJoin('keuangan_ledger_costratio', 'accounting_costratio.kode_cr', '=', 'keuangan_ledger_costratio.kode_cr')
            ->leftJoin('accounting_jurnalumum_costratio', 'accounting_costratio.kode_cr', '=', 'accounting_jurnalumum_costratio.kode_cr')
            ->leftJoin('keuangan_kaskecil as kk', 'keuangan_kaskecil_costratio.id', '=', 'kk.id')
            ->leftJoin('keuangan_ledger as l', 'keuangan_ledger_costratio.no_bukti', '=', 'l.no_bukti')
            ->leftJoin('accounting_jurnalumum as ju', 'accounting_jurnalumum_costratio.kode_ju', '=', 'ju.kode_ju')
            ->whereBetween('accounting_costratio.tanggal', [$dari, $sampai]);

        if (!empty($kode_cabang)) {
            $query->where('accounting_costratio.kode_cabang', $kode_cabang);
        }

        $costratio = $query->get();
        $updatedCount = 0;

        foreach ($costratio as $item) {
            $newJumlah = null;
            $newKodeAkun = null;

            if ($item->jumlah_kaskecil !== null) {
                $newJumlah = $item->jumlah_kaskecil;
                $newKodeAkun = $item->kode_akun_kaskecil;
            } elseif ($item->jumlah_ledger !== null) {
                $newJumlah = $item->jumlah_ledger;
                $newKodeAkun = $item->kode_akun_ledger;
            } elseif ($item->jumlah_jurnalumum !== null) {
                $newJumlah = $item->jumlah_jurnalumum;
                $newKodeAkun = $item->kode_akun_jurnalumum;
            }

            if ($newJumlah !== null) {
                if ($newJumlah != $item->jumlah_cr || $newKodeAkun != $item->kode_akun_cr) {
                    DB::table('accounting_costratio')
                        ->where('kode_cr', $item->kode_cr)
                        ->update([
                            'jumlah' => $newJumlah,
                            'kode_akun' => $newKodeAkun
                        ]);
                    $updatedCount++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Berhasil menyinkronkan nominal dan kode akun. $updatedCount data diperbarui."
        ]);
    }
}
