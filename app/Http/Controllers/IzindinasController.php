<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Disposisiizindinas;
use App\Models\Izindinas;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class IzindinasController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $i_dinas = new Izindinas();
        $izindinas = $i_dinas->getIzindinas(request: $request)->paginate(15);
        $izindinas->appends(request()->all());
        $data['izindinas'] = $izindinas;
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['roles_approve'] = config('hrd.roles_approve_presensi');
        $data['listApprove'] = listApprovepresensi(auth()->user()->kode_dept, auth()->user()->kode_cabang, $user->getRoleNames()->first());
        return view('hrd.pengajuanizin.izindinas.index', $data);
    }


    public function create()
    {
        $k = new Karyawan();
        $data['karyawan'] = $k->getkaryawanpresensi()->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        return view('hrd.pengajuanizin.izindinas.create', $data);
    }


    public function store(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $request->validate([
            'nik' => 'required',
            'dari' => 'required',
            'sampai' => 'required',
            'kode_cabang_tujuan' => 'required',
            'keterangan' => 'required',
        ]);
        DB::beginTransaction();
        try {

            $lastizindinas = Izindinas::select('kode_izin_dinas')
                ->whereRaw('YEAR(tanggal)="' . date('Y', strtotime($request->dari)) . '"')
                ->whereRaw('MONTH(tanggal)="' . date('m', strtotime($request->dari)) . '"')
                ->orderBy("kode_izin_dinas", "desc")
                ->first();
            $last_kode_izin_dinas = $lastizindinas != null ? $lastizindinas->kode_izin_dinas : '';
            $kode_izin_dinas  = buatkode($last_kode_izin_dinas, "ID"  . date('ym', strtotime($request->dari)), 4);
            $k = new Karyawan();
            $karyawan = $k->getKaryawan($request->nik);


            $dataizindinas = [
                'kode_izin_dinas' => $kode_izin_dinas,
                'nik' => $request->nik,
                'kode_jabatan' => $karyawan->kode_jabatan,
                'kode_dept' => $karyawan->kode_dept,
                'kode_cabang' => $karyawan->kode_cabang,
                'tanggal' => $request->dari,
                'dari' => $request->dari,
                'sampai' => $request->sampai,
                'keterangan' => $request->keterangan,
                'kode_cabang_tujuan' => $request->kode_cabang_tujuan,
                'status' => 0,
                'direktur' => 0,
                'id_user' => $user->id,
            ];


            Izindinas::create($dataizindinas);


            $roles_approve = cekRoleapprovepresensi($karyawan->kode_dept, $karyawan->kode_cabang, $karyawan->kategori, $karyawan->kode_jabatan);

            if (in_array($role, $roles_approve)) {
                $index_role = array_search($role, $roles_approve);
            } else {
                $index_role = 0;
            }

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
            $lastdisposisi = Disposisiizindinas::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
                ->orderBy('kode_disposisi', 'desc')
                ->first();
            $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
            $format = "DPID" . date('Ymd');
            $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);

            Disposisiizindinas::create([
                'kode_disposisi' => $kode_disposisi,
                'kode_izin_dinas' => $kode_izin_dinas,
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

    public function edit($kode_izin_dinas)
    {
        $kode_izin_dinas = Crypt::decrypt($kode_izin_dinas);
        $data['izindinas'] = Izindinas::where('kode_izin_dinas', $kode_izin_dinas)->first();
        $k = new Karyawan();
        $data['karyawan'] = $k->getkaryawanpresensi()->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        return view('hrd.pengajuanizin.izindinas.edit', $data);
    }


    public function update(Request $request, $kode_izin_dinas)
    {
        $kode_izin_dinas = Crypt::decrypt($kode_izin_dinas);

        $request->validate([
            'dari' => 'required',
            'sampai' => 'required',
            'keterangan' => 'required',
            'kode_cabang_tujuan' => 'required',
        ]);
        DB::beginTransaction();
        try {

            $dataizindinas = [
                'tanggal' => $request->dari,
                'dari' => $request->dari,
                'sampai' => $request->sampai,
                'keterangan' => $request->keterangan,
                'kode_cabang_tujuan' => $request->kode_cabang_tujuan,

            ];



            Izindinas::where('kode_izin_dinas', $kode_izin_dinas)->update($dataizindinas);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function destroy($kode_izin_dinas)
    {
        $kode_izin_dinas = Crypt::decrypt($kode_izin_dinas);
        try {
            Izindinas::where('kode_izin_dinas', $kode_izin_dinas)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function approve($kode_izin_dinas)
    {
        $kode_izin_dinas = Crypt::decrypt($kode_izin_dinas);
        $user = User::find(auth()->user()->id);
        $i_dinas = new Izindinas();
        $izindinas = $i_dinas->getIzindinas(kode_izin_dinas: $kode_izin_dinas)->first();
        $data['izindinas'] = $izindinas;

        $role = $user->getRoleNames()->first();
        $roles_approve = cekRoleapprovepresensi($izindinas->kode_dept, $izindinas->kode_cabang, $izindinas->kategori_jabatan, $izindinas->kode_jabatan);
        $end_role = end($roles_approve);
        if ($role != $end_role && in_array($role, $roles_approve)) {
            $cek_index = array_search($role, $roles_approve) + 1;
        } else {
            $cek_index = count($roles_approve) - 1;
        }

        $nextrole = $roles_approve[$cek_index];
        if ($nextrole == "regional sales manager") {
            $userrole = User::role($nextrole)
                ->where('kode_regional', $izindinas->kode_regional)
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
                        ->where('kode_regional', $izindinas->kode_regional)
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
        return view('hrd.pengajuanizin.izindinas.approve', $data);
    }

    public function storeapprove($kode_izin_dinas, Request $request)
    {
        // dd(isset($_POST['direktur']));

        $kode_izin_dinas = Crypt::decrypt($kode_izin_dinas);
        $user = User::findorfail(auth()->user()->id);
        $i_dinas = new Izindinas();
        $izindinas = $i_dinas->getIzindinas(kode_izin_dinas: $kode_izin_dinas)->first();
        $role = $user->getRoleNames()->first();
        $roles_approve = cekRoleapprovepresensi($izindinas->kode_dept, $izindinas->kode_cabang, $izindinas->kategori_jabatan, $izindinas->kode_jabatan);
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
            Disposisiizindinas::where('kode_izin_dinas', $kode_izin_dinas)
                ->where('id_penerima', auth()->user()->id)
                ->update([
                    'status' => 1
                ]);





            if ($role == 'direktur') {
                Izindinas::where('kode_izin_dinas', $kode_izin_dinas)->update([
                    'direktur' => 1
                ]);
            } else {
                //Insert Dispsosi ke Penerima
                $tanggal_hariini = date('Y-m-d');
                $lastdisposisi = Disposisiizindinas::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
                    ->orderBy('kode_disposisi', 'desc')
                    ->first();
                $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
                $format = "DPID" . date('Ymd');
                $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);

                if ($role == $end_role) {
                    Izindinas::where('kode_izin_dinas', $kode_izin_dinas)
                        ->update([
                            'status' => 1
                        ]);
                    //dd($request->direktur);
                    if (isset($request->direktur)) {
                        //dd('test');
                        $userrole = User::role('direktur')->where('status', 1)->first();
                        Disposisiizindinas::create([
                            'kode_disposisi' => $kode_disposisi,
                            'kode_izin_dinas' => $kode_izin_dinas,
                            'id_pengirim' => auth()->user()->id,
                            'id_penerima' => $userrole->id,
                            'status' => 0,
                        ]);
                    }
                } else {

                    Disposisiizindinas::create([
                        'kode_disposisi' => $kode_disposisi,
                        'kode_izin_dinas' => $kode_izin_dinas,
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


    public function cancel($kode_izin_dinas)
    {
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $kode_izin_dinas = Crypt::decrypt($kode_izin_dinas);
        $i_dinas = new Izindinas();
        $izindinas = $i_dinas->getIzindinas(kode_izin_dinas: $kode_izin_dinas)->first();
        $role = $user->getRoleNames()->first();
        $roles_approve = cekRoleapprovepresensi($izindinas->kode_dept, $izindinas->kode_cabang, $izindinas->kategori_jabatan, $izindinas->kode_jabatan);
        $end_role = end($roles_approve);
        DB::beginTransaction();
        try {

            Disposisiizindinas::where('kode_izin_dinas', $kode_izin_dinas)
                ->where('id_pengirim', auth()->user()->id)
                ->where('id_penerima', '!=', auth()->user()->id)
                ->delete();

            Disposisiizindinas::where('kode_izin_dinas', $kode_izin_dinas)
                ->where('id_penerima', auth()->user()->id)
                ->update([
                    'status' => 0
                ]);
            if ($role == 'direktur') {
                Izindinas::where('kode_izin_dinas', $kode_izin_dinas)
                    ->update([
                        'direktur' => 0
                    ]);
            } else {
                if ($role == $end_role) {
                    Izindinas::where('kode_izin_dinas', $kode_izin_dinas)
                        ->update([
                            'status' => 0
                        ]);
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
