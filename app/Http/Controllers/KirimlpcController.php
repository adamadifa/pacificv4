<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kirimlpc; // Pastikan model ini ada
use App\Models\Cabang;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class KirimlpcController extends Controller
{
    public function index(Request $request)
    {
        $query = Kirimlpc::query();
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
        $query->join('cabang', 'kirim_lpc.kode_cabang', '=', 'cabang.kode_cabang');
        $query->orderBy('kirim_lpc.created_at');
        $data['kirim_lpc'] = $query->get();
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('utilities.kirimlpc.index', $data);
    }

    public function create()
    {
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('utilities.kirimlpc.create', $data);
    }

    public function store(Request $request)
    {
        $user = User::findOrFail(auth()->user()->id);
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
            $kode_kirim_lpc = $kode_cabang . $request->bulan . $request->tahun;
            $cek = Kirimlpc::where('kode_kirim_lpc', $kode_kirim_lpc)->count();
            if ($cek > 0) {
                return Redirect::back()->with('messageError', 'Data Sudah Ada');
            }

            if ($request->hasFile('foto')) {
                $foto_name =  $kode_kirim_lpc . "." . $request->file('foto')->getClientOriginalExtension();
                $destination_foto_path = "/public/lpc";
                $foto = $foto_name;
            }

            $simpan = Kirimlpc::create([
                'kode_kirim_lpc' => $kode_kirim_lpc,
                'tanggal' => $request->tanggal,
                'kode_cabang' => $kode_cabang,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'jam' => $request->jam_kirim,
                'status' => 0,
                'foto' => $foto,
            ]);

            if ($simpan) {
                if ($request->hasFile('foto')) {
                    $request->file('foto')->storeAs($destination_foto_path, $foto_name);
                }
            }

            return Redirect::back()->with('messageSuccess', 'Data Berhasil Disimpan');
        } catch (\Exception $e) {
            return Redirect::back()->with('messageError', $e->getMessage());
        }
    }

    public function destroy($kode_kirim_lpc)
    {
        $kode_kirim_lpc = Crypt::decrypt($kode_kirim_lpc);
        try {
            $kirim_lpc = Kirimlpc::where('kode_kirim_lpc', $kode_kirim_lpc)->first();
            $delete = Kirimlpc::where('kode_kirim_lpc', $kode_kirim_lpc)->delete();
            if ($delete) {
                $destination_foto_path = "/public/lpc";
                Storage::delete($destination_foto_path . "/" . $kirim_lpc->foto);
            }
            return Redirect::back()->with('messageSuccess', 'Data Berhasil Dihapus');
        } catch (\Exception $e) {
            return Redirect::back()->with('messageError', 'Data Gagal Dihapus');
        }
    }

    public function approve($kode_kirim_lpc)
    {
        $kode_kirim_lpc = Crypt::decrypt($kode_kirim_lpc);
        $data['kirim_lpc'] = Kirimlpc::join('cabang', 'kirim_lpc.kode_cabang', '=', 'cabang.kode_cabang')->where('kode_kirim_lpc', $kode_kirim_lpc)->first();
        return view('utilities.kirimlpc.approve', $data);
    }

    public function storeapprove(Request $request, $kode_kirim_lpc)
    {
        $kode_kirim_lpc = Crypt::decrypt($kode_kirim_lpc);
        try {
            Kirimlpc::where('kode_kirim_lpc', $kode_kirim_lpc)->update([
                'status' => 1,
            ]);
            return Redirect::back()->with('messageSuccess', 'Data Berhasil Disetujui');
        } catch (\Exception $e) {
            return Redirect::back()->with('messageError', $e->getMessage());
        }
    }

    public function cancelapprove($kode_kirim_lpc)
    {
        $kode_kirim_lpc = Crypt::decrypt($kode_kirim_lpc);
        try {
            Kirimlpc::where('kode_kirim_lpc', $kode_kirim_lpc)->update([
                'status' => 0,
            ]);
            return Redirect::back()->with('messageSuccess', 'Data Berhasil Dibatalkan');
        } catch (\Exception $e) {
            return Redirect::back()->with('messageError', $e->getMessage());
        }
    }
}
