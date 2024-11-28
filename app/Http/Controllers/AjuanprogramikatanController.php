<?php

namespace App\Http\Controllers;

use App\Models\Ajuanprogramikatan;
use App\Models\Cabang;
use App\Models\Programikatan;
use App\Models\User;
use Illuminate\Http\Request;
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
}
