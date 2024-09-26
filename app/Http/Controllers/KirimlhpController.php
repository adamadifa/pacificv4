<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Kirimlhp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class KirimlhpController extends Controller
{
    public function index(Request $request)
    {
        $query = Kirimlhp::query();
        if (!empty($request->bulan)) {
            $query->where('bulan', $request->bulan);
        } else {
            $query->where('bulan', date('m'));
        }
        if (!empty($request->tahun)) {
            $query->where('tahun', $request->tahun);
        } else {
            $query->where('tahun', date('Y'));
        }
        $query->join('cabang', 'kirim_lhp.kode_cabang', '=', 'cabang.kode_cabang');
        $query->orderBy('kirim_lhp.created_at');
        $data['kirim_lhp'] = $query->get();
        return view('utilities.kirimlhp.index', $data);
    }


    public function create()
    {
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('utilities.kirimlhp.create', $data);
    }


    public function store(Request $request)
    {

        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');

        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang;
            $request->validate([
                'kode_cabang' => 'required',
                'bulan' => 'required',
                'tahun' => 'required',
                'tanggal' => 'required',
                'jam_kirim' => 'required',
                'foto' => 'required',
            ]);
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
            $request->validate([
                'bulan' => 'required',
                'tahun' => 'required',
                'tanggal' => 'required',
                'jam_kirim' => 'required',
                'foto' => 'required',
            ]);
        }

        try {
            $kode_kirim_lhp = $kode_cabang . $request->bulan . $request->tahun;
            $cek = Kirimlhp::where('kode_kirim_lhp', $kode_kirim_lhp)->count();
            if ($cek > 0) {
                return Redirect::back()->with(messageError('Data Sudah Ada'));
            }

            if ($request->hasfile('foto')) {
                $foto_name =  $kode_kirim_lhp . "." . $request->file('foto')->getClientOriginalExtension();
                $destination_foto_path = "/public/pelanggan";
                $foto = $foto_name;
            }
            $simpan = Kirimlhp::create([
                'kode_kirim_lhp' => $kode_kirim_lhp,
                'tanggal' => $request->tanggal,
                'kode_cabang' => $kode_cabang,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'jam' => $request->jam_kirim,
                'status' => 0,
                'foto' => $foto,
            ]);

            if ($simpan) {
                if ($request->hasfile('foto')) {
                    $request->file('foto')->storeAs($destination_foto_path, $foto_name);
                }
            }

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {

            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
