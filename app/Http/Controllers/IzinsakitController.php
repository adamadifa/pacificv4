<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Detailjadwalkerja;
use App\Models\Detailjadwalshift;
use App\Models\Disposisiizinsakit;
use App\Models\Izinpulang;
use App\Models\Izinsakit;
use App\Models\Karyawan;
use App\Models\Presensi;
use App\Models\Presensiizinpulang;
use App\Models\Presensiizinsakit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class IzinsakitController extends Controller
{

    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $i_sakit = new Izinsakit();
        $izinsakit = $i_sakit->getIzinsakit(request: $request)->paginate(15);
        $izinsakit->appends(request()->all());
        $data['izinsakit'] = $izinsakit;
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['roles_approve'] = config('hrd.roles_approve_presensi');
        $data['listApprove'] = listApprovepresensi(auth()->user()->kode_dept, auth()->user()->kode_cabang, $user->getRoleNames()->first());
        return view('hrd.pengajuanizin.izinsakit.index', $data);
    }

    public function create()
    {
        $k = new Karyawan();
        $data['karyawan'] = $k->getkaryawanpresensi()->get();
        return view('hrd.pengajuanizin.izinsakit.create', $data);
    }

    public function store(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $request->validate([
            'nik' => 'required',
            'dari' => 'required',
            'sampai' => 'required',
            'keterangan' => 'required',
        ]);
        DB::beginTransaction();
        try {

            $lastizinsakit = Izinsakit::select('kode_izin_sakit')
                ->whereRaw('YEAR(tanggal)="' . date('Y', strtotime($request->dari)) . '"')
                ->whereRaw('MONTH(tanggal)="' . date('m', strtotime($request->dari)) . '"')
                ->orderBy("kode_izin_sakit", "desc")
                ->first();
            $last_kode_izin_sakit = $lastizinsakit != null ? $lastizinsakit->kode_izin_sakit : '';
            $kode_izin_sakit  = buatkode($last_kode_izin_sakit, "IS"  . date('ym', strtotime($request->dari)), 4);
            $k = new Karyawan();
            $karyawan = $k->getKaryawan($request->nik);

            $data_sid = [];
            if ($request->hasfile('sid')) {
                $sid_name =  $kode_izin_sakit . "." . $request->file('sid')->getClientOriginalExtension();
                $destination_sid_path = "/public/uploads/sid";
                $sid = $sid_name;
                $data_sid = [
                    'doc_sid' => $sid,
                ];
            }

            $dataizinsakit = [
                'kode_izin_sakit' => $kode_izin_sakit,
                'nik' => $request->nik,
                'kode_jabatan' => $karyawan->kode_jabatan,
                'kode_dept' => $karyawan->kode_dept,
                'kode_cabang' => $karyawan->kode_cabang,
                'tanggal' => $request->dari,
                'dari' => $request->dari,
                'sampai' => $request->sampai,
                'keterangan' => $request->keterangan,
                'status' => 0,
                'direktur' => 0,
                'id_user' => $user->id,
            ];

            $data = array_merge($dataizinsakit, $data_sid);
            $simpandatasakit = Izinsakit::create($data);
            if ($simpandatasakit) {
                if ($request->hasfile('sid')) {
                    $request->file('sid')->storeAs($destination_sid_path, $sid_name);
                }
            }

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
                    // $cek_user_approve = User::role($roles_approve[$i])
                    //     ->where('status', 1)
                    //     ->first();
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
            $lastdisposisi = Disposisiizinsakit::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
                ->orderBy('kode_disposisi', 'desc')
                ->first();
            $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
            $format = "DPIS" . date('Ymd');
            $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);

            Disposisiizinsakit::create([
                'kode_disposisi' => $kode_disposisi,
                'kode_izin_sakit' => $kode_izin_sakit,
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

    public function edit($kode_izin_sakit)
    {
        $kode_izin_sakit = Crypt::decrypt($kode_izin_sakit);
        $data['izinsakit'] = Izinsakit::where('kode_izin_sakit', $kode_izin_sakit)->first();
        $k = new Karyawan();
        $data['karyawan'] = $k->getkaryawanpresensi()->get();
        return view('hrd.pengajuanizin.izinsakit.edit', $data);
    }

    public function update(Request $request, $kode_izin_sakit)
    {
        $kode_izin_sakit = Crypt::decrypt($kode_izin_sakit);

        $request->validate([
            'dari' => 'required',
            'sampai' => 'required',
            'keterangan' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $izinsakit = Izinsakit::where('kode_izin_sakit', $kode_izin_sakit)->first();
            $data_sid = [];
            if ($request->hasfile('sid')) {
                $sid_name =  $kode_izin_sakit . "." . $request->file('sid')->getClientOriginalExtension();
                $destination_sid_path = "/public/uploads/sid";
                $sid = $sid_name;
                $data_sid = [
                    'doc_sid' => $sid,
                ];
            }

            $dataizinsakit = [
                'tanggal' => $request->dari,
                'dari' => $request->dari,
                'sampai' => $request->sampai,
                'keterangan' => $request->keterangan,

            ];

            $data = array_merge($dataizinsakit, $data_sid);

            $simpandatasakit = Izinsakit::where('kode_izin_sakit', $kode_izin_sakit)->update($data);
            if ($simpandatasakit) {
                if ($request->hasfile('sid')) {
                    Storage::delete($destination_sid_path . "/" . $izinsakit->doc_sid);
                    $request->file('sid')->storeAs($destination_sid_path, $sid_name);
                }
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function approve($kode_izin_sakit)
    {
        $kode_izin_sakit = Crypt::decrypt($kode_izin_sakit);
        $user = User::find(auth()->user()->id);
        $i_sakit = new Izinsakit();
        $izinsakit = $i_sakit->getIzinsakit(kode_izin_sakit: $kode_izin_sakit)->first();
        $data['izinsakit'] = $izinsakit;

        $role = $user->getRoleNames()->first();
        $roles_approve = cekRoleapprovepresensi($izinsakit->kode_dept, $izinsakit->kode_cabang, $izinsakit->kategori_jabatan, $izinsakit->kode_jabatan);
        $end_role = end($roles_approve);
        if ($role != $end_role && in_array($role, $roles_approve)) {
            $cek_index = array_search($role, $roles_approve) + 1;
        } else {
            $cek_index = count($roles_approve) - 1;
        }

        $nextrole = $roles_approve[$cek_index];
        if ($nextrole == "regional sales manager") {
            $userrole = User::role($nextrole)
                ->where('kode_regional', $izinsakit->kode_regional)
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
                        ->where('kode_regional', $izinsakit->kode_regional)
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
        return view('hrd.pengajuanizin.izinsakit.approve', $data);
    }


    public function storeapprove($kode_izin_sakit, Request $request)
    {
        // dd(isset($_POST['direktur']));

        $kode_izin_sakit = Crypt::decrypt($kode_izin_sakit);
        $user = User::findorfail(auth()->user()->id);
        $i_sakit = new Izinsakit();
        $izinsakit = $i_sakit->getIzinsakit(kode_izin_sakit: $kode_izin_sakit)->first();
        $role = $user->getRoleNames()->first();
        $roles_approve = cekRoleapprovepresensi($izinsakit->kode_dept, $izinsakit->kode_cabang, $izinsakit->kategori_jabatan, $izinsakit->kode_jabatan);
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
            Disposisiizinsakit::where('kode_izin_sakit', $kode_izin_sakit)
                ->where('id_penerima', auth()->user()->id)
                ->update([
                    'status' => 1
                ]);





            if ($role == 'direktur') {
                Izinsakit::where('kode_izin_sakit', $kode_izin_sakit)->update([
                    'direktur' => 1
                ]);
            } else {
                //Insert Dispsosi ke Penerima
                $tanggal_hariini = date('Y-m-d');
                $lastdisposisi = Disposisiizinsakit::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
                    ->orderBy('kode_disposisi', 'desc')
                    ->first();
                $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
                $format = "DPIS" . date('Ymd');
                $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);

                if ($role == $end_role) {
                    Izinsakit::where('kode_izin_sakit', $kode_izin_sakit)
                        ->update([
                            'status' => 1
                        ]);

                    $dari = $izinsakit->dari;
                    $sampai = $izinsakit->sampai;

                    while (strtotime($dari) <= strtotime($sampai)) {
                        //Cek Jadwal Shift
                        $cekjadwalshift = Detailjadwalshift::join('hrd_jadwalshift', 'hrd_jadwalshift.kode_jadwalshift', 'hrd_jadwalshift_detail.kode_jadwalshift')
                            ->whereRaw($dari . ' between dari and sampai')
                            ->where('nik', $izinsakit->nik)
                            ->first();
                        if ($cekjadwalshift != null) {
                            $kode_jadwal = $cekjadwalshift->kode_jadwal;
                        } else {
                            $cekjadwalkaryawan = Karyawan::where('nik', $izinsakit->nik)->first();
                            $kode_jadwal =  $cekjadwalkaryawan->kode_jadwal;
                        }

                        $nama_hari = getNamahari($dari);




                        $cekjamkerja = Detailjadwalkerja::where('kode_jadwal', $kode_jadwal)->where('hari', $nama_hari)->first();


                        if ($cekjamkerja != null) {
                            $kode_jam_kerja = $cekjamkerja->kode_jam_kerja;
                        } else {
                            DB::rollback();
                            return Redirect::back()->with(messageError('Karyawan Belum Diatur Jam Kerja'));
                        }

                        //Hapus Jika Sudah Ada Data Presensi
                        $cekizinpulang = Izinpulang::where('nik', $izinsakit->nik)->where('tanggal', $dari)->first();
                        if ($cekizinpulang != null) {
                            Presensiizinpulang::where('kode_izin_pulang', $cekizinpulang->kode_izin_pulang)->delete();
                            Izinpulang::where('kode_izin_pulang', $cekizinpulang->kode_izin_pulang)->delete();
                        }
                        Presensi::where('nik', $izinsakit->nik)->where('tanggal', $dari)->delete();
                        $presensi = Presensi::create([
                            'nik' => $izinsakit->nik,
                            'tanggal' => $dari,
                            'kode_jadwal' => $kode_jadwal,
                            'kode_jam_kerja' => $kode_jam_kerja,
                            'status' => 's',
                        ]);

                        Presensiizinsakit::create([
                            'id_presensi' => $presensi->id,
                            'kode_izin_sakit' => $kode_izin_sakit,
                        ]);
                        $dari = date('Y-m-d', strtotime($dari . ' +1 day'));
                    }

                    //dd($request->direktur);
                    if (isset($request->direktur)) {
                        //dd('test');
                        $userrole = User::role('direktur')->where('status', 1)->first();
                        Disposisiizinsakit::create([
                            'kode_disposisi' => $kode_disposisi,
                            'kode_izin_sakit' => $kode_izin_sakit,
                            'id_pengirim' => auth()->user()->id,
                            'id_penerima' => $userrole->id,
                            'status' => 0,
                        ]);
                    }
                } else {

                    Disposisiizinsakit::create([
                        'kode_disposisi' => $kode_disposisi,
                        'kode_izin_sakit' => $kode_izin_sakit,
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


    public function cancel($kode_izin_sakit)
    {
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $kode_izin_sakit = Crypt::decrypt($kode_izin_sakit);
        $i_sakit = new Izinsakit();
        $izinsakit = $i_sakit->getIzinsakit(kode_izin_sakit: $kode_izin_sakit)->first();
        $role = $user->getRoleNames()->first();
        $roles_approve = cekRoleapprovepresensi($izinsakit->kode_dept, $izinsakit->kode_cabang, $izinsakit->kategori_jabatan, $izinsakit->kode_jabatan);
        $end_role = end($roles_approve);
        DB::beginTransaction();
        try {

            Disposisiizinsakit::where('kode_izin_sakit', $kode_izin_sakit)
                ->where('id_pengirim', auth()->user()->id)
                ->where('id_penerima', '!=', auth()->user()->id)
                ->delete();

            Disposisiizinsakit::where('kode_izin_sakit', $kode_izin_sakit)
                ->where('id_penerima', auth()->user()->id)
                ->update([
                    'status' => 0
                ]);
            if ($role == 'direktur') {
                Izinsakit::where('kode_izin_sakit', $kode_izin_sakit)
                    ->update([
                        'direktur' => 0
                    ]);
            } else {
                if ($role == $end_role) {
                    Izinsakit::where('kode_izin_sakit', $kode_izin_sakit)
                        ->update([
                            'status' => 0
                        ]);

                    $presensi_izinabsen = Presensiizinsakit::select('id_presensi')->where('kode_izin_sakit', $kode_izin_sakit);
                    $presensi = $presensi_izinabsen->get();
                    $id_presensi = [];
                    foreach ($presensi as $d) {
                        $id_presensi[] = $d->id_presensi;
                    }
                    $presensi_izinabsen->delete();

                    Presensi::whereIn('id', $id_presensi)->delete();
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

    public function destroy($kode_izin_sakit)
    {
        $kode_izin_sakit = Crypt::decrypt($kode_izin_sakit);
        try {
            Izinsakit::where('kode_izin_sakit', $kode_izin_sakit)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
