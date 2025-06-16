<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Detailjadwalkerja;
use App\Models\Detailjadwalshift;
use App\Models\Disposisiizinabsen;
use App\Models\Izinabsen;
use App\Models\Karyawan;
use App\Models\Presensi;
use App\Models\Presensizinabsen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class IzinabsenController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $i_absen = new Izinabsen();
        // dd($i_absen->getIzinabsen(request: $request)->get());
        $izinabsen = $i_absen->getIzinabsen(request: $request)->paginate(15);
        $izinabsen->appends(request()->all());
        $data['izinabsen'] = $izinabsen;
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['roles_can_approve'] = config('presensi.approval');
        $data['level_hrd'] = config('presensi.approval.level_hrd');
        // dd($data['roles_approve'][$role]);

        return view('hrd.pengajuanizin.izinabsen.index', $data);
    }

    public function create()
    {
        $k = new Karyawan();
        $data['karyawan'] = $k->getkaryawanpresensi()->get();
        return view('hrd.pengajuanizin.izinabsen.create', $data);
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
            $jmlhari = hitungHari($request->dari, $request->sampai);
            if ($jmlhari > 3) {
                return Redirect::back()->with(messageError('Tidak Boleh Lebih dari 3 Hari!'));
            }

            $lastizin = Izinabsen::select('kode_izin')
                ->whereRaw('YEAR(dari)="' . date('Y', strtotime($request->dari)) . '"')
                ->whereRaw('MONTH(dari)="' . date('m', strtotime($request->dari)) . '"')
                ->orderBy("kode_izin", "desc")
                ->first();
            $last_kode_izin = $lastizin != null ? $lastizin->kode_izin : '';
            $kode_izin  = buatkode($last_kode_izin, "IA"  . date('ym', strtotime($request->dari)), 4);
            $k = new Karyawan();
            $karyawan = $k->getKaryawan($request->nik);

            $head = $karyawan->kode_dept == 'HRD' && $karyawan->kode_jabatan=='J12' || $karyawan->kode_jabatan=='J02' ? '1' : '0';
            Izinabsen::create([
                'kode_izin' => $kode_izin,
                'nik' => $request->nik,
                'kode_jabatan' => $karyawan->kode_jabatan,
                'kode_dept' => $karyawan->kode_dept,
                'kode_cabang' => $karyawan->kode_cabang,
                'tanggal' => $request->dari,
                'dari' => $request->dari,
                'sampai' => $request->sampai,
                'keterangan' => $request->keterangan,
                'head' => $head,
                'status' => 0,
                'direktur' => 0,
                'id_user' => $user->id,
            ]);

            // $cekregional = Cabang::where('kode_cabang', $karyawan->kode_cabang)->first();

            // $roles_approve = cekRoleapprovepresensi($karyawan->kode_dept, $karyawan->kode_cabang, $karyawan->kategori, $karyawan->kode_jabatan);

            // //dd($roles_approve);
            // // dd($karyawan->kategori);
            // // dd($roles_approve);
            // if (in_array($role, $roles_approve)) {
            //     $index_role = array_search($role, $roles_approve);
            // } else {
            //     $index_role = 0;
            // }
            // // Jika Tidak Ada di dalam array

            // if (in_array($roles_approve[$index_role], ['operation manager', 'sales marketing manager'])) {
            //     $cek_user_approve = User::role($roles_approve[$index_role])->where('status', 1)
            //         ->where('kode_cabang', $karyawan->kode_cabang)
            //         ->first();
            // } else {
            //     if ($roles_approve[$index_role] == 'regional sales manager') {
            //         $cek_user_approve = User::role($roles_approve[$index_role])
            //             ->where('kode_regional', $cekregional->kode_regional)
            //             ->where('status', 1)
            //             ->first();
            //     } else {
            //         $cek_user_approve = User::role($roles_approve[$index_role])->where('status', 1)->first();
            //     }
            // }




            // if ($cek_user_approve == null) {
            //     for ($i = $index_role + 1; $i < count($roles_approve); $i++) {
            //         if ($roles_approve[$i] == 'regional sales manager') {
            //             $cek_user_approve = User::role($roles_approve[$index_role])
            //                 ->where('kode_regional', $cekregional->kode_regional)
            //                 ->where('status', 1)
            //                 ->first();
            //         } else {
            //             $cek_user_approve = User::role($roles_approve[$index_role])->where('status', 1)->first();
            //         }

            //         // $cek_user_approve = User::role($roles_approve[$i])
            //         //     ->where('status', 1)
            //         //     ->first();
            //         if ($cek_user_approve != null) {
            //             break;
            //         }
            //     }
            // }


            // $tanggal_hariini = date('Y-m-d');
            // $lastdisposisi = Disposisiizinabsen::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
            //     ->orderBy('kode_disposisi', 'desc')
            //     ->first();
            // $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
            // $format = "DPIA" . date('Ymd');
            // $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);


            // Disposisiizinabsen::create([
            //     'kode_disposisi' => $kode_disposisi,
            //     'kode_izin' => $kode_izin,
            //     'id_pengirim' => auth()->user()->id,
            //     'id_penerima' => $cek_user_approve->id,
            //     'status' => 0
            // ]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($kode_izin)
    {
        $kode_izin = Crypt::decrypt($kode_izin);
        $data['izinabsen'] = Izinabsen::where('kode_izin', $kode_izin)->first();
        $k = new Karyawan();
        $data['karyawan'] = $k->getkaryawanpresensi()->get();
        return view('hrd.pengajuanizin.izinabsen.edit', $data);
    }


    public function update(Request $request, $kode_izin)
    {
        $kode_izin = Crypt::decrypt($kode_izin);
        $request->validate([
            'dari' => 'required',
            'sampai' => 'required',
            'keterangan' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $jmlhari = hitungHari($request->dari, $request->sampai);
            if ($jmlhari > 3) {
                return Redirect::back()->with(messageError('Tidak Boleh Lebih dari 3 Hari!'));
            }

            Izinabsen::where('kode_izin', $kode_izin)->update([
                'tanggal' => $request->dari,
                'dari' => $request->dari,
                'sampai' => $request->sampai,
                'keterangan' => $request->keterangan,
            ]);

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function approve($kode_izin)
    {
        $kode_izin = Crypt::decrypt($kode_izin);

        $user = User::find(auth()->user()->id);
        $i_absen = new Izinabsen();

        $izinabsen = $i_absen->getIzinabsen(kode_izin: $kode_izin)->first();

        $data['izinabsen'] = $izinabsen;
        $level_hrd = ['asst. manager hrd', 'spv presensi'];
        $role = $user->getRoleNames()->first();
        $data['level_hrd'] = $level_hrd;
        $data['role'] = $role;
        return view('hrd.pengajuanizin.izinabsen.approve', $data);
    }


    public function show($kode_izin)
    {
        $kode_izin = Crypt::decrypt($kode_izin);
        $user = User::find(auth()->user()->id);
        $i_absen = new Izinabsen();
        $izinabsen = $i_absen->getIzinabsen(kode_izin: $kode_izin)->first();
        $data['izinabsen'] = $izinabsen;

        $role = $user->getRoleNames()->first();
        $roles_approve = cekRoleapprovepresensi($izinabsen->kode_dept, $izinabsen->kode_cabang, $izinabsen->kategori_jabatan, $izinabsen->kode_jabatan);
        $end_role = end($roles_approve);
        if ($role != $end_role && in_array($role, $roles_approve)) {
            $cek_index = array_search($role, $roles_approve) + 1;
        } else {
            $cek_index = count($roles_approve) - 1;
        }

        $nextrole = $roles_approve[$cek_index];
        if ($nextrole == "regional sales manager") {
            $userrole = User::role($nextrole)
                ->where('kode_regional', $izinabsen->kode_regional)
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
                        ->where('kode_regional', $izinabsen->kode_regional)
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
        return view('hrd.pengajuanizin.izinabsen.show', $data);
    }

    public function storeapprove($kode_izin, Request $request)
    {
        $kode_izin = Crypt::decrypt($kode_izin);
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $level_hrd = ['asst. manager hrd', 'spv presensi'];


        $i_absen = new Izinabsen();
        $izinabsen = $i_absen->getIzinabsen(kode_izin: $kode_izin)->first();
        DB::beginTransaction();
        try {
            if ($role != 'direktur') {
                if (!in_array($role, $level_hrd)) {
                    Izinabsen::where('kode_izin', $kode_izin)->update([
                        'head' => 1,
                    ]);
                } else {
                    //dd('test');

                    $forward_to_direktur = isset($request->direktur) ? 1 : 0;
                    Izinabsen::where('kode_izin', $kode_izin)
                        ->update([
                            'hrd' => 1,
                            'status' => 1,
                            'forward_to_direktur' => $forward_to_direktur
                        ]);





                    $dari = $izinabsen->dari;
                    $sampai = $izinabsen->sampai;

                    while (strtotime($dari) <= strtotime($sampai)) {
                        //Cek Jadwal Shift
                        $cekjadwalshift = Detailjadwalshift::join('hrd_jadwalshift', 'hrd_jadwalshift.kode_jadwalshift', 'hrd_jadwalshift_detail.kode_jadwalshift')
                            ->whereRaw($dari . ' between dari and sampai')
                            ->where('nik', $izinabsen->nik)
                            ->first();
                        if ($cekjadwalshift != null) {
                            $kode_jadwal = $cekjadwalshift->kode_jadwal;
                        } else {
                            $cekjadwalkaryawan = Karyawan::where('nik', $izinabsen->nik)->first();
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
                        Presensi::where('nik', $izinabsen->nik)->where('tanggal', $dari)->delete();
                        $presensi = Presensi::create([
                            'nik' => $izinabsen->nik,
                            'tanggal' => $dari,
                            'kode_jadwal' => $kode_jadwal,
                            'kode_jam_kerja' => $kode_jam_kerja,
                            'status' => 'i',
                        ]);

                        Presensizinabsen::create([
                            'id_presensi' => $presensi->id,
                            'kode_izin' => $kode_izin,
                        ]);
                        $dari = date('Y-m-d', strtotime($dari . ' +1 day'));
                    }
                    if (isset($request->forward_to_direktur)) {
                        Izinabsen::where('kode_izin', $kode_izin)
                            ->update([
                                'forward_to_direktur' => 1
                            ]);
                    }
                }
            } else {
                if ($izinabsen->forward_to_direktur == 1) {
                    Izinabsen::where('kode_izin', $kode_izin)
                        ->update([
                            'direktur' => 1
                        ]);
                } else {
                    Izinabsen::where('kode_izin', $kode_izin)
                        ->update([
                            'head' => 1,
                            'direktur' => 1
                        ]);
                }
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Di Approve'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }




        // $i_absen = new Izinabsen();
        // $izinabsen = $i_absen->getIzinabsen(kode_izin: $kode_izin)->first();
        // $role = $user->getRoleNames()->first() == 'spv presensi' ? 'asst. manager hrd' : $user->getRoleNames()->first();
        // $roles_approve = cekRoleapprovepresensi($izinabsen->kode_dept, $izinabsen->kode_cabang, $izinabsen->kategori_jabatan, $izinabsen->kode_jabatan);
        // $end_role = end($roles_approve);

        // if ($role != $end_role && in_array($role, $roles_approve)) {
        //     $cek_index = array_search($role, $roles_approve);
        //     $nextrole = $roles_approve[$cek_index + 1];
        //     $userrole = User::role($nextrole)
        //         ->where('status', 1)
        //         ->first();
        // }


        // // dd($userrole);

        // DB::beginTransaction();
        // try {
        //     // Upadate Disposisi Pengirim

        //     // dd($kode_penilaian);
        //     Disposisiizinabsen::where('kode_izin', $kode_izin)
        //         ->where('id_penerima', auth()->user()->id)
        //         ->update([
        //             'status' => 1
        //         ]);





        //     if ($role == 'direktur') {
        //         Izinabsen::where('kode_izin', $kode_izin)->update([
        //             'direktur' => 1
        //         ]);
        //     } else {
        //         //Insert Dispsosi ke Penerima
        //         $tanggal_hariini = date('Y-m-d');
        //         $lastdisposisi = Disposisiizinabsen::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
        //             ->orderBy('kode_disposisi', 'desc')
        //             ->first();
        //         $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
        //         $format = "DPIA" . date('Ymd');
        //         $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);

        //         if ($role == $end_role) {
        //             Izinabsen::where('kode_izin', $kode_izin)
        //                 ->update([
        //                     'status' => 1
        //                 ]);


        //             $dari = $izinabsen->dari;
        //             $sampai = $izinabsen->sampai;

        //             while (strtotime($dari) <= strtotime($sampai)) {
        //                 //Cek Jadwal Shift
        //                 $cekjadwalshift = Detailjadwalshift::join('hrd_jadwalshift', 'hrd_jadwalshift.kode_jadwalshift', 'hrd_jadwalshift_detail.kode_jadwalshift')
        //                     ->whereRaw($dari . ' between dari and sampai')
        //                     ->where('nik', $izinabsen->nik)
        //                     ->first();
        //                 if ($cekjadwalshift != null) {
        //                     $kode_jadwal = $cekjadwalshift->kode_jadwal;
        //                 } else {
        //                     $cekjadwalkaryawan = Karyawan::where('nik', $izinabsen->nik)->first();
        //                     $kode_jadwal =  $cekjadwalkaryawan->kode_jadwal;
        //                 }

        //                 $nama_hari = getNamahari($dari);




        //                 $cekjamkerja = Detailjadwalkerja::where('kode_jadwal', $kode_jadwal)->where('hari', $nama_hari)->first();


        //                 if ($cekjamkerja != null) {
        //                     $kode_jam_kerja = $cekjamkerja->kode_jam_kerja;
        //                 } else {
        //                     DB::rollback();
        //                     return Redirect::back()->with(messageError('Karyawan Belum Diatur Jam Kerja'));
        //                 }

        //                 //Hapus Jika Sudah Ada Data Presensi
        //                 Presensi::where('nik', $izinabsen->nik)->where('tanggal', $dari)->delete();
        //                 $presensi = Presensi::create([
        //                     'nik' => $izinabsen->nik,
        //                     'tanggal' => $dari,
        //                     'kode_jadwal' => $kode_jadwal,
        //                     'kode_jam_kerja' => $kode_jam_kerja,
        //                     'status' => 'i',
        //                 ]);

        //                 Presensizinabsen::create([
        //                     'id_presensi' => $presensi->id,
        //                     'kode_izin' => $kode_izin,
        //                 ]);
        //                 $dari = date('Y-m-d', strtotime($dari . ' +1 day'));
        //             }
        //             if (isset($request->direktur)) {
        //                 $userrole = User::role('direktur')->where('status', 1)->first();
        //                 Disposisiizinabsen::create([
        //                     'kode_disposisi' => $kode_disposisi,
        //                     'kode_izin' => $kode_izin,
        //                     'id_pengirim' => auth()->user()->id,
        //                     'id_penerima' => $userrole->id,
        //                     'status' => 0,
        //                 ]);
        //             }
        //         } else {

        //             Disposisiizinabsen::create([
        //                 'kode_disposisi' => $kode_disposisi,
        //                 'kode_izin' => $kode_izin,
        //                 'id_pengirim' => auth()->user()->id,
        //                 'id_penerima' => $userrole->id,
        //                 'status' => 0,
        //             ]);
        //         }
        //     }



        //     DB::commit();
        //     return Redirect::back()->with(messageSuccess('Data Berhasil Disetujui'));
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     dd($e);
        //     return Redirect::back()->with(messageError($e->getMessage()));
        // }
    }

    public function cancel($kode_izin)
    {
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $kode_izin = Crypt::decrypt($kode_izin);
        // $i_absen = new Izinabsen();
        $level_hrd = config('presensi.approval.level_hrd');
        // $izinabsen = $i_absen->getIzinabsen(kode_izin: $kode_izin)->first();
        $role = $user->getRoleNames()->first();
        DB::beginTransaction();
        try {
            if ($role != 'direktur') {


                if (in_array($role, $level_hrd)) {

                    Izinabsen::where('kode_izin', $kode_izin)
                        ->update([
                            'status' => 0,
                            'hrd' => 0,
                            'forward_to_direktur' => 0

                        ]);

                    $presensi_izinabsen = Presensizinabsen::select('id_presensi')->where('kode_izin', $kode_izin);
                    $presensi = $presensi_izinabsen->get();
                    $id_presensi = [];
                    foreach ($presensi as $d) {
                        $id_presensi[] = $d->id_presensi;
                    }
                    $presensi_izinabsen->delete();

                    Presensi::whereIn('id', $id_presensi)->delete();
                } else {
                    Izinabsen::where('kode_izin', $kode_izin)->update([
                        'head' => 0
                    ]);
                }
            } else {


                Izinabsen::where('kode_izin', $kode_izin)->update([
                    'direktur' => 0
                ]);
            }



            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dibatalkan'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
            //throw $th;
        }
    }


    public function destroy($kode_izin)
    {
        $kode_izin = Crypt::decrypt($kode_izin);
        try {
            Izinabsen::where('kode_izin', $kode_izin)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
