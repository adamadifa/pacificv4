<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Dokumenopname;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DokumenopnameController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findorFail(Auth::user()->id);
        
        $query = Dokumenopname::query();
        $query->join('cabang', 'dokumen_opname.kode_cabang', '=', 'cabang.kode_cabang')
            ->join('users', 'dokumen_opname.id_user', '=', 'users.id')
            ->select('dokumen_opname.*', 'cabang.nama_cabang', 'users.name as nama_uploader');

        // Month & Year filtering
        if (!empty($request->bulan)) {
            $query->where('dokumen_opname.bulan', $request->bulan);
        } else {
            $query->where('dokumen_opname.bulan', date('m'));
        }
        if (!empty($request->tahun)) {
            $query->where('dokumen_opname.tahun', $request->tahun);
        } else {
            $query->where('dokumen_opname.tahun', date('Y'));
        }

        // Branch permission check (same logic as getCabang)
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $query->whereIn('dokumen_opname.kode_cabang', $cabang->pluck('kode_cabang'));

        if (!empty($request->kode_cabang)) {
            $query->where('dokumen_opname.kode_cabang', $request->kode_cabang);
        }

        $data['dokumen_opname'] = $query->orderBy('dokumen_opname.created_at', 'desc')->get();
        $data['cabang'] = $cabang;
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');

        return view('worksheetom.dokumenopname.index', $data);
    }

    public function create()
    {
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('worksheetom.dokumenopname.create', $data);
    }

    public function store(Request $request)
    {
        $user = User::findorFail(Auth::user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');

        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang;
            $request->validate([
                'kode_cabang' => 'required',
                'bulan' => 'required',
                'tahun' => 'required',
                'tanggal' => 'required',
                'file_dokumen' => 'required|mimes:pdf,png,jpg,jpeg|max:5120',
            ]);
        } else {
            $kode_cabang = Auth::user()->kode_cabang;
            $request->validate([
                'bulan' => 'required',
                'tahun' => 'required',
                'tanggal' => 'required',
                'file_dokumen' => 'required|mimes:pdf,png,jpg,jpeg|max:5120',
            ]);
        }

        try {
            $kode_dokumen_opname = $kode_cabang . $request->bulan . $request->tahun;
            $cek = Dokumenopname::where('kode_dokumen_opname', $kode_dokumen_opname)->count();
            if ($cek > 0) {
                return Redirect::back()->with(messageError('Dokumen Opname untuk cabang dan periode tersebut sudah ada!'));
            }

            if ($request->hasfile('file_dokumen')) {
                $file_name = $kode_dokumen_opname . "." . $request->file('file_dokumen')->getClientOriginalExtension();
                $destination_path = "/public/dokumen_opname";
                $file_dokumen = $file_name;
            }

            $simpan = Dokumenopname::create([
                'kode_dokumen_opname' => $kode_dokumen_opname,
                'tanggal' => $request->tanggal,
                'kode_cabang' => $kode_cabang,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'file_dokumen' => $file_dokumen,
                'id_user' => Auth::user()->id,
            ]);

            if ($simpan) {
                if ($request->hasfile('file_dokumen')) {
                    $request->file('file_dokumen')->storeAs($destination_path, $file_name);
                }
            }

            return Redirect::back()->with(messageSuccess('Dokumen Opname berhasil di-upload'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_dokumen_opname)
    {
        $kode_dokumen_opname = Crypt::decrypt($kode_dokumen_opname);
        try {
            $dokumen = Dokumenopname::where('kode_dokumen_opname', $kode_dokumen_opname)->first();
            $delete = Dokumenopname::where('kode_dokumen_opname', $kode_dokumen_opname)->delete();
            if ($delete) {
                $destination_path = "/public/dokumen_opname";
                Storage::delete($destination_path . "/" . $dokumen->file_dokumen);
            }
            return Redirect::back()->with(messageSuccess('Dokumen Opname berhasil dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError('Gagal menghapus dokumen opname'));
        }
    }
}
