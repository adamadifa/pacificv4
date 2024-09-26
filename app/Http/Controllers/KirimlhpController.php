<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Kirimlhp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

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
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
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
                $destination_foto_path = "/public/lhp";
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


    public function destroy($kode_kirim_lhp)
    {
        $kode_kirim_lhp = Crypt::decrypt($kode_kirim_lhp);
        //dd($kode_kirim_lhp);
        try {
            $kirim_lhp = Kirimlhp::where('kode_kirim_lhp', $kode_kirim_lhp)->first();
            $delete = Kirimlhp::where('kode_kirim_lhp', $kode_kirim_lhp)->delete();
            if ($delete) {
                $destination_foto_path = "/public/lhp";
                Storage::delete($destination_foto_path . "/" . $kirim_lhp->foto);
            }
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            // dd($e);
            return Redirect::back()->with(messageError('Data Gagal Disimpan'));
        }
    }

    public function approve($kode_kirim_lhp)
    {
        $kode_kirim_lhp = Crypt::decrypt($kode_kirim_lhp);
        $data['kirim_lhp'] = Kirimlhp::join('cabang', 'kirim_lhp.kode_cabang', '=', 'cabang.kode_cabang')->where('kode_kirim_lhp', $kode_kirim_lhp)->first();
        return view('utilities.kirimlhp.approve', $data);
    }

    public function storeapprove(Request $request, $kode_kirim_lhp)
    {
        $kode_kirim_lhp = Crypt::decrypt($kode_kirim_lhp);
        try {
            Kirimlhp::where('kode_kirim_lhp', $kode_kirim_lhp)->update([
                'status' => 1,
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disetujui'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function cancelapprove($kode_kirim_lhp)
    {
        $kode_kirim_lhp = Crypt::decrypt($kode_kirim_lhp);
        try {
            Kirimlhp::where('kode_kirim_lhp', $kode_kirim_lhp)->update([
                'status' => 0,
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Dibatalkan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
