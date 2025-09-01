<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\Detaillembur;
use App\Models\Disposisilembur;
use App\Models\Karyawan;
use App\Models\Lembur;
use App\Models\Cabang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class LemburController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $lb = new Lembur();
        $lembur = $lb->getLembur(request: $request)->paginate(15);
        $lembur->appends(request()->all());
        $data['lembur'] = $lembur;
        $dept_lembur = config('hrd.dept_lembur');
        $data['departemen'] = Departemen::whereIn('kode_dept', $dept_lembur)->orderBy('kode_dept')->get();
        $data['listApprovepenilaian'] = listApprovepenilaian(auth()->user()->kode_dept, $user->getRoleNames()->first());
        return view('hrd.lembur.index', $data);
    }


    public function create()
    {
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getcabang();
        return view('hrd.lembur.create', $data);
    }


    public function store(Request $request)
    {
        $user = User::findorFail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $request->validate([
            'tanggal' => 'required|date',
            'dari' => 'required|date',
            'sampai' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'istirahat' => 'required',
            'keterangan' => 'required',
            'kategori' => 'required',
        ]);
        $validationRules = [];
        if (in_array($role, ['super admin', 'asst. manager hrd', 'spv presensi'])) {
            $validationRules['kode_dept'] = 'required';
            $validationRules['kode_cabang'] = 'required';
        }
        $request->validate($validationRules);

        $kode_dept = in_array($role, ['super admin', 'asst. manager hrd', 'spv presensi']) ? $request->kode_dept : $user->kode_dept;
        $kode_cabang = in_array($role, ['super admin', 'asst. manager hrd', 'spv presensi']) ? $request->kode_cabang : $user->kode_cabang;
        DB::beginTransaction();
        try {
            $lastlembur = Lembur::whereRaw('MID(kode_lembur,3,2)="' . date('y', strtotime($request->tanggal)) . '"')
                ->orderBy('kode_lembur', 'desc')->first();
            $last_kode_lembur = $lastlembur != null ? $lastlembur->kode_lembur : '';
            $kode_lembur = buatkode($last_kode_lembur, "LM" . date('y', strtotime($request->tanggal)), 3);
            $mulai = $request->dari . " " . $request->jam_mulai;
            $selesai = $request->sampai . " " . $request->jam_selesai;
            $mulai = date('Y-m-d H:i:s', strtotime($mulai));
            $selesai = date('Y-m-d H:i:s', strtotime($selesai));
            Lembur::create([
                'kode_lembur' => $kode_lembur,
                'kode_cabang' => $kode_cabang,
                'kode_dept' => $kode_dept,
                'tanggal' => $request->tanggal,
                'tanggal_dari' => $mulai,
                'tanggal_sampai' => $selesai,
                'kategori' => $request->kategori,
                'istirahat' => $request->istirahat,
                'status' => 0,
                'keterangan' => $request->keterangan,
            ]);

            $roles_approve = cekRoleapprovelembur($kode_dept);

            if (in_array($role, $roles_approve)) {
                $index_role = array_search($role, $roles_approve);
            } else {
                $index_role = 0;
            }
            // Jika Tidak Ada di dalam array

            $cek_user_approve = User::role($roles_approve[$index_role])->where('status', 1)->first();



            if ($cek_user_approve == null) {
                for ($i = $index_role + 1; $i < count($roles_approve); $i++) {
                    $cek_user_approve = User::role($roles_approve[$i])
                        ->where('status', 1)
                        ->first();
                    if ($cek_user_approve != null) {
                        break;
                    }
                }
            }


            $tanggal_hariini = date('Y-m-d');
            $lastdisposisi = Disposisilembur::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
                ->orderBy('kode_disposisi', 'desc')
                ->first();
            $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
            $format = "DPLB" . date('Ymd');
            $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);


            Disposisilembur::create([
                'kode_disposisi' => $kode_disposisi,
                'kode_lembur' => $kode_lembur,
                'id_pengirim' => auth()->user()->id,
                'id_penerima' => $cek_user_approve->id,
                'status' => 0
            ]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($kode_lembur)
    {
        $kode_lembur = Crypt::decrypt($kode_lembur);
        $data['lembur'] = Lembur::where('kode_lembur', $kode_lembur)->first();
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        return view('hrd.lembur.edit', $data);
    }


    public function update($kode_lembur, Request $request)
    {
        $kode_lembur = Crypt::decrypt($kode_lembur);
        $user = User::findorFail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $request->validate([
            'tanggal' => 'required',
            'dari' => 'required',
            'sampai' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'istirahat' => 'required',
            'keterangan' => 'required',
            'kategori' => 'required',
        ]);

        $validationRules = [];
        if (in_array($role, ['super admin', 'asst. manager hrd', 'spv presensi'])) {
            $validationRules['kode_dept'] = 'required';
        }
        $request->validate($validationRules);

        $kode_dept = in_array($role, ['super admin', 'asst. manager hrd', 'spv presensi']) ? $request->kode_dept : $user->kode_dept;
        $kode_cabang = $user->kode_cabang;

        try {

            $mulai = $request->dari . " " . $request->jam_mulai;
            $selesai = $request->sampai . " " . $request->jam_selesai;
            $mulai = date('Y-m-d H:i:s', strtotime($mulai));
            $selesai = date('Y-m-d H:i:s', strtotime($selesai));
            Lembur::where('kode_lembur', $kode_lembur)->update([
                'kode_cabang' => $kode_cabang,
                'kode_dept' => $kode_dept,
                'tanggal' => $request->tanggal,
                'tanggal_dari' => $mulai,
                'tanggal_sampai' => $selesai,
                'kategori' => $request->kategori,
                'istirahat' => $request->istirahat,
                'status' => 0,
                'keterangan' => $request->keterangan,
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function aturlembur($kode_lembur)
    {
        $kode_lembur = Crypt::decrypt($kode_lembur);
        $lb = new Lembur();
        $data['lembur'] = $lb->getLembur($kode_lembur)->first();
        return view('hrd.lembur.aturlembur', $data);
    }


    function getkaryawanlembur($kode_lembur)
    {
        $kode_lembur = Crypt::decrypt($kode_lembur);
        $data['detail'] = Detaillembur::join('hrd_karyawan', 'hrd_lembur_detail.nik', '=', 'hrd_karyawan.nik')
            ->join('hrd_lembur', 'hrd_lembur_detail.kode_lembur', '=', 'hrd_lembur.kode_lembur')
            ->join('hrd_group', 'hrd_karyawan.kode_group', '=', 'hrd_group.kode_group')
            ->where('hrd_lembur_detail.kode_lembur', $kode_lembur)->get();
        return view('hrd.lembur.getkaryawanlembur', $data);
    }

    public function aturkaryawan($kode_lembur)
    {
        $kode_lembur = Crypt::decrypt($kode_lembur);
        $lb = new Lembur();
        $lembur = $lb->getLembur(kode_lembur: $kode_lembur)->first();
        $data['lembur'] = $lembur;
        $data['group'] = Karyawan::where('hrd_karyawan.kode_dept', $lembur->kode_dept)
            ->where('kode_cabang', $lembur->kode_cabang)
            ->where('status_aktif_karyawan', 1)
            ->select('hrd_karyawan.kode_group', 'nama_group')
            ->join('hrd_group', 'hrd_karyawan.kode_group', '=', 'hrd_group.kode_group')
            ->orderBy('hrd_karyawan.kode_group')
            ->groupBy('hrd_karyawan.kode_group', 'nama_group')->get();


        return view('hrd.lembur.aturkaryawan', $data);
    }

    function getkaryawan(Request $request)
    {
        $kode_lembur = Crypt::decrypt($request->kode_lembur);
        $lb = new Lembur();
        $lembur = $lb->getLembur(kode_lembur: $kode_lembur)->first();
        $data['lembr'] = $lembur;
        $query = Karyawan::query();
        $query->select('hrd_karyawan.nik', 'hrd_karyawan.nama_karyawan', 'hrd_karyawan.kode_group', 'hrd_group.nama_group', 'lembur.nik as ceklembur');
        // if ($harilibur->kode_cabang != 'PST') {
        //     $query->where('hrd_karyawan.kode_cabang', $harilibur->kode_cabang);
        // } else {
        //     $query->where('hrd_karyawan.kode_dept', $harilibur->kode_dept);
        // }
        $query->where('hrd_karyawan.kode_dept', $lembur->kode_dept);
        $query->where('hrd_karyawan.kode_cabang', $lembur->kode_cabang);
        $query->where('hrd_karyawan.status_aktif_karyawan', 1);
        if (!empty($request->kode_group)) {
            $query->where('hrd_karyawan.kode_group', $request->kode_group);
        }

        if (!empty($request->nama_karyawan)) {
            $query->where('nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
        }

        //left join ke detail hari libur berdasarkan kode libur
        $query->leftJoin(
            DB::raw("(
                SELECT nik FROM hrd_lembur_detail
                WHERE kode_lembur = '$kode_lembur'
            ) lembur"),
            function ($join) {
                $join->on('hrd_karyawan.nik', '=', 'lembur.nik');
            }
        );
        $query->join('hrd_group', 'hrd_karyawan.kode_group', '=', 'hrd_group.kode_group');
        $query->orderBy('hrd_karyawan.kode_group');
        $query->orderBy('nama_karyawan');
        $data['karyawan'] = $query->get();
        return view('hrd.lembur.getkaryawan', $data);
    }

    public function updatelemburkaryawan(Request $request)
    {
        try {
            $cek = Detaillembur::where('nik', $request->nik)->where('kode_lembur', $request->kode_lembur)->first();
            if ($cek != null) {
                Detaillembur::where('nik', $request->nik)->where('kode_lembur', $request->kode_lembur)->delete();
            } else {
                Detaillembur::create([
                    'nik' => $request->nik,
                    'kode_lembur' => $request->kode_lembur,
                ]);
            }
            return response()->json(['success' => true, 'message' => 'Update Success']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function tambahkansemua(Request $request)
    {
        $kode_lembur = $request->kode_lembur;
        $lb = new Lembur();
        $lembur = $lb->getLembur(kode_lembur: $kode_lembur)->first();
        $data['lembur'] = $lembur;
        $query = Karyawan::query();
        $query->select('hrd_karyawan.nik', 'hrd_karyawan.nama_karyawan', 'hrd_karyawan.kode_group', 'hrd_group.nama_group', 'lembur.nik as ceklembur');
        // if ($harilibur->kode_cabang != 'PST') {
        //     $query->where('hrd_karyawan.kode_cabang', $harilibur->kode_cabang);
        // } else {
        //     $query->where('hrd_karyawan.kode_dept', $harilibur->kode_dept);
        // }

        $query->where('hrd_karyawan.kode_dept', $lembur->kode_dept);

        if (!empty($request->kode_group)) {
            $query->where('hrd_karyawan.kode_group', $request->kode_group);
        }

        if (!empty($request->nama_karyawan)) {
            $query->where('nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
        }
        //left join ke detail hari libur berdasarkan kode libur
        $query->leftJoin(
            DB::raw("(
                SELECT nik FROM hrd_lembur_detail
                WHERE kode_lembur = '$kode_lembur'
            ) lembur"),
            function ($join) {
                $join->on('hrd_karyawan.nik', '=', 'lembur.nik');
            }
        );
        $query->join('hrd_group', 'hrd_karyawan.kode_group', '=', 'hrd_group.kode_group');
        $query->orderBy('hrd_karyawan.kode_group');
        $query->orderBy('nama_karyawan');
        $karyawan = $query->get();

        try {
            //Hapus Data Libur
            Detaillembur::where('kode_lembur', $request->kode_lembur)->delete();
            foreach ($karyawan as $d) {
                Detaillembur::create([
                    'nik' => $d->nik,
                    'kode_lembur' => $request->kode_lembur,
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Update Success']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function batalkansemua(Request $request)
    {
        $kode_lembur = $request->kode_lembur;
        $lb = new Lembur();
        $lembur = $lb->getLembur(kode_lembur: $kode_lembur)->first();
        $data['lembur'] = $lembur;
        $query = Karyawan::query();
        $query->select('hrd_karyawan.nik', 'hrd_karyawan.nama_karyawan', 'hrd_karyawan.kode_group', 'hrd_group.nama_group', 'lembur.nik as ceklembur');
        if ($lembur->kode_cabang != 'PST') {
            $query->where('hrd_karyawan.kode_cabang', $lembur->kode_cabang);
        } else {
            $query->where('hrd_karyawan.kode_dept', $lembur->kode_dept);
        }

        if (!empty($request->kode_group)) {
            $query->where('hrd_karyawan.kode_group', $request->kode_group);
        }

        if (!empty($request->nama_karyawan)) {
            $query->where('nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
        }
        //left join ke detail lembur berdasarkan kode lembur
        $query->leftJoin(
            DB::raw("(
                SELECT nik FROM hrd_lembur_detail
                WHERE kode_lembur = '$kode_lembur'
            ) lembur"),
            function ($join) {
                $join->on('hrd_karyawan.nik', '=', 'lembur.nik');
            }
        );
        $query->join('hrd_group', 'hrd_karyawan.kode_group', '=', 'hrd_group.kode_group');
        $query->orderBy('hrd_karyawan.kode_group');
        $query->orderBy('nama_karyawan');
        $karyawan = $query->get();

        try {
            //Hapus Data Lembur

            foreach ($karyawan as $d) {
                Detaillembur::where('kode_lembur', $request->kode_lembur)->where('nik', $d->nik)->delete();
            }

            return response()->json(['success' => true, 'message' => 'Update Success']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deletekaryawanlembur(Request $request)
    {
        try {
            Detaillembur::where('nik', $request->nik)->where('kode_lembur', $request->kode_lembur)->delete();
            return response()->json(['success' => true, 'message' => 'Delete Success']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function approve($kode_lembur)
    {
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $kode_lembur = Crypt::decrypt($kode_lembur);
        $lb = new Lembur();
        $lembur = $lb->getLembur($kode_lembur)->first();
        $data['lembur'] = $lembur;
        $data['detail'] = Detaillembur::join('hrd_karyawan', 'hrd_lembur_detail.nik', '=', 'hrd_karyawan.nik')
            ->join('hrd_group', 'hrd_karyawan.kode_group', '=', 'hrd_group.kode_group')
            ->where('hrd_lembur_detail.kode_lembur', $kode_lembur)->get();


        $roles_approve = cekRoleapprovelembur($lembur->kode_dept);
        $end_role = end($roles_approve);
        if ($role != $end_role) {
            $cek_index = array_search($role, $roles_approve) + 1;
        } else {
            $cek_index = count($roles_approve) - 1;
        }

        $nextrole = $roles_approve[$cek_index];
        $userrole = User::role($nextrole)
            ->where('status', 1)
            ->first();

        $index_start = $cek_index + 1;
        if ($userrole == null) {
            for ($i = $index_start; $i < count($roles_approve); $i++) {
                $userrole = User::role($roles_approve[$i])
                    ->where('status', 1)
                    ->first();
                if ($userrole != null) {
                    $nextrole = $roles_approve[$i];
                    break;
                }
            }
        }

        $data['nextrole'] = $nextrole;
        $data['userrole'] = $userrole;
        $data['end_role'] = $end_role;
        return view('hrd.lembur.approve', $data);
    }


    public function storeapprove($kode_lembur, Request $request)
    {
        $kode_lembur = Crypt::decrypt($kode_lembur);
        $user = User::findorfail(auth()->user()->id);
        $lb = new Lembur();
        $lembur = $lb->getLembur($kode_lembur)->first();
        $role = $user->getRoleNames()->first();
        $roles_approve = cekRoleapprovelembur($lembur->kode_dept);
        $end_role = end($roles_approve);

        if ($role != $end_role) {
            $cek_index = array_search($role, $roles_approve);
            $nextrole = $roles_approve[$cek_index + 1];
            $userrole = User::role($nextrole)
                ->where('status', 1)
                ->first();
        }

        //dd($userrole);

        DB::beginTransaction();
        try {
            // Upadate Disposisi Pengirim

            // dd($kode_penilaian);
            Disposisilembur::where('kode_lembur', $kode_lembur)
                ->where('id_penerima', auth()->user()->id)
                ->update([
                    'status' => 1
                ]);

            //Insert Dispsosi ke Penerima
            $tanggal_hariini = date('Y-m-d');
            $lastdisposisi = Disposisilembur::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
                ->orderBy('kode_disposisi', 'desc')
                ->first();
            $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
            $format = "DPLB" . date('Ymd');
            $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);




            if ($role == $end_role) {
                Lembur::where('kode_lembur', $kode_lembur)
                    ->update([
                        'status' => 1
                    ]);
            } else {

                Disposisilembur::create([
                    'kode_disposisi' => $kode_disposisi,
                    'kode_lembur' => $kode_lembur,
                    'id_pengirim' => auth()->user()->id,
                    'id_penerima' => $userrole->id,
                    'status' => 0,
                ]);
            }


            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disetujui'));
        } catch (\Exception $e) {
            DB::rollBack();
            //dd($e);
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function cancel($kode_lembur)
    {
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $kode_lembur = Crypt::decrypt($kode_lembur);
        try {
            Disposisilembur::where('kode_lembur', $kode_lembur)
                ->where('id_pengirim', auth()->user()->id)
                ->where('id_penerima', '!=', auth()->user()->id)
                ->delete();

            Disposisilembur::where('kode_lembur', $kode_lembur)
                ->where('id_penerima', auth()->user()->id)
                ->update([
                    'status' => 0
                ]);

            if ($role == 'direktur') {
                Lembur::where('kode_lembur', $kode_lembur)
                    ->update([
                        'status' => 0
                    ]);
            }
            return Redirect::back()->with(messageSuccess('Data Berhasil Dibatalkan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
            //throw $th;
        }
    }
    public function destroy($kode_lembur)
    {
        $kode_lembur = Crypt::decrypt($kode_lembur);
        try {
            $lembur = Lembur::where('kode_lembur', $kode_lembur);
            if (!$lembur) {
                return Redirect::back()->with(messageError('Data tidak ditemukan'));
            }
            $lembur->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
