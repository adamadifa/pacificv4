<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Detailharilibur;
use App\Models\Harilibur;
use App\Models\Kategorilibur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class HariliburController extends Controller
{
    public function index(Request $request)
    {
        $hl = new Harilibur();
        $harilibur = $hl->getHarilibur(request: $request)->paginate(15);
        $harilibur->appends(request()->all());
        $data['harilibur'] = $harilibur;

        $data['kategorilibur'] = Kategorilibur::orderBy('kode_kategori')->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        return view('hrd.harilibur.index', $data);
    }

    public function create()
    {
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        $data['kategorilibur'] = Kategorilibur::orderBy('kode_kategori')->get();
        return view('hrd.harilibur.create', $data);
    }

    public function store(Request $request)
    {
        $user = User::findorFail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $validationRules = [
            'tanggal' => 'required|date',
            'kategori' => 'required',
            'keterangan' => 'required',
            'kode_dept' => 'required_if:kode_cabang,PST',
        ];
        if (in_array($role, ['super admin', 'asst. manager hrd', 'spv presensi'])) {
            $validationRules['kode_cabang'] = 'required';
        }
        $request->validate($validationRules);

        try {
            $lastharilibur = Harilibur::select('kode_libur')
                ->whereRaw('MID(kode_libur,3,2)="' . date('y', strtotime($request->tanggal)) . '"')
                ->orderBy('kode_libur', 'desc')
                ->first();
            $last_kode_libur = $lastharilibur != null ? $lastharilibur->kode_libur : '';
            $kode_libur = buatkode($last_kode_libur, "LB" . date('y', strtotime($request->tanggal)), 3);

            $tanggal_limajam = isset($request->limajam) ?  date('Y-m-d', strtotime('-1 day', strtotime($request->tanggal))) : null;

            if (!in_array($role, ['super admin', 'asst. manager hrd', 'spv presensi'])) {
                if ($user->kode_cabang != 'PST') {
                    $kode_cabang = $user->kode_cabang;
                    $kode_dept = null;
                } else {
                    $kode_cabang = $user->kode_cabang;
                    $kode_dept = $user->kode_dept;
                }
            } else {
                $kode_cabang = $request->kode_cabang;
                $kode_dept = $request->kode_dept;
            }
            Harilibur::create([
                'kode_libur' => $kode_libur,
                'tanggal' => $request->tanggal,
                'kode_cabang' => $kode_cabang,
                'kode_dept' => $kode_dept,
                'kategori' => $request->kategori,
                'keterangan' => $request->keterangan,
                'tanggal_diganti' => $request->tanggal_diganti,
                'tanggal_limajam' => $tanggal_limajam,
            ]);

            return Redirect::back()->with(messageSuccess('Data Harilibur Berhasil Di Tambahkan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($kode_libur)
    {
        $kode_libur = Crypt::decrypt($kode_libur);
        $data['harilibur'] = Harilibur::where('kode_libur', $kode_libur)->first();
        $data['kategorilibur'] = Kategorilibur::orderBy('kode_kategori')->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        return view('hrd.harilibur.edit', $data);
    }

    public function update(Request $request, $kode_libur)
    {
        $kode_libur = Crypt::decrypt($kode_libur);
        $user = User::findorFail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $validationRules = [
            'tanggal' => 'required|date',
            'kategori' => 'required',
            'keterangan' => 'required',
            'kode_dept' => 'required_if:kode_cabang,PST',
        ];
        if (in_array($role, ['super admin', 'asst. manager hrd', 'spv presensi'])) {
            $validationRules['kode_cabang'] = 'required';
        }
        $request->validate($validationRules);

        try {


            $tanggal_limajam = isset($request->limajam) ?  date('Y-m-d', strtotime('-1 day', strtotime($request->tanggal))) : null;

            if (!in_array($role, ['super admin', 'asst. manager hrd', 'spv presensi'])) {
                if ($user->kode_cabang != 'PST') {
                    $kode_cabang = $user->kode_cabang;
                    $kode_dept = null;
                } else {
                    $kode_cabang = $user->kode_cabang;
                    $kode_dept = $user->kode_dept;
                }
            } else {
                $kode_cabang = $request->kode_cabang;
                $kode_dept = $request->kode_dept;
            }
            Harilibur::where('kode_libur', $kode_libur)->update([
                'tanggal' => $request->tanggal,
                'kode_cabang' => $kode_cabang,
                'kode_dept' => $kode_dept,
                'kategori' => $request->kategori,
                'keterangan' => $request->keterangan,
                'tanggal_diganti' => $request->tanggal_diganti,
                'tanggal_limajam' => $tanggal_limajam,
            ]);

            return Redirect::back()->with(messageSuccess('Data Harilibur Berhasil Di Tambahkan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function aturharilibur($kode_libur)
    {
        $kode_libur = Crypt::decrypt($kode_libur);
        $hl = new Harilibur();
        $data['harilibur'] = $hl->getHarilibur(kode_libur: $kode_libur)->first();
        return view('hrd.harilibur.aturharilibur', $data);
    }

    function getkaryawanlibur($kode_libur)
    {
        $kode_libur = Crypt::decrypt($kode_libur);
        $data['detailharilibur'] = Detailharilibur::join('hrd_karyawan', 'hrd_harilibur_detail.nik', '=', 'hrd_karyawan.nik')
            ->where('kode_libur', $kode_libur)->get();
        return view('hrd.harilibur.getkaryawanlibur', $data);
    }
}
