<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\PencairanProgramIkatan2026;
use App\Models\Programikatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PencairanProgramIkatan2026Controller extends Controller
{
    public function index(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');

        $query = PencairanProgramIkatan2026::query();
        $query->select(
            'marketing_pencairan_ikatan_2026.*',
            'cabang.nama_cabang',
            'nama_program',
        );
        $query->join('program_ikatan', 'marketing_pencairan_ikatan_2026.kode_program', '=', 'program_ikatan.kode_program');
        $query->join('cabang', 'marketing_pencairan_ikatan_2026.kode_cabang', '=', 'cabang.kode_cabang');

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('marketing_pencairan_ikatan_2026.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        if (!empty($request->kode_cabang)) {
            $query->where('marketing_pencairan_ikatan_2026.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_program)) {
            $query->where('marketing_pencairan_ikatan_2026.kode_program', $request->kode_program);
        }

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('marketing_pencairan_ikatan_2026.tanggal', [$request->dari, $request->sampai]);
        }

        if ($user->hasRole('regional sales manager')) {
            $query->whereNotNull('marketing_pencairan_ikatan_2026.om');
            $query->where('marketing_pencairan_ikatan_2026.status', '!=', 2);
        }

        if ($user->hasRole('gm marketing')) {
            $query->whereNotNull('marketing_pencairan_ikatan_2026.rsm');
            $query->where('marketing_pencairan_ikatan_2026.status', '!=', 2);
        }

        if ($user->hasRole('direktur')) {
            $query->whereNotNull('marketing_pencairan_ikatan_2026.gm');
            $query->where('marketing_pencairan_ikatan_2026.status', '!=', 2);
        }

        $query->orderBy('marketing_pencairan_ikatan_2026.kode_pencairan', 'desc');
        
        $pencairanprogramikatan = $query->paginate(15);
        $pencairanprogramikatan->appends(request()->all());
        $data['pencairanprogramikatan'] = $pencairanprogramikatan;

        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        $data['user'] = $user;
        $data['programikatan'] = Programikatan::orderBy('kode_program')->get();
        return view('worksheetom.pencairanprogramikatan2026.index', $data);
    }

    public function create()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        $data['programikatan'] = Programikatan::orderBy('kode_program')->get();
        $data['roles_show_cabang'] = config('global.roles_show_cabang');
        return view('worksheetom.pencairanprogramikatan2026.create', $data);
        public function destroy($kode_pencairan)
    {
        $kode_pencairan = \Illuminate\Support\Facades\Crypt::decrypt($kode_pencairan);
        try {
            DB::beginTransaction();
            PencairanProgramIkatan2026::where('kode_pencairan', $kode_pencairan)->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

    public function store(Request $request)
    {
        $user = User::findorFail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        if (!$user->hasRole($roles_access_all_cabang)) {
            $request->validate([
                'tanggal' => 'required',
                'kode_program' => 'required',
                'bulan' => 'required',
                'tahun' => 'required',
                'keterangan' => 'required'
            ]);
        } else {
            $request->validate([
                'tanggal' => 'required',
                'kode_program' => 'required',
                'kode_cabang' => 'required',
                'bulan' => 'required',
                'tahun' => 'required',
                'keterangan' => 'required'
            ]);
        }

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang;
        }

        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $lastpencairan = PencairanProgramIkatan2026::select('kode_pencairan')->orderBy('kode_pencairan', 'desc')
            ->whereRaw('LEFT(kode_pencairan,4) = "PC' . $tahunlalu . '"')
            ->first();
            
        // Use standard code generation logic, assuming PC<YY><CAB><XXXX> or similar?
        // Let's check the old controller logic: `PC` . substr($tahun, 2, 2) . $kode_cabang . $format
        
        $format = "PC" . substr($tahun, 2, 2) . $kode_cabang;
        $lastpencairan = PencairanProgramIkatan2026::select('kode_pencairan')
            ->where('kode_pencairan', 'like', $format . '%')
            ->orderBy('kode_pencairan', 'desc')
            ->first();
        
        $last_kode_pencairan = $lastpencairan ? $lastpencairan->kode_pencairan : '';
        $kode_pencairan = buatkode($last_kode_pencairan, $format, 4);

        try {
            DB::beginTransaction();
            PencairanProgramIkatan2026::create([
                'kode_pencairan' => $kode_pencairan,
                'tanggal' => $request->tanggal,
                'kode_program' => $request->kode_program,
                'kode_cabang' => $kode_cabang,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'keterangan' => $request->keterangan,
                'status' => '0'
            ]);
            DB::commit();
            return Redirect::route('pencairanprogramikatan2026.index')->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
