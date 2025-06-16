<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Disposisiizinterlambat;
use App\Models\Izinterlambat;
use App\Models\Karyawan;
use App\Models\Presensi;
use App\Models\Presensiizinterlambat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class IzinterlambatController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $i_terlambat = new Izinterlambat();
        $izinterlambat = $i_terlambat->getIzinterlambat(request: $request)->paginate(15);
        $izinterlambat->appends(request()->all());
        $data['izinterlambat'] = $izinterlambat;
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['roles_can_approve'] = config('presensi.approval');
        $data['level_hrd'] = config('presensi.approval.level_hrd');
        return view('hrd.pengajuanizin.izinterlambat.index', $data);
    }

    public function create()
    {
        $k = new Karyawan();
        $data['karyawan'] = $k->getkaryawanpresensi()->get();
        return view('hrd.pengajuanizin.izinterlambat.create', $data);
    }

    public function store(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $request->validate([
            'nik' => 'required',
            'tanggal' => 'required',
            'jam_terlambat' => 'required',
            'keterangan' => 'required',
        ]);
        DB::beginTransaction();
        try {

            $lastizinterlambat = Izinterlambat::select('kode_izin_terlambat')
                ->whereRaw('YEAR(tanggal)="' . date('Y', strtotime($request->tanggal)) . '"')
                ->whereRaw('MONTH(tanggal)="' . date('m', strtotime($request->tanggal)) . '"')
                ->orderBy("kode_izin_terlambat", "desc")
                ->first();
            $last_kode_izin_terlambat = $lastizinterlambat != null ? $lastizinterlambat->kode_izin_terlambat : '';
            $kode_izin_terlambat  = buatkode($last_kode_izin_terlambat, "IT"  . date('ym', strtotime($request->tanggal)), 4);
            $k = new Karyawan();
            $karyawan = $k->getKaryawan($request->nik);
            $head = $karyawan->kode_dept == 'HRD' && $karyawan->kode_jabatan=='J12' || $karyawan->kode_jabatan=='J02' ? '1' : '0';
            Izinterlambat::create([
                'kode_izin_terlambat' => $kode_izin_terlambat,
                'nik' => $request->nik,
                'kode_jabatan' => $karyawan->kode_jabatan,
                'kode_dept' => $karyawan->kode_dept,
                'kode_cabang' => $karyawan->kode_cabang,
                'tanggal' => $request->tanggal,
                'jam_terlambat' => $request->tanggal . ' ' . $request->jam_terlambat,
                'keterangan' => $request->keterangan,
                'status' => 0,
                'direktur' => 0,
                'head' => $head,
                'id_user' => $user->id,
            ]);



            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($kode_izin_terlambat)
    {
        $kode_izin_terlambat = Crypt::decrypt($kode_izin_terlambat);
        $data['izinterlambat'] = Izinterlambat::where('kode_izin_terlambat', $kode_izin_terlambat)->first();
        $k = new Karyawan();
        $data['karyawan'] = $k->getkaryawanpresensi()->get();
        return view('hrd.pengajuanizin.izinterlambat.edit', $data);
    }

    public function update(Request $request, $kode_izin_terlambat)
    {
        $kode_izin_terlambat = Crypt::decrypt($kode_izin_terlambat);
        // dd($kode_izin_terlambat);
        $request->validate([
            'tanggal' => 'required',
            'jam_terlambat' => 'required',
            'keterangan' => 'required',
        ]);
        DB::beginTransaction();
        try {

            Izinterlambat::where('kode_izin_terlambat', $kode_izin_terlambat)->update([
                'tanggal' => $request->tanggal,
                'jam_terlambat' => $request->tanggal . ' ' . $request->jam_terlambat,
                'keterangan' => $request->keterangan,
            ]);

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_izin_terlambat)
    {
        $kode_izin_terlambat = Crypt::decrypt($kode_izin_terlambat);
        try {
            Izinterlambat::where('kode_izin_terlambat', $kode_izin_terlambat)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function approve($kode_izin_terlambat)
    {
        $kode_izin_terlambat = Crypt::decrypt($kode_izin_terlambat);

        $user = User::find(auth()->user()->id);
        $i_terlambat = new Izinterlambat();

        $izinterlambat = $i_terlambat->getIzinterlambat(kode_izin_terlambat: $kode_izin_terlambat)->first();

        $data['izinterlambat'] = $izinterlambat;
        $level_hrd = ['asst. manager hrd', 'spv presensi'];
        $role = $user->getRoleNames()->first();
        $data['level_hrd'] = $level_hrd;
        $data['role'] = $role;
        return view('hrd.pengajuanizin.izinterlambat.approve', $data);
    }


    public function show($kode_izin_terlambat)
    {
        $kode_izin_terlambat = Crypt::decrypt($kode_izin_terlambat);
        $user = User::find(auth()->user()->id);
        $i_terlambat = new Izinterlambat();
        $izinterlambat = $i_terlambat->getIzinterlambat(kode_izin_terlambat: $kode_izin_terlambat)->first();
        $data['izinterlambat'] = $izinterlambat;

        $role = $user->getRoleNames()->first();
        $roles_approve = cekRoleapprovepresensi($izinterlambat->kode_dept, $izinterlambat->kode_cabang, $izinterlambat->kategori_jabatan, $izinterlambat->kode_jabatan);
        $end_role = end($roles_approve);
        if ($role != $end_role && in_array($role, $roles_approve)) {
            $cek_index = array_search($role, $roles_approve) + 1;
        } else {
            $cek_index = count($roles_approve) - 1;
        }

        $nextrole = $roles_approve[$cek_index];
        if ($nextrole == "regional sales manager") {
            $userrole = User::role($nextrole)
                ->where('kode_regional', $izinterlambat->kode_regional)
                ->where('status', 1)
                ->first();
        } else {
            $userrole = User::role($nextrole)
                ->where('status', 1)
                ->first();
        }

        $index_start = $cek_index + 1;
        if ($userrole == null) {
            for ($i = $index_start; $i < count($roles_approve); $i++) {
                if ($roles_approve[$i] == 'regional sales manager') {
                    $userrole = User::role($roles_approve[$i])
                        ->where('kode_regional', $izinterlambat->kode_regional)
                        ->where('status', 1)
                        ->first();
                } else {
                    $userrole = User::role($roles_approve[$i])
                        ->where('status', 1)
                        ->first();
                }

                if ($userrole != null) {
                    $nextrole = $roles_approve[$i];
                    break;
                }
            }
        }

        $data['nextrole'] = $nextrole;
        $data['userrole'] = $userrole;
        $data['end_role'] = $end_role;
        return view('hrd.pengajuanizin.izinterlambat.show', $data);
    }


    public function storeapprove($kode_izin_terlambat, Request $request)
    {
        // dd(isset($_POST['direktur']));

        $kode_izin_terlambat = Crypt::decrypt($kode_izin_terlambat);
        $user = User::findorfail(auth()->user()->id);
        $i_terlambat = new Izinterlambat();
        $izinterlambat = $i_terlambat->getIzinterlambat(kode_izin_terlambat: $kode_izin_terlambat)->first();
        $role = $user->getRoleNames()->first();
        $level_hrd = config('presensi.approval.level_hrd');

        DB::beginTransaction();
        try {

            if ($role != 'direktur') {
                if (!in_array($role, $level_hrd)) {
                    Izinterlambat::where('kode_izin_terlambat', $kode_izin_terlambat)->update([
                        'head' => 1,
                    ]);
                } else {
                    $forward_to_direktur = isset($request->direktur) ? 1 : 0;
                    Izinterlambat::where('kode_izin_terlambat', $kode_izin_terlambat)
                        ->update([
                            'hrd' => 1,
                            'status' => 1,
                            'forward_to_direktur' => $forward_to_direktur
                        ]);

                    $cekpresensi = Presensi::where('nik', $izinterlambat->nik)->where('tanggal', $izinterlambat->tanggal)->first();
                    //dd($cekpresensi);
                    if ($cekpresensi != null) {
                        Presensiizinterlambat::create([
                            'id_presensi' => $cekpresensi->id,
                            'kode_izin_terlambat' => $kode_izin_terlambat,
                        ]);

                        Presensi::where('nik', $izinterlambat->nik)->where('tanggal', $izinterlambat->tanggal)->update([
                            'jam_in' => $izinterlambat->jam_terlambat
                        ]);
                    } else {
                        DB::rollBack();
                        return Redirect::back()->with(messageError('Karyawan Belum Melakukan Presesnsi Pada Tanggal Tersebut'));
                    }


                    if (isset($request->forward_to_direktur)) {
                        Izinterlambat::where('kode_izin_terlambat', $kode_izin_terlambat)
                            ->update([
                                'forward_to_direktur' => 1
                            ]);
                    }
                }
            } else {
                if ($izinterlambat->forward_to_direktur == 1) {
                    Izinterlambat::where('kode_izin_terlambat', $kode_izin_terlambat)
                        ->update([
                            'direktur' => 1
                        ]);
                } else {
                    Izinterlambat::where('kode_izin_terlambat', $kode_izin_terlambat)
                        ->update([
                            'head' => 1,
                            'direktur' => 1
                        ]);
                }
            }



            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disetujui'));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function cancel($kode_izin_terlambat)
    {
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $kode_izin_terlambat = Crypt::decrypt($kode_izin_terlambat);
        $i_terlambat = new Izinterlambat();
        $izinterlambat = $i_terlambat->getIzinterlambat(kode_izin_terlambat: $kode_izin_terlambat)->first();
        $role = $user->getRoleNames()->first();
        $level_hrd = config('presensi.approval.level_hrd');
        DB::beginTransaction();
        try {

            if ($role != 'direktur') {
                if (in_array($role, $level_hrd)) {

                    Izinterlambat::where('kode_izin_terlambat', $kode_izin_terlambat)
                        ->update([
                            'status' => 0,
                            'hrd' => 0,
                            'forward_to_direktur' => 0

                        ]);
                    Presensiizinterlambat::where('kode_izin_terlambat', $kode_izin_terlambat)->delete();
                } else {
                    Izinterlambat::where('kode_izin_terlambat', $kode_izin_terlambat)->update([
                        'head' => 0
                    ]);
                }
            } else {
                Izinterlambat::where('kode_izin_terlambat', $kode_izin_terlambat)->update([
                    'direktur' => 0
                ]);
            }

            

            DB::commit();
           
            return Redirect::back()->with(messageSuccess('Data Berhasil Dibatalkan'));
        } catch (\Exception $e) {
            //dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
            //throw $th;
        }
    }
}
