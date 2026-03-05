<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Detailpenilaiankaryawan;

use App\Models\Itempenilaian;
use App\Models\Jasamasakerja;
use App\Models\Karyawan;
use App\Models\Kontrakkaryawan;
use App\Models\Penilaiankaryawan;
use App\Models\Presensi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PenilaiankaryawanController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $role_names = $user->getRoleNames()->toArray();
        $user_role_ids = array_map('getRoleID', $role_names);
        $data['role_names'] = $role_names;
        $data['user_role_ids'] = $user_role_ids;
        $data['role'] = $role_names[0] ?? null; // Keep for backward compatibility if needed
        $data['role_id'] = $user_role_ids[0] ?? null; // Keep for backward compatibility if needed
        $pk = new Penilaiankaryawan();
        $penilaiankaryawan = $pk->getPenilaiankaryawan(request: $request)->paginate(15);
        $penilaiankaryawan->appends(request()->all());
        $data['penilaiankaryawan'] = $penilaiankaryawan;
        // dd(auth()->user()->kode_dept);
        $data['listApprovepenilaian'] = listApprovepenilaian(auth()->user()->kode_dept, $user->getRoleNames()->first());

        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();

        return view('hrd.penilaiankaryawan.index', $data);

        //Mobile
        // return view('hrd.penilaiankaryawan.mobile.index', $data);
    }


    public function create()
    {
        $k = new Karyawan();
        $karyawan = $k->getKaryawanpenilaian()->get();
        $data['karyawan'] = $karyawan;
        return view('hrd.penilaiankaryawan.create', $data);
    }

    public function createpenilaian(Request $request)
    {
        $k = new Karyawan();
        $karyawan = $k->getKaryawan($request->nik);
        $data['karyawan'] = $karyawan;
        //dd($request->no_kontrak);
        //Kontrak Karyawan
        $kk = new Kontrakkaryawan();
        $data['kontrak'] = $kk->getKontrak($request->no_kontrak)->first();

        $doc_2 = ['J15', 'J16', 'J18', 'J19', 'J21', 'J22', 'J23', 'J24'];
        $doc = in_array($karyawan->kode_jabatan, $doc_2) ?  2 : 1;
        $data['doc'] = $doc;
        $data['tanggal'] = $request->tanggal;
        // dd($karyawan->kode_jabatan);
        // dd($doc);
        $cekpenilaian = Penilaiankaryawan::where('no_kontrak', $request->no_kontrak)
            ->count();

        if ($cekpenilaian > 0) {
            return Redirect::back()->with('error', 'Penilaian Karyawan sudah pernah dilakukan');
        }
        $data['penilaian_item'] = Itempenilaian::where('kode_doc', $doc)
            ->select('hrd_penilaian_item.*', 'hrd_penilaian_kategori.nama_kategori')
            ->join('hrd_penilaian_kategori', 'hrd_penilaian_item.kode_kategori', '=', 'hrd_penilaian_kategori.kode_kategori')
            ->orderBy('hrd_penilaian_item.kode_kategori')
            ->orderBy('hrd_penilaian_item.kode_item')
            ->get();

        $data['rekappresensi'] = Presensi::select(
            DB::raw("SUM(IF(hrd_presensi.status='h',1,0)) as hadir"),
            DB::raw("SUM(IF(hrd_presensi.status='i',1,0)) as izin"),
            DB::raw("SUM(IF(hrd_presensi.status='s' AND doc_sid IS NULL,1,0)) as sakit"),
            DB::raw("SUM(IF(hrd_presensi.status='a',1,0)) as alpa"),
            DB::raw("SUM(IF(hrd_presensi.status='c',1,0)) as cuti"),
            DB::raw("SUM(IF(doc_sid IS NOT NULL,1,0)) as sid"),
        )
            ->where('hrd_presensi.nik', $request->nik)
            ->whereBetween('hrd_presensi.tanggal', [$data['kontrak']->dari, $data['kontrak']->sampai])
            ->leftJoin('hrd_presensi_izinsakit', 'hrd_presensi.id', '=', 'hrd_presensi_izinsakit.id_presensi')
            ->leftJoin('hrd_izinsakit', 'hrd_presensi_izinsakit.kode_izin_sakit', '=', 'hrd_izinsakit.kode_izin_sakit')
            ->first();
        if ($doc == 1) {
            return view('hrd.penilaiankaryawan.create_penilaian_1', $data);
        } else {
            return view('hrd.penilaiankaryawan.create_penilaian_2', $data);
        }
    }


    public function store(Request $request, $no_kontrak)
    {
        $no_kontrak = Crypt::decrypt($no_kontrak);
        $request->validate([
            'skor.*' => 'required',
            'rekomendasi' => 'required',
            'evaluasi' => 'required',
            'masa_kontrak' => 'required',

        ]);

        DB::beginTransaction();
        try {
            $user = User::findorfail(auth()->user()->id);
            $role = $user->getRoleNames()->first();
            $lastpenilaian = Penilaiankaryawan::whereRaw('YEAR(tanggal)="' . date('Y', strtotime($request->tanggal)) . '"')
                ->whereRaw('MONTH(tanggal)="' . date('m', strtotime($request->tanggal)) . '"')
                ->orderBy("kode_penilaian", "desc")
                ->first();
            $last_kode_penilaian = $lastpenilaian != null ? $lastpenilaian->kode_penilaian : '';
            $kode_penilaian = buatkode($last_kode_penilaian, "PK" . date('my', strtotime($request->tanggal)), 2);

            $kk = new Kontrakkaryawan();
            $kontrak = $kk->getKontrak($no_kontrak)->first();

            $k = new Karyawan();
            $karyawan = $k->getKaryawan($kontrak->nik);


            $sid = !empty($request->sid) ? $request->sid : 0;
            $sakit = !empty($request->sakit) ? $request->sakit : 0;
            $izin = !empty($request->izin) ? $request->izin : 0;
            $alfa = !empty($request->alfa) ? $request->alfa : 0;

            Penilaiankaryawan::create([
                'kode_penilaian' => $kode_penilaian,
                'nik' => $karyawan->nik,
                'tanggal' => $request->tanggal,
                'kontrak_dari' => $kontrak->dari,
                'kontrak_sampai' => $kontrak->sampai,
                'rekomendasi' => $request->rekomendasi,
                'evaluasi' => $request->evaluasi,
                'masa_kontrak' => $request->masa_kontrak,
                'kode_perusahaan' => $karyawan->kode_perusahaan,
                'kode_cabang' => $karyawan->kode_cabang,
                'kode_dept' => $karyawan->kode_dept,
                'kode_jabatan' => $karyawan->kode_jabatan,
                'kode_doc' => $request->kode_doc,
                'sid' => $sid,
                'sakit' => $sakit,
                'alfa' => $alfa,
                'izin' => $izin,
                'status' => 0,
                'status_pemutihan' => 0,
                'no_kontrak' => $no_kontrak,
                'id_user' => auth()->user()->id
            ]);

            foreach ($request->skor as $kode_item => $skor) {
                Detailpenilaiankaryawan::create([
                    'kode_penilaian' => $kode_penilaian,
                    'kode_item' => $kode_item,
                    'nilai' => $skor
                ]);
            }


            $roles_approve = cekRoleapprove($karyawan->kode_dept, $karyawan->kode_cabang, $karyawan->kategori, $karyawan->kode_jabatan);

            if (!empty($roles_approve)) {
                $user_roles = $user->getRoleNames()->toArray();
                $found_index = -1;

                // Cari role tertinggi user yang ada di dalam antrian approval
                foreach ($roles_approve as $index => $r_approve) {
                    if (in_array($r_approve, $user_roles)) {
                        $found_index = $index;
                    }
                }

                $is_approved = 0;
                if ($found_index != -1) {
                    $next_index = $found_index + 1;
                    if ($next_index >= count($roles_approve)) {
                        $is_approved = 1;
                        $posisi_ajuan = getRoleID($roles_approve[$found_index]);
                    } else {
                        $posisi_ajuan = getRoleID($roles_approve[$next_index]);
                    }
                } else {
                    $posisi_ajuan = getRoleID($roles_approve[0]);
                }

                Penilaiankaryawan::where('kode_penilaian', $kode_penilaian)->update([
                    'posisi_ajuan' => $posisi_ajuan,
                    'status' => $is_approved
                ]);
            }

            //Jika Departemen Marketing dan Cabang != Pusat

            DB::commit();
            return redirect('/penilaiankaryawan')->with(messageSuccess('Data Berhasil Ditambah'));
        } catch (\Exception $e) {
            DB::rollBack();

            dd($e);
            return redirect('/penilaiankaryawan')->with(messageError($e->getMessage()));
        }
    }



    public function edit($kode_penilaian)
    {
        $kode_penilaian = Crypt::decrypt($kode_penilaian);
        $penilaiankaryawan = Penilaiankaryawan::where('kode_penilaian', $kode_penilaian)
            ->select(
                'hrd_penilaian.*',
                'nama_karyawan',
                'nama_dept',
                'nama_jabatan',
                'nama_cabang'
            )
            ->join('hrd_karyawan', 'hrd_penilaian.nik', '=', 'hrd_karyawan.nik')
            ->join('hrd_departemen', 'hrd_penilaian.kode_dept', '=', 'hrd_departemen.kode_dept')
            ->join('hrd_jabatan', 'hrd_penilaian.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan')
            ->join('cabang', 'hrd_penilaian.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();


        $doc = $penilaiankaryawan->kode_doc;
        $data['penilaiankaryawan'] = $penilaiankaryawan;
        $data['penilaian_item'] = Detailpenilaiankaryawan::where('kode_penilaian', $kode_penilaian)
            ->select(
                'hrd_penilaian_detail.*',
                'hrd_penilaian_item.item_penilaian',
                'hrd_penilaian_item.kode_kategori',
                'hrd_penilaian_item.jenis_kompetensi',
                'hrd_penilaian_kategori.nama_kategori'
            )
            ->join('hrd_penilaian_item', 'hrd_penilaian_detail.kode_item', '=', 'hrd_penilaian_item.kode_item')
            ->join('hrd_penilaian_kategori', 'hrd_penilaian_item.kode_kategori', '=', 'hrd_penilaian_kategori.kode_kategori')
            ->orderBy('hrd_penilaian_item.kode_kategori')
            ->orderBy('hrd_penilaian_item.kode_item')
            ->get();
        if ($doc == 1) {
            return view('hrd.penilaiankaryawan.edit_penilaian_1', $data);
        } else {
            return view('hrd.penilaiankaryawan.edit_penilaian_2', $data);
        }
    }

    public function update(Request $request, $kode_penilaian)
    {
        $kode_penilaian = Crypt::decrypt($kode_penilaian);
        $request->validate([
            'skor.*' => 'required',
            'rekomendasi' => 'required',
            'evaluasi' => 'required',
            'masa_kontrak' => 'required'
        ]);
        DB::beginTransaction();
        try {
            Penilaiankaryawan::where('kode_penilaian', $kode_penilaian)->update([
                'rekomendasi' => $request->rekomendasi,
                'evaluasi' => $request->evaluasi,
                'masa_kontrak' => $request->masa_kontrak,
                'sakit' => $request->sakit,
                'alfa' => $request->alfa,
                'izin' => $request->izin,
                'sid' => $request->sid,
                'status_pemutihan' => $request->status_pemutihan || 0
            ]);
            foreach ($request->skor as $kode_item => $skor) {
                Detailpenilaiankaryawan::where('kode_penilaian', $kode_penilaian)->where('kode_item', $kode_item)->update([
                    'nilai' => $skor
                ]);
            }

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
    public function destroy($kode_penilaian)
    {
        $kode_penilaian = Crypt::decrypt($kode_penilaian);
        try {
            Penilaiankaryawan::where('kode_penilaian', $kode_penilaian)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function cetak($kode_penilaian)
    {
        $kode_penilaian = Crypt::decrypt($kode_penilaian);
        $penilaiankaryawan = Penilaiankaryawan::where('kode_penilaian', $kode_penilaian)
            ->select(
                'hrd_penilaian.*',
                'nama_karyawan',
                'nama_dept',
                'nama_jabatan',
                'nama_cabang',
                'hrd_karyawan.jenis_kelamin',
                'hrd_karyawan.foto'
            )
            ->join('hrd_karyawan', 'hrd_penilaian.nik', '=', 'hrd_karyawan.nik')
            ->join('hrd_departemen', 'hrd_penilaian.kode_dept', '=', 'hrd_departemen.kode_dept')
            ->join('hrd_jabatan', 'hrd_penilaian.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan')
            ->join('cabang', 'hrd_penilaian.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();
        $data['penilaiankaryawan'] = $penilaiankaryawan;
        $data['penilaian_item'] = Detailpenilaiankaryawan::where('kode_penilaian', $kode_penilaian)
            ->select(
                'hrd_penilaian_detail.*',
                'hrd_penilaian_item.item_penilaian',
                'hrd_penilaian_item.kode_kategori',
                'hrd_penilaian_item.jenis_kompetensi',
                'hrd_penilaian_kategori.nama_kategori'
            )
            ->join('hrd_penilaian_item', 'hrd_penilaian_detail.kode_item', '=', 'hrd_penilaian_item.kode_item')
            ->join('hrd_penilaian_kategori', 'hrd_penilaian_item.kode_kategori', '=', 'hrd_penilaian_kategori.kode_kategori')
            ->orderBy('hrd_penilaian_item.kode_kategori')
            ->orderBy('hrd_penilaian_item.kode_item')
            ->get();

        $data['historikontrak'] = Kontrakkaryawan::where('nik', $penilaiankaryawan->nik)
            ->orderBy('tanggal')
            ->get();

        $data['historipemutihan'] = Jasamasakerja::where('nik', $penilaiankaryawan->nik)->orderBy('tanggal')->get();
        if ($penilaiankaryawan->kode_doc == 1) {
            return view('hrd.penilaiankaryawan.cetak_1', $data);
        } else {
            return view('hrd.penilaiankaryawan.cetak_2', $data);
        }
    }



    public function approve($kode_penilaian)
    {


        $user = User::findorfail(auth()->user()->id);
        $kode_penilaian = Crypt::decrypt($kode_penilaian);

        $pk = new Penilaiankaryawan();
        $penilaiankaryawan = $pk->getPenilaiankaryawan($kode_penilaian)->first();
        $role = $user->getRoleNames()->first();
        $roles_approve = cekRoleapprove($penilaiankaryawan->kode_dept, $penilaiankaryawan->kode_cabang, $penilaiankaryawan->kategori_jabatan, $penilaiankaryawan->kode_jabatan);
        $end_role = end($roles_approve);

        if (in_array($role, $roles_approve)) {
            if ($role != $end_role) {
                $cek_index = array_search($role, $roles_approve) + 1;
            } else {
                $cek_index = count($roles_approve) - 1;
            }
        } else {
            // If super admin or other role not in approval sequence, determine next role from current position
            $current_posisi_index = array_search($penilaiankaryawan->posisi_ajuan_name, $roles_approve);
            if ($current_posisi_index !== false) {
                if ($penilaiankaryawan->posisi_ajuan_name != $end_role) {
                    $cek_index = $current_posisi_index + 1;
                } else {
                    $cek_index = count($roles_approve) - 1;
                }
            } else {
                $cek_index = 0;
            }
        }

        $nextrole = $roles_approve[$cek_index];
        if ($nextrole == "regional sales manager") {
            $userrole = User::role($nextrole)
                ->where('kode_regional', $penilaiankaryawan->kode_regional)
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
                        ->where('kode_regional', $penilaiankaryawan->kode_regional)
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
        $data['doc'] = $penilaiankaryawan->kode_doc;
        $data['penilaiankaryawan'] = $penilaiankaryawan;
        $data['total_score'] = Detailpenilaiankaryawan::where('kode_penilaian', $kode_penilaian)
            ->select(DB::raw('SUM(nilai) as total_score'))->first();
        return view('hrd.penilaiankaryawan.approve', $data);
    }


    public function storeapprove($kode_penilaian, Request $request)
    {
        $kode_penilaian = Crypt::decrypt($kode_penilaian);
        $user = User::findorfail(auth()->user()->id);
        $pk = new Penilaiankaryawan();
        $penilaiankaryawan = $pk->getPenilaiankaryawan($kode_penilaian)->first();
        $role = $user->getRoleNames()->first();
        $roles_approve = cekRoleapprove($penilaiankaryawan->kode_dept, $penilaiankaryawan->kode_cabang, $penilaiankaryawan->kategori_jabatan, $penilaiankaryawan->kode_jabatan);
        $end_role = end($roles_approve);

        if (in_array($role, $roles_approve)) {
            $cek_index = array_search($role, $roles_approve);
            if ($role != $end_role) {
                $nextrole = $roles_approve[$cek_index + 1];
            }
        } else {
            // Bypass logic for super admin
            $current_posisi_index = array_search($penilaiankaryawan->posisi_ajuan_name, $roles_approve);
            if ($current_posisi_index !== false) {
                if ($penilaiankaryawan->posisi_ajuan_name != $end_role) {
                    $nextrole = $roles_approve[$current_posisi_index + 1];
                    $role = $penilaiankaryawan->posisi_ajuan_name; // Set current role as the step being advanced
                } else {
                    $role = $end_role;
                }
            } else {
                $nextrole = $roles_approve[0];
                $role = ""; // Force it to NOT match end_role if sequence hasn't started
            }
        }

        //dd($userrole);

        DB::beginTransaction();
        try {







            if ($role == $end_role) {
                Penilaiankaryawan::where('kode_penilaian', $kode_penilaian)
                    ->update([
                        'status' => 1,
                        'posisi_ajuan' => getRoleID($role) // Tetap di role terakhir saat sudah disetujui
                    ]);
            } else {
                // Update posisi_ajuan ke role berikutnya
                Penilaiankaryawan::where('kode_penilaian', $kode_penilaian)->update([
                    'posisi_ajuan' => getRoleID($nextrole)
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

    public function cancel($kode_penilaian)
    {
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $kode_penilaian = Crypt::decrypt($kode_penilaian);
        try {


            Penilaiankaryawan::where('kode_penilaian', $kode_penilaian)
                ->update([
                    'status' => 0,
                    'posisi_ajuan' => getRoleID($role)
                ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Dibatalkan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
            //throw $th;
        }
    }
}
