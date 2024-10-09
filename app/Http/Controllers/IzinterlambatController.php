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
        $data['roles_approve'] = config('hrd.roles_approve_presensi');
        $data['listApprove'] = listApprovepresensi(auth()->user()->kode_dept, auth()->user()->kode_cabang, $user->getRoleNames()->first());
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
                'id_user' => $user->id,
            ]);


            $roles_approve = cekRoleapprovepresensi($karyawan->kode_dept, $karyawan->kode_cabang, $karyawan->kategori, $karyawan->kode_jabatan);

            if (in_array($role, $roles_approve)) {
                $index_role = array_search($role, $roles_approve);
            } else {
                $index_role = 0;
            }

            if (in_array($roles_approve[$index_role], ['operation manager', 'sales marketing manager'])) {
                $cek_user_approve = User::role($roles_approve[$index_role])->where('status', 1)
                    ->where('kode_cabang', $karyawan->kode_cabang)
                    ->first();
            } else {
                if ($roles_approve[$index_role] == 'regional sales manager') {
                    $cek_user_approve = User::role($roles_approve[$index_role])
                        ->where('kode_regional', $karyawan->kode_regional)
                        ->where('status', 1)
                        ->first();
                } else {
                    $cek_user_approve = User::role($roles_approve[$index_role])->where('status', 1)->first();
                }
            }

            if ($cek_user_approve == null) {
                for ($i = $index_role + 1; $i < count($roles_approve); $i++) {
                    if ($roles_approve[$i] == 'regional sales manager') {
                        $cek_user_approve = User::role($roles_approve[$index_role])
                            ->where('kode_regional', $karyawan->kode_regional)
                            ->where('status', 1)
                            ->first();
                    } else {
                        $cek_user_approve = User::role($roles_approve[$index_role])->where('status', 1)->first();
                    }
                    if ($cek_user_approve != null) {
                        break;
                    }
                }
            }

            $tanggal_hariini = date('Y-m-d');
            $lastdisposisi = Disposisiizinterlambat::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
                ->orderBy('kode_disposisi', 'desc')
                ->first();
            $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
            $format = "DPIT" . date('Ymd');
            $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);

            Disposisiizinterlambat::create([
                'kode_disposisi' => $kode_disposisi,
                'kode_izin_terlambat' => $kode_izin_terlambat,
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
        $roles_approve = cekRoleapprovepresensi($izinterlambat->kode_dept, $izinterlambat->kode_cabang, $izinterlambat->kategori_jabatan, $izinterlambat->kode_jabatan);
        $end_role = end($roles_approve);

        if ($role != $end_role && in_array($role, $roles_approve)) {
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
            Disposisiizinterlambat::where('kode_izin_terlambat', $kode_izin_terlambat)
                ->where('id_penerima', auth()->user()->id)
                ->update([
                    'status' => 1
                ]);





            if ($role == 'direktur') {
                Izinterlambat::where('kode_izin_terlambat', $kode_izin_terlambat)->update([
                    'direktur' => 1
                ]);
            } else {
                //Insert Dispsosi ke Penerima
                $tanggal_hariini = date('Y-m-d');
                $lastdisposisi = Disposisiizinterlambat::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
                    ->orderBy('kode_disposisi', 'desc')
                    ->first();
                $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
                $format = "DPIT" . date('Ymd');
                $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);

                if ($role == $end_role) {
                    Izinterlambat::where('kode_izin_terlambat', $kode_izin_terlambat)
                        ->update([
                            'status' => 1
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

                    //dd($request->direktur);
                    if (isset($request->direktur)) {
                        //dd('test');
                        $userrole = User::role('direktur')->where('status', 1)->first();
                        Disposisiizinterlambat::create([
                            'kode_disposisi' => $kode_disposisi,
                            'kode_izin_terlambat' => $kode_izin_terlambat,
                            'id_pengirim' => auth()->user()->id,
                            'id_penerima' => $userrole->id,
                            'status' => 0,
                        ]);
                    }
                } else {

                    Disposisiizinterlambat::create([
                        'kode_disposisi' => $kode_disposisi,
                        'kode_izin_terlambat' => $kode_izin_terlambat,
                        'id_pengirim' => auth()->user()->id,
                        'id_penerima' => $userrole->id,
                        'status' => 0,
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
        $roles_approve = cekRoleapprovepresensi($izinterlambat->kode_dept, $izinterlambat->kode_cabang, $izinterlambat->kategori_jabatan, $izinterlambat->kode_jabatan);
        $end_role = end($roles_approve);
        DB::beginTransaction();
        try {

            Disposisiizinterlambat::where('kode_izin_terlambat', $kode_izin_terlambat)
                ->where('id_pengirim', auth()->user()->id)
                ->where('id_penerima', '!=', auth()->user()->id)
                ->delete();

            Disposisiizinterlambat::where('kode_izin_terlambat', $kode_izin_terlambat)
                ->where('id_penerima', auth()->user()->id)
                ->update([
                    'status' => 0
                ]);
            if ($role == 'direktur') {
                Izinterlambat::where('kode_izin_terlambat', $kode_izin_terlambat)
                    ->update([
                        'direktur' => 0
                    ]);
            } else {
                if ($role == $end_role) {
                    Izinterlambat::where('kode_izin_terlambat', $kode_izin_terlambat)
                        ->update([
                            'status' => 0
                        ]);

                    Presensiizinterlambat::where('kode_izin_terlambat', $kode_izin_terlambat)->delete();
                }
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
