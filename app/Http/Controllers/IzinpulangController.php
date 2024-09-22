<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Disposisiizinpulang;
use App\Models\Izinpulang;
use App\Models\Karyawan;
use App\Models\Presensi;
use App\Models\Presensiizinpulang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class IzinpulangController extends Controller
{

    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $i_pulang = new Izinpulang();
        $izinpulang = $i_pulang->getIzinpulang(request: $request)->paginate(15);
        $izinpulang->appends(request()->all());
        $data['izinpulang'] = $izinpulang;
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['roles_approve'] = config('hrd.roles_approve_presensi');
        $data['listApprove'] = listApprovepresensi(auth()->user()->kode_dept, auth()->user()->kode_cabang, $user->getRoleNames()->first());
        return view('hrd.pengajuanizin.izinpulang.index', $data);
    }

    public function create()
    {
        $k = new Karyawan();
        $data['karyawan'] = $k->getkaryawanpresensi()->get();
        return view('hrd.pengajuanizin.izinpulang.create', $data);
    }


    public function store(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $request->validate([
            'nik' => 'required',
            'tanggal' => 'required',
            'jam_pulang' => 'required',
            'keterangan' => 'required',
        ]);
        DB::beginTransaction();
        try {

            $lastizinpulang = Izinpulang::select('kode_izin_pulang')
                ->whereRaw('YEAR(tanggal)="' . date('Y', strtotime($request->tanggal)) . '"')
                ->whereRaw('MONTH(tanggal)="' . date('m', strtotime($request->tanggal)) . '"')
                ->orderBy("kode_izin_pulang", "desc")
                ->first();
            $last_kode_izin_pulang = $lastizinpulang != null ? $lastizinpulang->kode_izin_pulang : '';
            $kode_izin_pulang  = buatkode($last_kode_izin_pulang, "IP"  . date('ym', strtotime($request->tanggal)), 4);
            $k = new Karyawan();
            $karyawan = $k->getKaryawan($request->nik);
            Izinpulang::create([
                'kode_izin_pulang' => $kode_izin_pulang,
                'nik' => $request->nik,
                'kode_jabatan' => $karyawan->kode_jabatan,
                'kode_dept' => $karyawan->kode_dept,
                'kode_cabang' => $karyawan->kode_cabang,
                'tanggal' => $request->tanggal,
                'jam_pulang' => $request->tanggal . ' ' . $request->jam_pulang,
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
                $cek_user_approve = User::role($roles_approve[$index_role])->where('status', 1)->first();
            }

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
            $lastdisposisi = Disposisiizinpulang::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
                ->orderBy('kode_disposisi', 'desc')
                ->first();
            $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
            $format = "DPIP" . date('Ymd');
            $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);

            Disposisiizinpulang::create([
                'kode_disposisi' => $kode_disposisi,
                'kode_izin_pulang' => $kode_izin_pulang,
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

    public function edit($kode_izin_pulang)
    {
        $kode_izin_pulang = Crypt::decrypt($kode_izin_pulang);
        $data['izinpulang'] = Izinpulang::where('kode_izin_pulang', $kode_izin_pulang)->first();
        $k = new Karyawan();
        $data['karyawan'] = $k->getkaryawanpresensi()->get();
        return view('hrd.pengajuanizin.izinpulang.edit', $data);
    }


    public function update(Request $request, $kode_izin_pulang)
    {
        $kode_izin_pulang = Crypt::decrypt($kode_izin_pulang);
        $request->validate([
            'tanggal' => 'required',
            'jam_pulang' => 'required',
            'keterangan' => 'required',
        ]);
        DB::beginTransaction();
        try {

            Izinpulang::where('kode_izin_pulang', $kode_izin_pulang)->update([
                'tanggal' => $request->tanggal,
                'jam_pulang' => $request->tanggal . ' ' . $request->jam_pulang,
                'keterangan' => $request->keterangan,
            ]);

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_izin_pulang)
    {
        $kode_izin_pulang = Crypt::decrypt($kode_izin_pulang);
        try {
            Izinpulang::where('kode_izin_pulang', $kode_izin_pulang)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function approve($kode_izin_pulang)
    {
        $kode_izin_pulang = Crypt::decrypt($kode_izin_pulang);
        $user = User::find(auth()->user()->id);
        $i_pulang = new Izinpulang();
        $izinpulang = $i_pulang->getIzinpulang(kode_izin_pulang: $kode_izin_pulang)->first();
        $data['izinpulang'] = $izinpulang;

        $role = $user->getRoleNames()->first();
        $roles_approve = cekRoleapprovepresensi($izinpulang->kode_dept, $izinpulang->kode_cabang, $izinpulang->kategori_jabatan, $izinpulang->kode_jabatan);
        $end_role = end($roles_approve);
        if ($role != $end_role && in_array($role, $roles_approve)) {
            $cek_index = array_search($role, $roles_approve) + 1;
        } else {
            $cek_index = count($roles_approve) - 1;
        }

        $nextrole = $roles_approve[$cek_index];
        if ($nextrole == "regional sales manager") {
            $userrole = User::role($nextrole)
                ->where('kode_regional', $izinpulang->kode_regional)
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
                        ->where('kode_regional', $izinpulang->kode_regional)
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
        return view('hrd.pengajuanizin.izinpulang.approve', $data);
    }

    public function storeapprove($kode_izin_pulang, Request $request)
    {
        // dd(isset($_POST['direktur']));

        $kode_izin_pulang = Crypt::decrypt($kode_izin_pulang);
        $user = User::findorfail(auth()->user()->id);
        $i_pulang = new Izinpulang();
        $izinpulang = $i_pulang->getIzinpulang(kode_izin_pulang: $kode_izin_pulang)->first();
        $role = $user->getRoleNames()->first();
        $roles_approve = cekRoleapprovepresensi($izinpulang->kode_dept, $izinpulang->kode_cabang, $izinpulang->kategori_jabatan, $izinpulang->kode_jabatan);
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
            Disposisiizinpulang::where('kode_izin_pulang', $kode_izin_pulang)
                ->where('id_penerima', auth()->user()->id)
                ->update([
                    'status' => 1
                ]);





            if ($role == 'direktur') {
                Izinpulang::where('kode_izin_pulang', $kode_izin_pulang)->update([
                    'direktur' => 1
                ]);
            } else {
                //Insert Dispsosi ke Penerima
                $tanggal_hariini = date('Y-m-d');
                $lastdisposisi = Disposisiizinpulang::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
                    ->orderBy('kode_disposisi', 'desc')
                    ->first();
                $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
                $format = "DPIP" . date('Ymd');
                $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);

                if ($role == $end_role) {
                    Izinpulang::where('kode_izin_pulang', $kode_izin_pulang)
                        ->update([
                            'status' => 1
                        ]);

                    $cekpresensi = Presensi::where('nik', $izinpulang->nik)->where('tanggal', $izinpulang->tanggal)->first();
                    //dd($cekpresensi);
                    if ($cekpresensi != null) {
                        Presensiizinpulang::create([
                            'id_presensi' => $cekpresensi->id,
                            'kode_izin_pulang' => $kode_izin_pulang,
                        ]);

                        Presensi::where('id', $cekpresensi->id)->update([
                            'jam_out' => $izinpulang->jam_pulang
                        ]);
                    } else {
                        DB::rollBack();
                        return Redirect::back()->with(messageError('Karyawan Belum Melakukan Presesnsi Pada Tanggal Tersebut'));
                    }

                    //dd($request->direktur);
                    if (isset($request->direktur)) {
                        //dd('test');
                        $userrole = User::role('direktur')->where('status', 1)->first();
                        Disposisiizinpulang::create([
                            'kode_disposisi' => $kode_disposisi,
                            'kode_izin_pulang' => $kode_izin_pulang,
                            'id_pengirim' => auth()->user()->id,
                            'id_penerima' => $userrole->id,
                            'status' => 0,
                        ]);
                    }
                } else {

                    Disposisiizinpulang::create([
                        'kode_disposisi' => $kode_disposisi,
                        'kode_izin_pulang' => $kode_izin_pulang,
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

    public function cancel($kode_izin_pulang)
    {
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $kode_izin_pulang = Crypt::decrypt($kode_izin_pulang);
        $i_pulang = new Izinpulang();
        $izinpulang = $i_pulang->getIzinpulang(kode_izin_pulang: $kode_izin_pulang)->first();
        $role = $user->getRoleNames()->first();
        $roles_approve = cekRoleapprovepresensi($izinpulang->kode_dept, $izinpulang->kode_cabang, $izinpulang->kategori_jabatan, $izinpulang->kode_jabatan);
        $end_role = end($roles_approve);
        DB::beginTransaction();
        try {

            Disposisiizinpulang::where('kode_izin_pulang', $kode_izin_pulang)
                ->where('id_pengirim', auth()->user()->id)
                ->where('id_penerima', '!=', auth()->user()->id)
                ->delete();

            Disposisiizinpulang::where('kode_izin_pulang', $kode_izin_pulang)
                ->where('id_penerima', auth()->user()->id)
                ->update([
                    'status' => 0
                ]);
            if ($role == 'direktur') {
                Izinpulang::where('kode_izin_pulang', $kode_izin_pulang)
                    ->update([
                        'direktur' => 0
                    ]);
            } else {
                if ($role == $end_role) {
                    Izinpulang::where('kode_izin_pulang', $kode_izin_pulang)
                        ->update([
                            'status' => 0
                        ]);

                    Presensiizinpulang::where('kode_izin_pulang', $kode_izin_pulang)->delete();
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
