<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Detailjadwalshift;
use App\Models\Disposisiizinkeluar;
use App\Models\Izinabsen;
use App\Models\Izinkeluar;
use App\Models\Karyawan;
use App\Models\Presensi;
use App\Models\Presensiizinkeluar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class IzinkeluarController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $i_keluar = new Izinkeluar();
        $izinkeluar = $i_keluar->getIzinkeluar(request: $request)->paginate(15);
        $izinkeluar->appends(request()->all());
        $data['izinkeluar'] = $izinkeluar;
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['roles_can_approve'] = config('presensi.approval');
        $data['level_hrd'] = config('presensi.approval.level_hrd');
        return view('hrd.pengajuanizin.izinkeluar.index', $data);
    }


    public function create()
    {
        $k = new Karyawan();
        $data['karyawan'] = $k->getkaryawanpresensi()->get();
        return view('hrd.pengajuanizin.izinkeluar.create', $data);
    }

    public function store(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $request->validate([
            'nik' => 'required',
            'tanggal' => 'required',
            'jam_keluar' => 'required',
            'keperluan' => 'required',
            'keterangan' => 'required',
        ]);
        DB::beginTransaction();
        try {

            $lastizinkeluar = Izinkeluar::select('kode_izin_keluar')
                ->whereRaw('YEAR(tanggal)="' . date('Y', strtotime($request->tanggal)) . '"')
                ->whereRaw('MONTH(tanggal)="' . date('m', strtotime($request->tanggal)) . '"')
                ->orderBy("kode_izin_keluar", "desc")
                ->first();
            $last_kode_izin_keluar = $lastizinkeluar != null ? $lastizinkeluar->kode_izin_keluar : '';
            $kode_izin_keluar  = buatkode($last_kode_izin_keluar, "IK"  . date('ym', strtotime($request->tanggal)), 4);
            $k = new Karyawan();
            $karyawan = $k->getKaryawan($request->nik);
            $head = $karyawan->kode_dept == 'HRD' && $karyawan->kode_jabatan=='J12' || $karyawan->kode_jabatan=='J02' ? '1' : '0';
            Izinkeluar::create([
                'kode_izin_keluar' => $kode_izin_keluar,
                'nik' => $request->nik,
                'kode_jabatan' => $karyawan->kode_jabatan,
                'kode_dept' => $karyawan->kode_dept,
                'kode_cabang' => $karyawan->kode_cabang,
                'tanggal' => $request->tanggal,
                'keperluan' => $request->keperluan,
                'jam_keluar' => $request->tanggal . ' ' . $request->jam_keluar,
                'keterangan' => $request->keterangan,
                'head' => $head,
                'status' => 0,
                'direktur' => 0,
                'id_user' => $user->id,
            ]);


            // $roles_approve = cekRoleapprovepresensi($karyawan->kode_dept, $karyawan->kode_cabang, $karyawan->kategori, $karyawan->kode_jabatan);

            // if (in_array($role, $roles_approve)) {
            //     $index_role = array_search($role, $roles_approve);
            // } else {
            //     $index_role = 0;
            // }

            // if (in_array($roles_approve[$index_role], ['operation manager', 'sales marketing manager'])) {
            //     $cek_user_approve = User::role($roles_approve[$index_role])->where('status', 1)
            //         ->where('kode_cabang', $karyawan->kode_cabang)
            //         ->first();
            // } else {
            //     if ($roles_approve[$index_role] == 'regional sales manager') {
            //         $cek_user_approve = User::role($roles_approve[$index_role])
            //             ->where('kode_regional', $karyawan->kode_regional)
            //             ->where('status', 1)
            //             ->first();
            //     } else {
            //         $cek_user_approve = User::role($roles_approve[$index_role])->where('status', 1)->first();
            //     }
            // }

            // if ($cek_user_approve == null) {
            //     for ($i = $index_role + 1; $i < count($roles_approve); $i++) {
            //         // $cek_user_approve = User::role($roles_approve[$i])
            //         //     ->where('status', 1)
            //         //     ->first();
            //         if ($roles_approve[$i] == 'regional sales manager') {
            //             $cek_user_approve = User::role($roles_approve[$index_role])
            //                 ->where('kode_regional', $karyawan->kode_regional)
            //                 ->where('status', 1)
            //                 ->first();
            //         } else {
            //             $cek_user_approve = User::role($roles_approve[$index_role])->where('status', 1)->first();
            //         }

            //         if ($cek_user_approve != null) {
            //             break;
            //         }
            //     }
            // }

            // $tanggal_hariini = date('Y-m-d');
            // $lastdisposisi = Disposisiizinkeluar::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
            //     ->orderBy('kode_disposisi', 'desc')
            //     ->first();
            // $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
            // $format = "DPIK" . date('Ymd');
            // $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);

            // Disposisiizinkeluar::create([
            //     'kode_disposisi' => $kode_disposisi,
            //     'kode_izin_keluar' => $kode_izin_keluar,
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

    public function edit($kode_izin_keluar)
    {
        $kode_izin_keluar = Crypt::decrypt($kode_izin_keluar);
        $data['izinkeluar'] = Izinkeluar::where('kode_izin_keluar', $kode_izin_keluar)->first();
        $k = new Karyawan();
        $data['karyawan'] = $k->getkaryawanpresensi()->get();
        return view('hrd.pengajuanizin.izinkeluar.edit', $data);
    }

    public function update(Request $request, $kode_izin_keluar)
    {
        $kode_izin_keluar = Crypt::decrypt($kode_izin_keluar);
        $request->validate([
            'tanggal' => 'required',
            'jam_keluar' => 'required',
            'keperluan' => 'required',
            'keterangan' => 'required',
        ]);
        DB::beginTransaction();
        try {

            Izinkeluar::where('kode_izin_keluar', $kode_izin_keluar)->update([
                'tanggal' => $request->tanggal,
                'jam_keluar' => $request->tanggal . ' ' . $request->jam_keluar,
                'keperluan' => $request->keperluan,
                'keterangan' => $request->keterangan,
            ]);

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_izin_keluar)
    {
        $kode_izin_keluar = Crypt::decrypt($kode_izin_keluar);
        try {
            Izinkeluar::where('kode_izin_keluar', $kode_izin_keluar)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function approve($kode_izin_keluar)
    {
        $kode_izin_keluar = Crypt::decrypt($kode_izin_keluar);

        $user = User::find(auth()->user()->id);
        $i_keluar = new Izinkeluar();

        $izinkeluar = $i_keluar->getIzinkeluar(kode_izin_keluar: $kode_izin_keluar)->first();

        $data['izinkeluar'] = $izinkeluar;
        $level_hrd = ['asst. manager hrd', 'spv presensi'];
        $role = $user->getRoleNames()->first();
        $data['level_hrd'] = $level_hrd;
        $data['role'] = $role;
        return view('hrd.pengajuanizin.izinkeluar.approve', $data);
    }


    public function show($kode_izin_keluar)
    {
        $kode_izin_keluar = Crypt::decrypt($kode_izin_keluar);
        $user = User::find(auth()->user()->id);
        $i_keluar = new Izinkeluar();
        $izinkeluar = $i_keluar->getIzinkeluar(kode_izin_keluar: $kode_izin_keluar)->first();
        $data['izinkeluar'] = $izinkeluar;

        $role = $user->getRoleNames()->first();
        $roles_approve = cekRoleapprovepresensi($izinkeluar->kode_dept, $izinkeluar->kode_cabang, $izinkeluar->kategori_jabatan, $izinkeluar->kode_jabatan);
        $end_role = end($roles_approve);
        if ($role != $end_role && in_array($role, $roles_approve)) {
            $cek_index = array_search($role, $roles_approve) + 1;
        } else {
            $cek_index = count($roles_approve) - 1;
        }

        $nextrole = $roles_approve[$cek_index];
        if ($nextrole == "regional sales manager") {
            $userrole = User::role($nextrole)
                ->where('kode_regional', $izinkeluar->kode_regional)
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
                        ->where('kode_regional', $izinkeluar->kode_regional)
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
        return view('hrd.pengajuanizin.izinkeluar.show', $data);
    }



    public function storeapprove($kode_izin_keluar, Request $request)
    {
        $kode_izin_keluar = Crypt::decrypt($kode_izin_keluar);
        $user = User::findorfail(auth()->user()->id);
        $i_keluar = new Izinkeluar();
        $izinkeluar = $i_keluar->getIzinkeluar(kode_izin_keluar: $kode_izin_keluar)->first();
        $role = $user->getRoleNames()->first();
        $level_hrd = config('presensi.approval.level_hrd');



        //dd($userrole);

        DB::beginTransaction();
        try {

            if ($role != 'direktur') {
                if (!in_array($role, $level_hrd)) {
                    Izinkeluar::where('kode_izin_keluar', $kode_izin_keluar)->update([
                        'head' => 1,
                    ]);
                } else {
                    $forward_to_direktur = isset($request->direktur) ? 1 : 0;
                    Izinkeluar::where('kode_izin_keluar', $kode_izin_keluar)
                        ->update([
                            'hrd' => 1,
                            'status' => 1,
                            'forward_to_direktur' => $forward_to_direktur
                        ]);

                    $cekpresensi = Presensi::where('nik', $izinkeluar->nik)->where('tanggal', $izinkeluar->tanggal)->first();
                    //dd($cekpresensi);
                    if ($cekpresensi != null) {
                        Presensiizinkeluar::create([
                            'id_presensi' => $cekpresensi->id,
                            'kode_izin_keluar' => $kode_izin_keluar,
                        ]);
                    } else {
                        DB::rollBack();
                        return Redirect::back()->with(messageError('Karyawan Belum Melakukan Presesnsi Pada Tanggal Tersebut'));
                    }

                    if (isset($request->forward_to_direktur)) {
                        Izinkeluar::where('kode_izin_keluar', $kode_izin_keluar)
                            ->update([
                                'forward_to_direktur' => 1
                            ]);
                    }
                }
            } else {
                if ($izinkeluar->forward_to_direktur == 1) {
                    Izinkeluar::where('kode_izin_keluar', $kode_izin_keluar)
                        ->update([
                            'direktur' => 1
                        ]);
                } else {
                    Izinkeluar::where('kode_izin_keluar', $kode_izin_keluar)
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
    }


    public function cancel($kode_izin_keluar)
    {
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $kode_izin_keluar = Crypt::decrypt($kode_izin_keluar);
        $i_keluar = new Izinkeluar();
        $izinkeluar = $i_keluar->getIzinkeluar(kode_izin_keluar: $kode_izin_keluar)->first();
        $role = $user->getRoleNames()->first();
        $level_hrd = config('presensi.approval.level_hrd');
        DB::beginTransaction();
        try {

            if ($role != 'direktur') {


                if (in_array($role, $level_hrd)) {

                    Izinkeluar::where('kode_izin_keluar', $kode_izin_keluar)
                        ->update([
                            'status' => 0,
                            'hrd' => 0,
                            'forward_to_direktur' => 0

                        ]);
                        Presensiizinkeluar::where('kode_izin_keluar', $kode_izin_keluar)->delete();
                } else {
                    Izinkeluar::where('kode_izin_keluar', $kode_izin_keluar)->update([
                        'head' => 0
                    ]);
                }
            } else {
                Izinkeluar::where('kode_izin_keluar', $kode_izin_keluar)->update([
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

    public function updatejamkembali($kode_izin_keluar)
    {
        $kode_izin_keluar = Crypt::decrypt($kode_izin_keluar);
        $i_keluar = new Izinkeluar();
        $izinkeluar = $i_keluar->getIzinkeluar(kode_izin_keluar: $kode_izin_keluar)->first();
        $data['izinkeluar'] = $izinkeluar;
        return view('hrd.pengajuanizin.izinkeluar.updatejamkembali', $data);
    }

    public function storeupdatejamkembali(Request $request, $kode_izin_keluar)
    {

        $kode_izin_keluar = Crypt::decrypt($kode_izin_keluar);
        $request->validate([
            'jam_kembali' => 'required',
        ]);
        DB::beginTransaction();
        try {
            Izinkeluar::where('kode_izin_keluar', $kode_izin_keluar)
                ->update([
                    'jam_kembali' => $request->jam_kembali
                ]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
