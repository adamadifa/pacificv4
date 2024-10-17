<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Gaji;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\Kontrakgaji;
use App\Models\Kontrakkaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class KontrakkaryawanController extends Controller
{
    public function index(Request $request)
    {
        $kk = new Kontrakkaryawan();
        $kontrak = $kk->getKontrak(request: $request)->paginate(15);
        $kontrak->appends(request()->all());
        $data['kontrak'] = $kontrak;
        return view('hrd.kontrak.index', $data);
    }

    public function create()
    {
        $data['jabatan'] = Jabatan::orderBy('kode_jabatan')->get();
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['karyawan'] = Karyawan::orderBy('nik')
            ->whereNotIn('nik', function ($query) {
                $query->select('nik')->from('hrd_kontrak');
            })
            ->whereNotIn('status_karyawan', ['O', 'T'])
            ->where('status_aktif_karyawan', 1)
            ->get();
        return view('hrd.kontrak.create', $data);
    }

    public function store(Request $request)
    {
        $nik = Crypt::decrypt($request->nik);
        $request->validate([
            'nik' => 'required',
            'kode_perusahaan' => 'required',
            'kode_cabang' => 'required',
            'kode_dept' => 'required',
            'kode_jabatan' => 'required',
            'dari' => 'required',
        ]);

        DB::beginTransaction();
        try {
            //Generate No. Kontrak
            $lastkontrak = Kontrakkaryawan::select('no_kontrak')
                ->whereRaw('MONTH(tanggal)="' . date('m', strtotime($request->dari)) . '"')
                ->whereRaw('YEAR(tanggal)="' . date('Y', strtotime($request->dari)) . '"')
                ->orderBy("no_kontrak", "desc")
                ->first();
            $last_no_kontrak = $lastkontrak != null ? $lastkontrak->no_kontrak : '';
            $no_kontrak  = buatkode($last_no_kontrak, "K" . date('my', strtotime($request->dari)), 3);

            //Generate Kode Gaji

            $lastgaji = Gaji::select('kode_gaji')
                ->whereRaw('YEAR(tanggal_berlaku)="' . date('Y', strtotime($request->dari)) . '"')
                ->whereRaw('LEFT(kode_gaji,3)="S' . date('y', strtotime($request->dari)) . '"')
                ->orderBy("kode_gaji", "desc")
                ->first();

            $last_kode_gaji = $lastgaji != null ? $lastgaji->kode_gaji : '';
            $kode_gaji  = buatkode($last_kode_gaji, "S" . date('y', strtotime($request->dari)), 4);

            //Cek Kontrak Terakhir Karyawan
            $lastkontrakkaryawan = Kontrakkaryawan::where('nik', $nik)
                ->orderBy('tanggal', 'desc')
                ->orderBy('no_kontrak', 'desc')
                ->first();
            Kontrakkaryawan::create([
                'no_kontrak' => $no_kontrak,
                'nik' => $nik,
                'tanggal' => $request->dari,
                'kode_perusahaan' => $request->kode_perusahaan,
                'kode_cabang' => $request->kode_cabang,
                'kode_dept' => $request->kode_dept,
                'kode_jabatan' => $request->kode_jabatan,
                'dari' => $request->dari,
                'sampai' => $request->sampai,
                'status_kontrak' => 1,
                'status_pemutihan' => 0,
            ]);

            Gaji::create([
                'nik' => $nik,
                'kode_gaji' => $kode_gaji,
                'gaji_pokok' => toNumber($request->gaji_pokok),
                't_jabatan' => toNumber($request->t_jabatan),
                't_masakerja' => toNumber($request->t_masakerja),
                't_tanggungjawab' => toNumber($request->t_tanggungjawab),
                't_makan' => toNumber($request->t_makan),
                't_istri' => toNumber($request->t_istri),
                't_skill' => toNumber($request->t_skill),
                'tanggal_berlaku' => $request->dari
            ]);

            Kontrakgaji::create([
                'no_kontrak' => $no_kontrak,
                'kode_gaji' => $kode_gaji,
            ]);

            if ($lastkontrakkaryawan != null) {
                $lastkontrakkaryawan->update([
                    'status_kontrak' => 0
                ]);
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($no_kontrak)
    {
        $no_kontrak = Crypt::decrypt($no_kontrak);

        DB::beginTransaction();

        try {
            $kontrak = Kontrakkaryawan::find($no_kontrak)->first();
            $gaji = Kontrakgaji::where('no_kontrak', $no_kontrak)->first();

            Kontrakkaryawan::where('no_kontrak', $no_kontrak)->delete();

            if ($gaji != null) {
                Gaji::where('kode_gaji', $gaji->kode_gaji)->delete();
            }


            $lastkontrak = Kontrakkaryawan::where('nik', $kontrak->nik)
                ->where('no_kontrak', '!=', $no_kontrak)
                ->orderBy('tanggal', 'desc')
                ->orderBy('no_kontrak', 'desc')
                ->first();
            if ($lastkontrak != null) {
                $lastkontrak->update([
                    'status_kontrak' => 1
                ]);
            }

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
    public function edit($no_kontrak)
    {
        $no_kontrak = Crypt::decrypt($no_kontrak);
        $kontrak = Kontrakkaryawan::where('no_kontrak', $no_kontrak)->first();
        $kontrakgaji = Kontrakgaji::where('no_kontrak', $no_kontrak)->first();
        $kode_gaji = $kontrakgaji != null ? $kontrakgaji->kode_gaji : '';
        $gaji = Gaji::where('kode_gaji', $kode_gaji)->first();
        //dd($gaji);
        $data['kontrak'] = $kontrak;
        $data['gaji'] = $gaji;
        $data['jabatan'] = Jabatan::orderBy('kode_jabatan')->get();
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        return view('hrd.kontrak.edit', $data);
    }


    public function update(Request $request, $no_kontrak)
    {

        $no_kontrak = Crypt::decrypt($no_kontrak);
        $request->validate([
            'kode_perusahaan' => 'required',
            'kode_cabang' => 'required',
            'kode_dept' => 'required',
            'kode_jabatan' => 'required',
            'dari' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $kontrak = Kontrakkaryawan::where('no_kontrak', $no_kontrak)->first();
            $cekkontrak = Kontrakkaryawan::where('nik', $kontrak->nik)
                ->where('tanggal', '>', $request->dari)
                ->first();
            if ($cekkontrak != null) {
                return Redirect::back()->with(messageError('Kontrak Sudah Tidak Dapat di Ubah Karena Sudah Ada Kontrak Baru'));
            }
            $kontrakgaji = Kontrakgaji::where('no_kontrak', $no_kontrak)->first();
            Kontrakkaryawan::where('no_kontrak', $no_kontrak)->update([
                'kode_perusahaan' => $request->kode_perusahaan,
                'kode_cabang' => $request->kode_cabang,
                'kode_dept' => $request->kode_dept,
                'kode_jabatan' => $request->kode_jabatan,
                'dari' => $request->dari,
                'sampai' => $request->sampai
            ]);

            Gaji::where('kode_gaji', $kontrakgaji->kode_gaji)->update([
                'tanggal_berlaku' => $request->dari,
                'gaji_pokok' => toNumber($request->gaji_pokok),
                't_jabatan' => toNumber($request->t_jabatan),
                't_masakerja' => toNumber($request->t_masakerja),
                't_tanggungjawab' => toNumber($request->t_tanggungjawab),
                't_makan' => toNumber($request->t_makan),
                't_istri' => toNumber($request->t_istri),
                't_skill' => toNumber($request->t_skill)
            ]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Diubah'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function cetak($no_kontrak)
    {
        $no_kontrak = Crypt::decrypt($no_kontrak);
        $kk = new Kontrakkaryawan();
        $data['kontrak'] = $kk->getKontrak(no_kontrak: $no_kontrak)->first();
        $data['pihak_satu'] = config('hrd.pihak_satu');
        $kontrakgaji = Kontrakgaji::where('no_kontrak', $no_kontrak)->first();
        $data['gaji'] = Gaji::where('kode_gaji', $kontrakgaji->kode_gaji)->first();

        //dd($data['gaji']);
        if ($data['kontrak']->masa_kontrak == 'KT' || $data['kontrak']->dari == $data['kontrak']->sampai) {
            return view('hrd.kontrak.cetak_pkwtt', $data);
        } else {
            return view('hrd.kontrak.cetak', $data);
        }
    }

    public function getlastkontrak(Request $request)
    {

        $nik = $request->nik;
        $lastkontrak = Kontrakkaryawan::where('nik', $nik)
            ->orderBy('tanggal', 'desc')
            ->orderBy('no_kontrak', 'desc')
            ->first();
        return response()->json([
            'success' => true,
            'message' => 'Data Kontrak',
            'data'    => $lastkontrak
        ]);
    }
}
