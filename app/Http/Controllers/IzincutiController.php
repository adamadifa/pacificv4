<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Detailjadwalkerja;
use App\Models\Detailjadwalshift;
use App\Models\Disposisiizincuti;
use App\Models\Izincuti;
use App\Models\Jeniscuti;
use App\Models\Jeniscutikhusus;
use App\Models\Karyawan;
use App\Models\Presensi;
use App\Models\Presensiizincuti;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class IzincutiController extends Controller
{


    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $i_cuti = new Izincuti();
        $izincuti = $i_cuti->getIzincuti(request: $request)->paginate(15);
        $izincuti->appends(request()->all());
        $data['izincuti'] = $izincuti;
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['roles_approve'] = config('hrd.roles_approve_presensi');
        $data['listApprove'] = listApprovepresensi(auth()->user()->kode_dept, auth()->user()->kode_cabang, $user->getRoleNames()->first());
        return view('hrd.pengajuanizin.izincuti.index', $data);
    }

    public function create()
    {
        $k = new Karyawan();
        $data['karyawan'] = $k->getkaryawanpresensi()->get();
        $data['jenis_cuti'] = Jeniscuti::orderBy('kode_cuti')->get();
        $data['jenis_cuti_khusus'] = Jeniscutikhusus::orderBy('kode_cuti_khusus')->get();
        return view('hrd.pengajuanizin.izincuti.create', $data);
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
            'kode_cuti' => 'required',
        ]);
        DB::beginTransaction();
        try {

            $lastizincuti = Izincuti::select('kode_izin_cuti')
                ->whereRaw('YEAR(tanggal)="' . date('Y', strtotime($request->dari)) . '"')
                ->whereRaw('MONTH(tanggal)="' . date('m', strtotime($request->dari)) . '"')
                ->orderBy("kode_izin_cuti", "desc")
                ->first();
            $last_kode_izin_cuti = $lastizincuti != null ? $lastizincuti->kode_izin_cuti : '';
            $kode_izin_cuti  = buatkode($last_kode_izin_cuti, "IC"  . date('ym', strtotime($request->dari)), 4);
            $k = new Karyawan();
            $karyawan = $k->getKaryawan($request->nik);

            $data_cuti = [];
            if ($request->hasfile('doc_cuti')) {
                $cuti_name =  $kode_izin_cuti . "." . $request->file('doc_cuti')->getClientOriginalExtension();
                $destination_cuti_path = "/public/uploads/cuti";
                $cuti = $cuti_name;
                $data_cuti = [
                    'doc_cuti' => $cuti,
                ];
            }

            $dataizincuti = [
                'kode_izin_cuti' => $kode_izin_cuti,
                'nik' => $request->nik,
                'kode_jabatan' => $karyawan->kode_jabatan,
                'kode_dept' => $karyawan->kode_dept,
                'kode_cabang' => $karyawan->kode_cabang,
                'tanggal' => $request->dari,
                'dari' => $request->dari,
                'sampai' => $request->sampai,
                'kode_cuti' => $request->kode_cuti,
                'kode_cuti_khusus' => $request->kode_cuti == 'C03' ? $request->kode_cuti_khusus : null,
                'keterangan' => $request->keterangan,
                'status' => 0,
                'direktur' => 0,
                'id_user' => $user->id,
            ];

            $data = array_merge($dataizincuti, $data_cuti);
            $simpandatacuti = Izincuti::create($data);
            if ($simpandatacuti) {
                if ($request->hasfile('doc_cuti')) {
                    $request->file('doc_cuti')->storeAs($destination_cuti_path, $cuti_name);
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
            $lastdisposisi = Disposisiizincuti::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
                ->orderBy('kode_disposisi', 'desc')
                ->first();
            $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
            $format = "DPIC" . date('Ymd');
            $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);

            Disposisiizincuti::create([
                'kode_disposisi' => $kode_disposisi,
                'kode_izin_cuti' => $kode_izin_cuti,
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


    public function destroy($kode_izin_cuti)
    {
        $kode_izin_cuti = Crypt::decrypt($kode_izin_cuti);
        try {
            Izincuti::where('kode_izin_cuti', $kode_izin_cuti)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($kode_izin_cuti)
    {
        $kode_izin_cuti = Crypt::decrypt($kode_izin_cuti);
        $data['izincuti'] = Izincuti::where('kode_izin_cuti', $kode_izin_cuti)->first();
        $k = new Karyawan();
        $data['karyawan'] = $k->getkaryawanpresensi()->get();
        $data['jenis_cuti'] = Jeniscuti::orderBy('kode_cuti')->get();
        $data['jenis_cuti_khusus'] = Jeniscutikhusus::orderBy('kode_cuti_khusus')->get();
        return view('hrd.pengajuanizin.izincuti.edit', $data);
    }

    public function update(Request $request, $kode_izin_cuti)
    {
        $kode_izin_cuti = Crypt::decrypt($kode_izin_cuti);

        $request->validate([
            'dari' => 'required',
            'sampai' => 'required',
            'keterangan' => 'required',
            'kode_cuti' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $izincuti = Izincuti::where('kode_izin_cuti', $kode_izin_cuti)->first();
            $data_cuti = [];
            if ($request->hasfile('doc_cuti')) {
                $cuti_name =  $kode_izin_cuti . "." . $request->file('doc_cuti')->getClientOriginalExtension();
                $destination_cuti_path = "/public/uploads/cuti";
                $cuti = $cuti_name;
                $data_cuti = [
                    'doc_cuti' => $cuti,
                ];
            }

            $dataizincuti = [
                'tanggal' => $request->dari,
                'dari' => $request->dari,
                'sampai' => $request->sampai,
                'keterangan' => $request->keterangan,
                'kode_cuti' => $request->kode_cuti,
                'kode_cuti_khusus' => $request->kode_cuti == 'C03' ? $request->kode_cuti_khusus : null,

            ];

            $data = array_merge($dataizincuti, $data_cuti);

            $simpandatacuti = Izincuti::where('kode_izin_cuti', $kode_izin_cuti)->update($data);
            if ($simpandatacuti) {
                if ($request->hasfile('doc_cuti')) {
                    Storage::delete($destination_cuti_path . "/" . $izincuti->doc_cuti);
                    $request->file('doc_cuti')->storeAs($destination_cuti_path, $cuti_name);
                }
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function approve($kode_izin_cuti)
    {
        $kode_izin_cuti = Crypt::decrypt($kode_izin_cuti);

        $user = User::find(auth()->user()->id);
        $i_cuti = new Izincuti();
        $izincuti = $i_cuti->getIzincuti(kode_izin_cuti: $kode_izin_cuti)->first();
        // $izincuti = DB::table('hrd_izincuti')
        //     ->select('hrd_izincuti.*', 'nama_karyawan', 'nama_jabatan', 'hrd_jabatan.kategori as kategori_jabatan')
        //     ->join('hrd_karyawan', 'hrd_izincuti.nik', '=', 'hrd_karyawan.nik')
        //     ->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan')
        //     ->join('hrd_departemen', 'hrd_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept')
        //     ->join('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang')
        //     ->where('kode_izin_cuti', $kode_izin_cuti)->first();

        // dd($datacuti);
        $data['izincuti'] = $izincuti;

        $role = $user->getRoleNames()->first();
        $roles_approve = cekRoleapprovepresensi($izincuti->kode_dept, $izincuti->kode_cabang, $izincuti->kategori_jabatan, $izincuti->kode_jabatan);
        $end_role = end($roles_approve);
        if ($role != $end_role && in_array($role, $roles_approve)) {
            $cek_index = array_search($role, $roles_approve) + 1;
        } else {
            $cek_index = count($roles_approve) - 1;
        }

        $nextrole = $roles_approve[$cek_index];
        if ($nextrole == "regional sales manager") {
            $userrole = User::role($nextrole)
                ->where('kode_regional', $izincuti->kode_regional)
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
                        ->where('kode_regional', $izincuti->kode_regional)
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
        return view('hrd.pengajuanizin.izincuti.approve', $data);
    }


    public function show($kode_izin_cuti)
    {
        $kode_izin_cuti = Crypt::decrypt($kode_izin_cuti);

        $user = User::find(auth()->user()->id);
        $i_cuti = new Izincuti();
        $izincuti = $i_cuti->getIzincuti(kode_izin_cuti: $kode_izin_cuti)->first();
        // $izincuti = DB::table('hrd_izincuti')
        //     ->select('hrd_izincuti.*', 'nama_karyawan', 'nama_jabatan', 'hrd_jabatan.kategori as kategori_jabatan')
        //     ->join('hrd_karyawan', 'hrd_izincuti.nik', '=', 'hrd_karyawan.nik')
        //     ->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan')
        //     ->join('hrd_departemen', 'hrd_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept')
        //     ->join('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang')
        //     ->where('kode_izin_cuti', $kode_izin_cuti)->first();

        // dd($datacuti);
        $data['izincuti'] = $izincuti;

        $role = $user->getRoleNames()->first();
        $roles_approve = cekRoleapprovepresensi($izincuti->kode_dept, $izincuti->kode_cabang, $izincuti->kategori_jabatan, $izincuti->kode_jabatan);
        $end_role = end($roles_approve);
        if ($role != $end_role && in_array($role, $roles_approve)) {
            $cek_index = array_search($role, $roles_approve) + 1;
        } else {
            $cek_index = count($roles_approve) - 1;
        }

        $nextrole = $roles_approve[$cek_index];
        if ($nextrole == "regional sales manager") {
            $userrole = User::role($nextrole)
                ->where('kode_regional', $izincuti->kode_regional)
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
                        ->where('kode_regional', $izincuti->kode_regional)
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
        return view('hrd.pengajuanizin.izincuti.approve', $data);
    }

    public function storeapprove($kode_izin_cuti, Request $request)
    {
        // dd(isset($_POST['direktur']));

        $kode_izin_cuti = Crypt::decrypt($kode_izin_cuti);
        $user = User::findorfail(auth()->user()->id);
        $i_cuti = new Izincuti();
        $izincuti = $i_cuti->getIzincuti(kode_izin_cuti: $kode_izin_cuti)->first();
        $role = $user->getRoleNames()->first();
        $roles_approve = cekRoleapprovepresensi($izincuti->kode_dept, $izincuti->kode_cabang, $izincuti->kategori_jabatan, $izincuti->kode_jabatan);
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
            Disposisiizincuti::where('kode_izin_cuti', $kode_izin_cuti)
                ->where('id_penerima', auth()->user()->id)
                ->update([
                    'status' => 1
                ]);





            if ($role == 'direktur') {
                Izincuti::where('kode_izin_cuti', $kode_izin_cuti)->update([
                    'direktur' => 1
                ]);
            } else {
                //Insert Dispsosi ke Penerima
                $tanggal_hariini = date('Y-m-d');
                $lastdisposisi = Disposisiizincuti::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
                    ->orderBy('kode_disposisi', 'desc')
                    ->first();
                $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
                $format = "DPIC" . date('Ymd');
                $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);

                if ($role == $end_role) {
                    Izincuti::where('kode_izin_cuti', $kode_izin_cuti)
                        ->update([
                            'status' => 1
                        ]);

                    $dari = $izincuti->dari;
                    $sampai = $izincuti->sampai;

                    while (strtotime($dari) <= strtotime($sampai)) {
                        //Cek Jadwal Shift
                        $cekjadwalshift = Detailjadwalshift::join('hrd_jadwalshift', 'hrd_jadwalshift.kode_jadwalshift', 'hrd_jadwalshift_detail.kode_jadwalshift')
                            ->whereRaw($dari . ' between dari and sampai')
                            ->where('nik', $izincuti->nik)
                            ->first();
                        if ($cekjadwalshift != null) {
                            $kode_jadwal = $cekjadwalshift->kode_jadwal;
                        } else {
                            $cekjadwalkaryawan = Karyawan::where('nik', $izincuti->nik)->first();
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
                        Presensi::where('nik', $izincuti->nik)->where('tanggal', $dari)->delete();
                        $presensi = Presensi::create([
                            'nik' => $izincuti->nik,
                            'tanggal' => $dari,
                            'kode_jadwal' => $kode_jadwal,
                            'kode_jam_kerja' => $kode_jam_kerja,
                            'status' => 'c',
                        ]);

                        Presensiizincuti::create([
                            'id_presensi' => $presensi->id,
                            'kode_izin_cuti' => $kode_izin_cuti,
                        ]);
                        $dari = date('Y-m-d', strtotime($dari . ' +1 day'));
                    }

                    //dd($request->direktur);
                    if (isset($request->direktur)) {
                        //dd('test');
                        $userrole = User::role('direktur')->where('status', 1)->first();
                        Disposisiizincuti::create([
                            'kode_disposisi' => $kode_disposisi,
                            'kode_izin_cuti' => $kode_izin_cuti,
                            'id_pengirim' => auth()->user()->id,
                            'id_penerima' => $userrole->id,
                            'status' => 0,
                        ]);
                    }
                } else {
                    Disposisiizincuti::create([
                        'kode_disposisi' => $kode_disposisi,
                        'kode_izin_cuti' => $kode_izin_cuti,
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


    public function cancel($kode_izin_cuti)
    {
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $kode_izin_cuti = Crypt::decrypt($kode_izin_cuti);
        $i_cuti = new Izincuti();
        $izincuti = $i_cuti->getIzincuti(kode_izin_cuti: $kode_izin_cuti)->first();
        $role = $user->getRoleNames()->first();
        $roles_approve = cekRoleapprovepresensi($izincuti->kode_dept, $izincuti->kode_cabang, $izincuti->kategori_jabatan, $izincuti->kode_jabatan);
        $end_role = end($roles_approve);
        DB::beginTransaction();
        try {

            Disposisiizincuti::where('kode_izin_cuti', $kode_izin_cuti)
                ->where('id_pengirim', auth()->user()->id)
                ->where('id_penerima', '!=', auth()->user()->id)
                ->delete();

            Disposisiizincuti::where('kode_izin_cuti', $kode_izin_cuti)
                ->where('id_penerima', auth()->user()->id)
                ->update([
                    'status' => 0
                ]);
            if ($role == 'direktur') {
                Izincuti::where('kode_izin_cuti', $kode_izin_cuti)
                    ->update([
                        'direktur' => 0
                    ]);
            } else {
                if ($role == $end_role) {
                    Izincuti::where('kode_izin_cuti', $kode_izin_cuti)
                        ->update([
                            'status' => 0
                        ]);

                    $presensi_izinabsen = Presensiizincuti::select('id_presensi')->where('kode_izin_cuti', $kode_izin_cuti);
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
}
