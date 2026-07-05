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
            ->leftJoin('users as approver', 'dokumen_opname.approved_by', '=', 'approver.id')
            ->select('dokumen_opname.*', 'cabang.nama_cabang', 'users.name as nama_uploader', 'approver.name as nama_approver');

        // Date filtering
        $dari = $request->dari ?? date('Y-m-01');
        $sampai = $request->sampai ?? date('Y-m-d');
        
        $query->whereBetween('dokumen_opname.tanggal', [$dari, $sampai]);

        // Branch permission check (same logic as getCabang)
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $query->whereIn('dokumen_opname.kode_cabang', $cabang->pluck('kode_cabang'));

        if (!empty($request->kode_cabang)) {
            $query->where('dokumen_opname.kode_cabang', $request->kode_cabang);
        }

        $data['dokumen_opname'] = $query->orderBy('dokumen_opname.tanggal', 'desc')->get();
        $data['cabang'] = $cabang;
        $data['dari'] = $dari;
        $data['sampai'] = $sampai;
        $data['namabulan'] = config('global.list_bulan');

        return view('worksheetom.dokumenopname.index', $data);
    }

    public function create()
    {
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
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
                'tanggal' => 'required|date',
                'file_persediaan' => 'required|mimes:pdf,png,jpg,jpeg|max:300',
                'file_kas_kecil' => 'required|mimes:pdf,png,jpg,jpeg|max:300',
                'file_kas_besar' => 'required|mimes:pdf,png,jpg,jpeg|max:300',
            ]);
        } else {
            $kode_cabang = Auth::user()->kode_cabang;
            $request->validate([
                'tanggal' => 'required|date',
                'file_persediaan' => 'required|mimes:pdf,png,jpg,jpeg|max:300',
                'file_kas_kecil' => 'required|mimes:pdf,png,jpg,jpeg|max:300',
                'file_kas_besar' => 'required|mimes:pdf,png,jpg,jpeg|max:300',
            ]);
        }

        if (!$user->hasRole(['super admin'])) {
            $target_date = \Carbon\Carbon::parse($request->tanggal);
            $today = \Carbon\Carbon::today();
            if ($today->greaterThan($target_date->addDay())) {
                return Redirect::back()->with(messageError('Batas waktu upload maksimal adalah H+1 dari tanggal dokumen opname!'));
            }
        }

        try {
            $kode_dokumen_opname = $kode_cabang . date('Ymd', strtotime($request->tanggal));
            $cek = Dokumenopname::where('kode_dokumen_opname', $kode_dokumen_opname)->count();
            if ($cek > 0) {
                return Redirect::back()->with(messageError('Dokumen Opname untuk cabang dan tanggal tersebut sudah ada!'));
            }

            $file_persediaan = null;
            $file_kas_kecil = null;
            $file_kas_besar = null;
            $destination_path = "/public/dokumen_opname";

            if ($request->hasfile('file_persediaan')) {
                $file_persediaan = $kode_dokumen_opname . "_persediaan." . $request->file('file_persediaan')->getClientOriginalExtension();
            }
            if ($request->hasfile('file_kas_kecil')) {
                $file_kas_kecil = $kode_dokumen_opname . "_kaskecil." . $request->file('file_kas_kecil')->getClientOriginalExtension();
            }
            if ($request->hasfile('file_kas_besar')) {
                $file_kas_besar = $kode_dokumen_opname . "_kasbesar." . $request->file('file_kas_besar')->getClientOriginalExtension();
            }

            $simpan = Dokumenopname::create([
                'kode_dokumen_opname' => $kode_dokumen_opname,
                'tanggal' => $request->tanggal,
                'kode_cabang' => $kode_cabang,
                'file_persediaan' => $file_persediaan,
                'file_kas_kecil' => $file_kas_kecil,
                'file_kas_besar' => $file_kas_besar,
                'status_approval' => 0,
                'id_user' => Auth::user()->id,
            ]);

            if ($simpan) {
                if ($request->hasfile('file_persediaan')) {
                    $request->file('file_persediaan')->storeAs($destination_path, $file_persediaan);
                }
                if ($request->hasfile('file_kas_kecil')) {
                    $request->file('file_kas_kecil')->storeAs($destination_path, $file_kas_kecil);
                }
                if ($request->hasfile('file_kas_besar')) {
                    $request->file('file_kas_besar')->storeAs($destination_path, $file_kas_besar);
                }
            }

            return Redirect::back()->with(messageSuccess('Dokumen Opname berhasil di-upload'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function approve($kode_dokumen_opname)
    {
        $kode_dokumen_opname = Crypt::decrypt($kode_dokumen_opname);
        $user = User::findorFail(Auth::user()->id);

        if (!$user->hasRole(['sales marketing manager', 'super admin'])) {
            return Redirect::back()->with(messageError('Anda tidak memiliki hak akses untuk menyetujui dokumen ini!'));
        }

        try {
            Dokumenopname::where('kode_dokumen_opname', $kode_dokumen_opname)->update([
                'status_approval' => 1,
                'approved_by' => Auth::user()->id,
                'approved_at' => now(),
            ]);
            return Redirect::back()->with(messageSuccess('Dokumen Opname berhasil disetujui'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError('Gagal menyetujui dokumen opname'));
        }
    }

    public function reject($kode_dokumen_opname)
    {
        $kode_dokumen_opname = Crypt::decrypt($kode_dokumen_opname);
        $user = User::findorFail(Auth::user()->id);

        if (!$user->hasRole(['sales marketing manager', 'super admin'])) {
            return Redirect::back()->with(messageError('Anda tidak memiliki hak akses untuk menolak dokumen ini!'));
        }

        try {
            Dokumenopname::where('kode_dokumen_opname', $kode_dokumen_opname)->update([
                'status_approval' => 2,
                'approved_by' => Auth::user()->id,
                'approved_at' => now(),
            ]);
            return Redirect::back()->with(messageSuccess('Dokumen Opname berhasil ditolak'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError('Gagal menolak dokumen opname'));
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
                if (!empty($dokumen->file_persediaan)) {
                    Storage::delete($destination_path . "/" . $dokumen->file_persediaan);
                }
                if (!empty($dokumen->file_kas_kecil)) {
                    Storage::delete($destination_path . "/" . $dokumen->file_kas_kecil);
                }
                if (!empty($dokumen->file_kas_besar)) {
                    Storage::delete($destination_path . "/" . $dokumen->file_kas_besar);
                }
            }
            return Redirect::back()->with(messageSuccess('Dokumen Opname berhasil dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError('Gagal menghapus dokumen opname'));
        }
    }
}
