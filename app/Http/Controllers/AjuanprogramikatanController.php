<?php

namespace App\Http\Controllers;


use App\Models\Ajuanprogramikatan;
use App\Models\Cabang;
use App\Models\Detailajuanprogramikatan;
use App\Models\Pelanggan;
use App\Models\Programikatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class AjuanprogramikatanController extends Controller
{
    public function index()
    {
        $query = Ajuanprogramikatan::query();
        $query->join('cabang', 'marketing_program_ikatan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('program_ikatan', 'marketing_program_ikatan.kode_program', '=', 'program_ikatan.kode_program');
        $query->orderBy('marketing_program_ikatan.no_pengajuan', 'desc');
        $data['ajuanprogramikatan'] = $query->get();
        return view('worksheetom.ajuanprogramikatan.index', $data);
    }

    public function create()
    {
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;

        $data['programikatan'] = Programikatan::orderBy('kode_program')->get();
        return view('worksheetom.ajuanprogramikatan.create', $data);
    }

    public function  store(Request $request)
    {
        $request->validate([
            'no_dokumen' => 'required',
            'tanggal' => 'required',
            'kode_cabang' => 'required',
            'kode_program' => 'required',
            'periode_dari' => 'required',
            'periode_sampai' => 'required',
            'keterangan' => 'required',
        ]);

        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang;
        }
        $tahun = date('Y', strtotime($request->tanggal));
        $lastajuan = Ajuanprogramikatan::select('no_pengajuan')
            ->whereRaw('YEAR(tanggal) = "' . $tahun . '"')
            ->where('kode_cabang', $kode_cabang)
            ->orderBy('no_pengajuan', 'desc')
            ->first();
        $lastno_pengajuan = $lastajuan ? $lastajuan->no_pengajuan : '';
        $no_pengajuan = buatkode($lastno_pengajuan, 'IK' . $kode_cabang . substr($tahun, 2, 2), 4);




        try {
            Ajuanprogramikatan::create([
                'no_pengajuan' => $no_pengajuan,
                'nomor_dokumen' => $request->no_dokumen,
                'tanggal' => $request->tanggal,
                'kode_program' => $request->kode_program,
                'kode_cabang' => $kode_cabang,
                'periode_dari' => $request->periode_dari,
                'periode_sampai' => $request->periode_sampai,
                // 'keterangan' => $request->keterangan,
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function setajuanprogramikatan($no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $data['programikatan'] = Ajuanprogramikatan::where('no_pengajuan', $no_pengajuan)
            ->join('program_ikatan', 'marketing_program_ikatan.kode_program', '=', 'program_ikatan.kode_program')
            ->first();
        $data['detail'] = Detailajuanprogramikatan::join('pelanggan', 'marketing_program_ikatan_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->where('no_pengajuan', $no_pengajuan)
            ->get();
        return view('worksheetom.ajuanprogramikatan.setajuanprogramikatan', $data);
    }

    public function tambahpelanggan($no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $ajuanprogramikatan = Ajuanprogramikatan::where('no_pengajuan', $no_pengajuan)->first();
        $data['ajuanprogramikatan'] = $ajuanprogramikatan;

        $pelanggan = Pelanggan::where('kode_cabang', $ajuanprogramikatan->kode_cabang)->get();
        $data['pelanggan'] = $pelanggan;


        return view('worksheetom.ajuanprogramikatan.tambahpelanggan', $data);
    }

    public function editpelanggan($no_pengajuan, $kode_pelanggan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $data['detail'] = Detailajuanprogramikatan::where('no_pengajuan', $no_pengajuan)
            ->join('pelanggan', 'marketing_program_ikatan_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->where('marketing_program_ikatan_detail.kode_pelanggan', $kode_pelanggan)
            ->first();
        return view('worksheetom.ajuanprogramikatan.editpelanggan', $data);
    }

    public function storepelanggan(Request $request, $no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $request->validate([
            'kode_pelanggan' => 'required',
            'target' => 'required',
            'reward' => 'required',
        ]);

        try {
            //code...
            $cek = Detailajuanprogramikatan::where('no_pengajuan', $no_pengajuan)
                ->where('kode_pelanggan', $request->kode_pelanggan)
                ->first();

            if ($cek) {
                return Redirect::back()->with(messageError('Pelanggan Sudah Ada'));
            }
            Detailajuanprogramikatan::create([
                'no_pengajuan' => $no_pengajuan,
                'kode_pelanggan' => $request->kode_pelanggan,
                'qty_target' => toNumber($request->target),
                'qty_avg' => toNumber($request->qty_avg),
                'reward' => toNumber($request->reward)
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function updatepelanggan(Request $request, $no_pengajuan, $kode_pelanggan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $request->validate([
            'target' => 'required',
            'reward' => 'required',
        ]);

        try {
            //code...
            Detailajuanprogramikatan::where('no_pengajuan', $no_pengajuan)
                ->where('kode_pelanggan', $kode_pelanggan)
                ->update([
                    'qty_target' => toNumber($request->target),
                    'reward' => toNumber($request->reward)
                ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Di Update'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function deletepelanggan($no_pengajuan, $kode_pelanggan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        try {
            Detailajuanprogramikatan::where('no_pengajuan', $no_pengajuan)
                ->where('kode_pelanggan', $kode_pelanggan)
                ->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Di Hapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function getajuanprogramikatan()
    {
        $query = Ajuanprogramikatan::query();
        $query->join('cabang', 'marketing_program_ikatan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('program_ikatan', 'marketing_program_ikatan.kode_program', '=', 'program_ikatan.kode_program');
        $query->orderBy('marketing_program_ikatan.no_pengajuan', 'desc');
        $data['ajuanprogramikatan'] = $query->get();
        return view('worksheetom.ajuanprogramikatan.getajuanprogramikatan', $data);
    }

    public function destroy($no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        try {
            Ajuanprogramikatan::where('no_pengajuan', $no_pengajuan)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
