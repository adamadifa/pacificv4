<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Detailjadwalkerja;
use App\Models\Disposisiizinkoreksi;
use App\Models\Izinkoreksi;
use App\Models\Jadwalkerja;
use App\Models\Karyawan;
use App\Models\Presensi;
use App\Models\Presensiizinkoreksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class IzinkoreksiController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $i_koreksi = new Izinkoreksi();
        $izinkoreksi = $i_koreksi->getIzinkoreksi(request: $request)->paginate(15);
        $izinkoreksi->appends(request()->all());
        $data['izinkoreksi'] = $izinkoreksi;
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['roles_approve'] = config('hrd.roles_approve_presensi');
        $data['listApprove'] = listApprovepresensi(auth()->user()->kode_dept, auth()->user()->kode_cabang, $user->getRoleNames()->first());
        return view('hrd.pengajuanizin.izinkoreksi.index', $data);
    }


    public function create()
    {
        $k = new Karyawan();
        $data['karyawan'] = $k->getkaryawanpresensi()->get();

        return view('hrd.pengajuanizin.izinkoreksi.create', $data);
    }

    function getjadwalkerja($kode_jadwal = "")
    {
        $user = User::findorfail(auth()->user()->id);

        if ($user->hasRole(['super admin', 'asst. manager hrd', 'spv presensi'])) {
            $jadwalkerja = Jadwalkerja::orderBy('kode_jadwal')->get();
            echo "<option value=''>Pilih Jadwal</option>";
            foreach ($jadwalkerja as $j) {
                echo "<option " . ($j->kode_jadwal == $kode_jadwal ? " selected" : "") . " value='$j->kode_jadwal'>" . $j->nama_jadwal . " " . $j->kode_cabang . "</option>";
            }
        } else {
            $jadwalkerja = Jadwalkerja::where('kode_cabang', auth()->user()->kode_cabang)->orderBy('kode_jadwal')->get();
            echo "<option value=''>Pilih Jadwal</option>";
            foreach ($jadwalkerja as $j) {
                echo "<option" . ($j->kode_jadwal == $kode_jadwal ? " selected" : "") . " value='$j->kode_jadwal'>" . $j->nama_jadwal . " " . $j->kode_cabang . "</option>";
            }
        }
    }

    function getjamkerja($kode_jadwal, $kode_jam_kerja = "")
    {
        $jamkerja = Detailjadwalkerja::select('hrd_jadwalkerja_detail.kode_jam_kerja', 'jam_masuk', 'jam_pulang')
            ->join('hrd_jamkerja', 'hrd_jadwalkerja_detail.kode_jam_kerja', '=', 'hrd_jamkerja.kode_jam_kerja')
            ->where('kode_jadwal', $kode_jadwal)
            ->groupBy('hrd_jadwalkerja_detail.kode_jam_kerja', 'jam_masuk', 'jam_pulang')
            ->get();
        echo "<option value=''>Pilih Jam Kerja</option>";
        foreach ($jamkerja as $j) {
            echo "<option" . ($j->kode_jam_kerja == $kode_jam_kerja ? " selected" : "") . " value='$j->kode_jam_kerja'>" . $j->jam_masuk . " - " . $j->jam_pulang . "</option>";
        }
    }

    public function store(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $request->validate([
            'nik' => 'required',
            'tanggal' => 'required',
            'jam_masuk' => 'required',
            'jam_pulang' => 'required',
            'kode_jam_kerja' => 'required',
            'kode_jadwal' => 'required',
            'keterangan' => 'required',
        ]);
        //dd($request->tanggal);
        DB::beginTransaction();
        try {

            $lastizinkoreksi = Izinkoreksi::select('kode_izin_koreksi')
                ->whereRaw('YEAR(tanggal)="' . date('Y', strtotime($request->tanggal)) . '"')
                ->whereRaw('MONTH(tanggal)="' . date('m', strtotime($request->tanggal)) . '"')
                ->whereRaw('LEFT(kode_izin_koreksi, 4)!="IK70"')
                ->orderBy("kode_izin_koreksi", "desc")
                ->first();


            $last_kode_izin_koreksi = $lastizinkoreksi != null ? $lastizinkoreksi->kode_izin_koreksi : '';
            $kode_izin_koreksi  = buatkode($last_kode_izin_koreksi, "IK"  . date('ym', strtotime($request->tanggal)), 4);
            $k = new Karyawan();
            $karyawan = $k->getKaryawan($request->nik);

            $jam_masuk = $request->tanggal . " " . $request->jam_masuk;
            if ($request->kode_jam_kerja == 'JK08') {
                //Tanggal di tambah 1 hari
                $jam_pulang = date('Y-m-d', strtotime($request->tanggal . ' + 1 days')) . " " . $request->jam_pulang;
            } else {
                $jam_pulang = $request->tanggal . " " . $request->jam_pulang;
            }


            Izinkoreksi::create([
                'kode_izin_koreksi' => $kode_izin_koreksi,
                'nik' => $request->nik,
                'kode_jabatan' => $karyawan->kode_jabatan,
                'kode_dept' => $karyawan->kode_dept,
                'kode_cabang' => $karyawan->kode_cabang,
                'tanggal' => $request->tanggal,
                'jam_masuk' => $jam_masuk,
                'jam_pulang' => $jam_pulang,
                'kode_jadwal' => $request->kode_jadwal,
                'kode_jam_kerja' => $request->kode_jam_kerja,
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
            $lastdisposisi = Disposisiizinkoreksi::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
                ->orderBy('kode_disposisi', 'desc')
                ->first();
            $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
            $format = "DPIK" . date('Ymd');
            $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);

            Disposisiizinkoreksi::create([
                'kode_disposisi' => $kode_disposisi,
                'kode_izin_koreksi' => $kode_izin_koreksi,
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


    public function edit($kode_izin_koreksi)
    {
        $kode_izin_koreksi = Crypt::decrypt($kode_izin_koreksi);
        $data['izinkoreksi'] = Izinkoreksi::where('kode_izin_koreksi', $kode_izin_koreksi)->first();
        //dd($data['izinkoreksi']);
        $k = new Karyawan();
        $data['karyawan'] = $k->getkaryawanpresensi()->get();
        return view('hrd.pengajuanizin.izinkoreksi.edit', $data);
    }


    public function update(Request $request, $kode_izin_koreksi)
    {
        $kode_izin_koreksi = Crypt::decrypt($kode_izin_koreksi);
        $request->validate([
            'tanggal' => 'required',
            'jam_masuk' => 'required',
            'jam_pulang' => 'required',
            'kode_jadwal' => 'required',
            'kode_jam_kerja' => 'required',
            'keterangan' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $jam_masuk = $request->tanggal . " " . $request->jam_masuk;
            if ($request->kode_jam_kerja == 'JK08') {
                //Tanggal di tambah 1 hari
                $jam_pulang = date('Y-m-d', strtotime($request->tanggal . ' + 1 days')) . " " . $request->jam_pulang;
            } else {
                $jam_pulang = $request->tanggal . " " . $request->jam_pulang;
            }
            Izinkoreksi::where('kode_izin_koreksi', $kode_izin_koreksi)->update([
                'tanggal' => $request->tanggal,
                'jam_masuk' => $jam_masuk,
                'jam_pulang' => $jam_pulang,
                'kode_jadwal' => $request->kode_jadwal,
                'kode_jam_kerja' => $request->kode_jam_kerja,
                'keterangan' => $request->keterangan,
            ]);

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function approve($kode_izin_koreksi)
    {
        $kode_izin_koreksi = Crypt::decrypt($kode_izin_koreksi);
        $user = User::find(auth()->user()->id);
        $i_koreksi = new Izinkoreksi();
        $izinkoreksi = $i_koreksi->getIzinkoreksi(kode_izin_koreksi: $kode_izin_koreksi)->first();
        $data['izinkoreksi'] = $izinkoreksi;

        $role = $user->getRoleNames()->first();
        $roles_approve = cekRoleapprovepresensi($izinkoreksi->kode_dept, $izinkoreksi->kode_cabang, $izinkoreksi->kategori_jabatan, $izinkoreksi->kode_jabatan);
        $end_role = end($roles_approve);
        if ($role != $end_role && in_array($role, $roles_approve)) {
            $cek_index = array_search($role, $roles_approve) + 1;
        } else {
            $cek_index = count($roles_approve) - 1;
        }

        $nextrole = $roles_approve[$cek_index];
        if ($nextrole == "regional sales manager") {
            $userrole = User::role($nextrole)
                ->where('kode_regional', $izinkoreksi->kode_regional)
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
                        ->where('kode_regional', $izinkoreksi->kode_regional)
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
        return view('hrd.pengajuanizin.izinkoreksi.approve', $data);
    }


    public function storeapprove($kode_izin_koreksi, Request $request)
    {
        // dd(isset($_POST['direktur']));

        $kode_izin_koreksi = Crypt::decrypt($kode_izin_koreksi);
        $user = User::findorfail(auth()->user()->id);
        $i_koreksi = new Izinkoreksi();
        $izinkoreksi = $i_koreksi->getIzinkoreksi(kode_izin_koreksi: $kode_izin_koreksi)->first();
        $role = $user->getRoleNames()->first();
        $roles_approve = cekRoleapprovepresensi($izinkoreksi->kode_dept, $izinkoreksi->kode_cabang, $izinkoreksi->kategori_jabatan, $izinkoreksi->kode_jabatan);
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
            // Update Disposisi Pengirim

            // dd($kode_penilaian);
            Disposisiizinkoreksi::where('kode_izin_koreksi', $kode_izin_koreksi)
                ->where('id_penerima', auth()->user()->id)
                ->update([
                    'status' => 1
                ]);

            if ($role == 'direktur') {
                Izinkoreksi::where('kode_izin_koreksi', $kode_izin_koreksi)->update([
                    'direktur' => 1
                ]);
            } else {
                //Insert Disposisi ke Penerima
                $tanggal_hariini = date('Y-m-d');
                $lastdisposisi = Disposisiizinkoreksi::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
                    ->orderBy('kode_disposisi', 'desc')
                    ->first();
                $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
                $format = "DPIK" . date('Ymd');
                $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);

                if ($role == $end_role) {
                    Izinkoreksi::where('kode_izin_koreksi', $kode_izin_koreksi)
                        ->update([
                            'status' => 1
                        ]);
                    Presensiizinkoreksi::where('kode_izin_koreksi', $kode_izin_koreksi)->delete();
                    Presensi::where('nik', $izinkoreksi->nik)->where('tanggal', $izinkoreksi->tanggal)->delete();
                    //dd($cekpresensi);
                    $presensi = Presensi::create([
                        'nik' => $izinkoreksi->nik,
                        'tanggal' => $izinkoreksi->tanggal,
                        'jam_in' => $izinkoreksi->jam_masuk,
                        'jam_out' => $izinkoreksi->jam_pulang,
                        'kode_jadwal' => $izinkoreksi->kode_jadwal,
                        'kode_jam_kerja' => $izinkoreksi->kode_jam_kerja,
                        'status' => 'h'
                    ]);

                    Presensiizinkoreksi::create([
                        'id_presensi' => $presensi->id,
                        'kode_izin_koreksi' => $kode_izin_koreksi
                    ]);

                    //dd($request->direktur);
                    if (isset($request->direktur)) {
                        //dd('test');
                        $userrole = User::role('direktur')->where('status', 1)->first();
                        Disposisiizinkoreksi::create([
                            'kode_disposisi' => $kode_disposisi,
                            'kode_izin_koreksi' => $kode_izin_koreksi,
                            'id_pengirim' => auth()->user()->id,
                            'id_penerima' => $userrole->id,
                            'status' => 0,
                        ]);
                    }
                } else {

                    Disposisiizinkoreksi::create([
                        'kode_disposisi' => $kode_disposisi,
                        'kode_izin_koreksi' => $kode_izin_koreksi,
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


    public function cancel($kode_izin_koreksi)
    {
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $kode_izin_koreksi = Crypt::decrypt($kode_izin_koreksi);
        $i_koreksi = new Izinkoreksi();
        $izinkoreksi = $i_koreksi->getIzinkoreksi(kode_izin_koreksi: $kode_izin_koreksi)->first();
        $role = $user->getRoleNames()->first();
        $roles_approve = cekRoleapprovepresensi($izinkoreksi->kode_dept, $izinkoreksi->kode_cabang, $izinkoreksi->kategori_jabatan, $izinkoreksi->kode_jabatan);
        $end_role = end($roles_approve);
        DB::beginTransaction();
        try {

            Disposisiizinkoreksi::where('kode_izin_koreksi', $kode_izin_koreksi)
                ->where('id_pengirim', auth()->user()->id)
                ->where('id_penerima', '!=', auth()->user()->id)
                ->delete();

            Disposisiizinkoreksi::where('kode_izin_koreksi', $kode_izin_koreksi)
                ->where('id_penerima', auth()->user()->id)
                ->update([
                    'status' => 0
                ]);
            if ($role == 'direktur') {
                Izinkoreksi::where('kode_izin_koreksi', $kode_izin_koreksi)
                    ->update([
                        'direktur' => 0
                    ]);
            } else {
                if ($role == $end_role) {
                    Izinkoreksi::where('kode_izin_koreksi', $kode_izin_koreksi)
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


    public function getpresensi(Request $request)
    {
        $presensi = Presensi::where('nik', $request->nik)->where('tanggal', $request->tanggal)->first();
        $data = [
            'jam_in' => !empty($presensi->jam_in) ? date('H:i', strtotime($presensi->jam_in)) : '',
            'jam_out' => !empty($presensi->jam_out)  ? date('H:i', strtotime($presensi->jam_out)) : '',
        ];
        return response()->json($data);
    }


    public function destroy($kode_izin_koreksi)
    {
        $kode_izin_koreksi = Crypt::decrypt($kode_izin_koreksi);
        try {
            Izinkoreksi::where('kode_izin_koreksi', $kode_izin_koreksi)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
