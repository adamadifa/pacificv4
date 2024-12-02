<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Detailharilibur;
use App\Models\Group;
use App\Models\Harilibur;
use App\Models\Karyawan;
use App\Models\Kategorilibur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
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
                ->whereRaw('LEFT(kode_libur,2)="' . "LB" . '"')
                ->orderBy('kode_libur', 'desc')
                ->first();

            // $lasthariliburLR = Harilibur::select('kode_libur')
            //     ->whereRaw('MID(kode_libur,3,2)="' . date('y', strtotime($request->tanggal)) . '"')
            //     ->whereRaw('LEFT(kode_libur,2)="' . "LR" . '"')
            //     ->orderBy('kode_libur', 'desc')
            //     ->first();
            $last_kode_libur = $lastharilibur != null ? $lastharilibur->kode_libur : '';


            $last3digit = substr($last_kode_libur, -3);
            if ($last3digit == '999') {
                $format = "LR";
            } else {
                $format = "LB";
            }

            $lastharilibur = Harilibur::select('kode_libur')
                ->whereRaw('MID(kode_libur,3,2)="' . date('y', strtotime($request->tanggal)) . '"')
                ->whereRaw('LEFT(kode_libur,2)="' . $format . '"')
                ->orderBy('kode_libur', 'desc')
                ->first();
            $last_kode_libur = $lastharilibur != null ? $lastharilibur->kode_libur : '';

            $kode_libur = buatkode($last_kode_libur, $format . date('y', strtotime($request->tanggal)), 3);

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
            ->join('hrd_group', 'hrd_karyawan.kode_group', '=', 'hrd_group.kode_group')
            ->where('kode_libur', $kode_libur)->get();
        return view('hrd.harilibur.getkaryawanlibur', $data);
    }

    public function aturkaryawan($kode_libur)
    {
        $kode_libur = Crypt::decrypt($kode_libur);
        $hl = new Harilibur();
        $harilibur = $hl->getHarilibur(kode_libur: $kode_libur)->first();
        $data['harilibur'] = $harilibur;
        if ($harilibur->kode_cabang != 'PST') {
            $data['group'] = Karyawan::where('hrd_karyawan.kode_cabang', $harilibur->kode_cabang)
                ->select('hrd_karyawan.kode_group', 'nama_group')
                ->join('hrd_group', 'hrd_karyawan.kode_group', '=', 'hrd_group.kode_group')
                ->orderBy('hrd_karyawan.kode_group')
                ->groupBy('hrd_karyawan.kode_group', 'nama_group')
                ->get();
        } else {
            $data['group'] = Karyawan::where('hrd_karyawan.kode_dept', $harilibur->kode_dept)
                ->select('hrd_karyawan.kode_group', 'nama_group')
                ->join('hrd_group', 'hrd_karyawan.kode_group', '=', 'hrd_group.kode_group')
                ->orderBy('hrd_karyawan.kode_group')
                ->groupBy('hrd_karyawan.kode_group', 'nama_group')->get();
        }


        return view('hrd.harilibur.aturkaryawan', $data);
    }

    function getkaryawan(Request $request)
    {
        $kode_libur = Crypt::decrypt($request->kode_libur);
        $hl = new Harilibur();
        $harilibur = $hl->getHarilibur(kode_libur: $kode_libur)->first();
        $data['harilibur'] = $harilibur;
        $query = Karyawan::query();
        $query->select('hrd_karyawan.nik', 'hrd_karyawan.nama_karyawan', 'hrd_karyawan.kode_group', 'hrd_group.nama_group', 'harilibur.nik as ceklibur');
        if ($harilibur->kode_cabang != 'PST') {
            $query->where('hrd_karyawan.kode_cabang', $harilibur->kode_cabang);
        } else {
            $query->where('hrd_karyawan.kode_dept', $harilibur->kode_dept);
        }

        if (!empty($request->kode_group)) {
            $query->where('hrd_karyawan.kode_group', $request->kode_group);
        }

        if (!empty($request->nama_karyawan)) {
            $query->where('nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
        }

        //left join ke detail hari libur berdasarkan kode libur
        $query->leftJoin(
            DB::raw("(
                SELECT nik FROM hrd_harilibur_detail
                WHERE kode_libur = '$kode_libur'
            ) harilibur"),
            function ($join) {
                $join->on('hrd_karyawan.nik', '=', 'harilibur.nik');
            }
        );
        $query->join('hrd_group', 'hrd_karyawan.kode_group', '=', 'hrd_group.kode_group');
        $query->orderBy('hrd_karyawan.kode_group');
        $query->orderBy('nama_karyawan');
        $data['karyawan'] = $query->get();
        return view('hrd.harilibur.getkaryawan', $data);
    }

    public function updateliburkaryawan(Request $request)
    {
        try {
            $cek = Detailharilibur::where('nik', $request->nik)->where('kode_libur', $request->kode_libur)->first();
            if ($cek != null) {
                Detailharilibur::where('nik', $request->nik)->where('kode_libur', $request->kode_libur)->delete();
            } else {
                Detailharilibur::create([
                    'nik' => $request->nik,
                    'kode_libur' => $request->kode_libur,
                ]);
            }
            return response()->json(['success' => true, 'message' => 'Update Success']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function tambahkansemua(Request $request)
    {
        $kode_libur = $request->kode_libur;
        $hl = new Harilibur();
        $harilibur = $hl->getHarilibur(kode_libur: $kode_libur)->first();
        $data['harilibur'] = $harilibur;
        $query = Karyawan::query();
        $query->select('hrd_karyawan.nik', 'hrd_karyawan.nama_karyawan', 'hrd_karyawan.kode_group', 'hrd_group.nama_group', 'harilibur.nik as ceklibur');
        if ($harilibur->kode_cabang != 'PST') {
            $query->where('hrd_karyawan.kode_cabang', $harilibur->kode_cabang);
        } else {
            $query->where('hrd_karyawan.kode_dept', $harilibur->kode_dept);
        }

        if (!empty($request->kode_group)) {
            $query->where('hrd_karyawan.kode_group', $request->kode_group);
        }

        if (!empty($request->nama_karyawan)) {
            $query->where('nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
        }
        //left join ke detail hari libur berdasarkan kode libur
        $query->leftJoin(
            DB::raw("(
                SELECT nik FROM hrd_harilibur_detail
                WHERE kode_libur = '$kode_libur'
            ) harilibur"),
            function ($join) {
                $join->on('hrd_karyawan.nik', '=', 'harilibur.nik');
            }
        );
        $query->join('hrd_group', 'hrd_karyawan.kode_group', '=', 'hrd_group.kode_group');
        $query->orderBy('hrd_karyawan.kode_group');
        $query->orderBy('nama_karyawan');
        $karyawan = $query->get();

        try {
            //Hapus Data Libur
            Detailharilibur::where('kode_libur', $request->kode_libur)->delete();
            foreach ($karyawan as $d) {
                Detailharilibur::create([
                    'nik' => $d->nik,
                    'kode_libur' => $request->kode_libur,
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Update Success']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function batalkansemua(Request $request)
    {
        $kode_libur = $request->kode_libur;
        $hl = new Harilibur();
        $harilibur = $hl->getHarilibur(kode_libur: $kode_libur)->first();
        $data['harilibur'] = $harilibur;
        $query = Karyawan::query();
        $query->select('hrd_karyawan.nik', 'hrd_karyawan.nama_karyawan', 'hrd_karyawan.kode_group', 'hrd_group.nama_group', 'harilibur.nik as ceklibur');
        if ($harilibur->kode_cabang != 'PST') {
            $query->where('hrd_karyawan.kode_cabang', $harilibur->kode_cabang);
        } else {
            $query->where('hrd_karyawan.kode_dept', $harilibur->kode_dept);
        }

        if (!empty($request->kode_group)) {
            $query->where('hrd_karyawan.kode_group', $request->kode_group);
        }

        if (!empty($request->nama_karyawan)) {
            $query->where('nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
        }
        //left join ke detail hari libur berdasarkan kode libur
        $query->leftJoin(
            DB::raw("(
                SELECT nik FROM hrd_harilibur_detail
                WHERE kode_libur = '$kode_libur'
            ) harilibur"),
            function ($join) {
                $join->on('hrd_karyawan.nik', '=', 'harilibur.nik');
            }
        );
        $query->join('hrd_group', 'hrd_karyawan.kode_group', '=', 'hrd_group.kode_group');
        $query->orderBy('hrd_karyawan.kode_group');
        $query->orderBy('nama_karyawan');
        $karyawan = $query->get();

        try {
            //Hapus Data Libur

            foreach ($karyawan as $d) {
                Detailharilibur::where('kode_libur', $request->kode_libur)->where('nik', $d->nik)->delete();
            }

            return response()->json(['success' => true, 'message' => 'Update Success']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function deletekaryawanlibur(Request $request)
    {
        try {
            Detailharilibur::where('nik', $request->nik)->where('kode_libur', $request->kode_libur)->delete();
            return response()->json(['success' => true, 'message' => 'Delete Success']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy($kode_libur)
    {
        $kode_libur = Crypt::decrypt($kode_libur);
        try {
            Harilibur::where('kode_libur', $kode_libur)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function approve($kode_libur)
    {
        $kode_libur = Crypt::decrypt($kode_libur);
        $lb = new Harilibur();
        $data['harilibur'] = $lb->getHarilibur(kode_libur: $kode_libur)->first();
        $data['detail'] = Detailharilibur::join('hrd_karyawan', 'hrd_harilibur_detail.nik', '=', 'hrd_karyawan.nik')
            ->join('hrd_group', 'hrd_karyawan.kode_group', '=', 'hrd_group.kode_group')
            ->where('kode_libur', $kode_libur)->get();
        return view('hrd.harilibur.approve', $data);
    }

    public function storeapprove($kode_libur, Request $request)
    {
        $kode_libur = Crypt::decrypt($kode_libur);
        try {
            Harilibur::where('kode_libur', $kode_libur)->update([
                'status' => 1
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Diapprove'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function cancel($kode_libur)
    {
        $kode_libur = Crypt::decrypt($kode_libur);
        try {
            Harilibur::where('kode_libur', $kode_libur)->update([
                'status' => 0
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Dibatalkan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
