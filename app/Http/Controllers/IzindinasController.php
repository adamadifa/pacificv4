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
        $data['roles_can_approve'] = config('presensi.approval');
        $data['level_hrd'] = config('presensi.approval.level_hrd');
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
            $head = $karyawan->kode_dept == 'HRD' && $karyawan->kode_jabatan=='J12' || $karyawan->kode_jabatan=='J02' ? '1' : '0';

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
                'head' => $head,
                'status' => 0,
                'direktur' => 0,
                'id_user' => $user->id,
            ];


            Izindinas::create($dataizindinas);

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
        $level_hrd = ['asst. manager hrd', 'spv presensi'];
        $role = $user->getRoleNames()->first();
        $data['level_hrd'] = $level_hrd;
        $data['role'] = $role;
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
        $level_hrd = config('presensi.approval.level_hrd');
        DB::beginTransaction();
        try {

            if ($role != 'direktur') {
                if (!in_array($role, $level_hrd)) {
                    Izindinas::where('kode_izin_dinas', $kode_izin_dinas)->update([
                        'head' => 1,
                    ]);
                } else {
                    //dd('test');

                    $forward_to_direktur = isset($request->direktur) ? 1 : 0;
                    Izindinas::where('kode_izin_dinas', $kode_izin_dinas)
                        ->update([
                            'hrd' => 1,
                            'status' => 1,
                            'forward_to_direktur' => $forward_to_direktur
                        ]);

                   
                }
            } else {
                if ($izindinas->forward_to_direktur == 1) {
                    Izindinas::where('kode_izin_dinas', $kode_izin_dinas)
                        ->update([
                            'direktur' => 1
                        ]);
                } else {
                    Izindinas::where('kode_izin_dinas', $kode_izin_dinas)
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


    public function cancel($kode_izin_dinas)
    {
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $kode_izin_dinas = Crypt::decrypt($kode_izin_dinas);
        $i_dinas = new Izindinas();
        $izindinas = $i_dinas->getIzindinas(kode_izin_dinas: $kode_izin_dinas)->first();
        $role = $user->getRoleNames()->first();
        $level_hrd = config('presensi.approval.level_hrd');
        DB::beginTransaction();
        try {

            if ($role != 'direktur') {


                if (in_array($role, $level_hrd)) {

                    Izindinas::where('kode_izin_dinas', $kode_izin_dinas)
                        ->update([
                            'status' => 0,
                            'hrd' => 0,
                            'forward_to_direktur' => 0

                        ]);
                } else {
                    Izindinas::where('kode_izin_dinas', $kode_izin_dinas)->update([
                        'head' => 0
                    ]);
                }
            } else {


                Izindinas::where('kode_izin_dinas', $kode_izin_dinas)->update([
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
