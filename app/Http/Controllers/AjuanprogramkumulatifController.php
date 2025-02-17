<?php

namespace App\Http\Controllers;

use App\Models\Ajuanprogramkumulatif;
use App\Models\Cabang;
use App\Models\Detailajuanprogramkumulatif;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class AjuanprogramkumulatifController extends Controller
{
    public function index(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $query = Ajuanprogramkumulatif::query();
        $query->join('cabang', 'marketing_program_kumulatif.kode_cabang', '=', 'cabang.kode_cabang');
        $query->orderBy('marketing_program_kumulatif.no_pengajuan', 'desc');
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('marketing_program_kumulatif.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        if (!empty($request->kode_cabang)) {
            $query->where('marketing_program_kumulatif.kode_cabang', $request->kode_cabang);
        }



        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('marketing_program_kumulatif.tanggal', [$request->dari, $request->sampai]);
        }

        if (!empty($request->nomor_dokumen)) {
            $query->where('marketing_program_kumulatif.nomor_dokumen', $request->nomor_dokumen);
        }

        if ($user->hasRole('regional sales manager')) {
            if (!empty($request->status)) {
                if ($request->status == 'pending') {
                    $query->whereNotnull('marketing_program_kumulatif.om');
                    $query->whereNull('marketing_program_kumulatif.rsm');
                } else if ($request->status == 'approved') {
                    $query->whereNotnull('marketing_program_kumulatif.rsm');
                    $query->where('status', 0);
                } else if ($request->status == 'rejected') {
                    $query->where('status', 2);
                }
            }
            $query->whereNotNull('marketing_program_kumulatif.om');
            $query->where('marketing_program_kumulatif.status', '!=', 2);
        }

        if ($user->hasRole('gm marketing')) {
            if (!empty($request->status)) {
                if ($request->status == 'pending') {
                    $query->whereNotnull('marketing_program_kumulatif.rsm');
                    $query->whereNull('marketing_program_kumulatif.gm');
                } else if ($request->status == 'approved') {
                    $query->whereNotnull('marketing_program_kumulatif.gm');
                    $query->where('status', 0);
                } else if ($request->status == 'rejected') {
                    $query->where('status', 2);
                }
            }
            $query->whereNotNull('marketing_program_kumulatif.rsm');
            $query->where('marketing_program_kumulatif.status', '!=', 2);
        }

        if ($user->hasRole('direktur')) {
            if (!empty($request->status)) {
                if ($request->status == 'pending') {
                    $query->whereNotnull('marketing_program_kumulatif.gm');
                    $query->whereNull('marketing_program_kumulatif.direktur');
                    $query->where('status', 0);
                } else if ($request->status == 'approved') {
                    $query->where('status', 1);
                } else if ($request->status == 'rejected') {
                    $query->where('status', 2);
                }
            }
            $query->whereNotNull('marketing_program_kumulatif.gm');
            $query->where('marketing_program_kumulatif.status', '!=', 2);
        }
        $ajuanprogramkumulatif = $query->paginate(15);
        $ajuanprogramkumulatif->appends(request()->all());
        $data['ajuankumulatif'] = $ajuanprogramkumulatif;

        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        $data['user'] = $user;

        return view('worksheetom.ajuanprogramkumulatif.index', $data);
    }

    public function create()
    {
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;


        return view('worksheetom.ajuanprogramkumulatif.create', $data);
    }

    public function  store(Request $request)
    {

        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
            $request->validate([
                'no_dokumen' => 'required',
                'tanggal' => 'required',
            ]);
        } else {
            $kode_cabang = $request->kode_cabang;
            $request->validate([
                'no_dokumen' => 'required',
                'tanggal' => 'required',
                'kode_cabang' => 'required',
            ]);
        }
        $tahun = date('Y', strtotime($request->tanggal));
        $lastajuan = Ajuanprogramkumulatif::select('no_pengajuan')
            ->whereRaw('YEAR(tanggal) = "' . $tahun . '"')
            ->where('kode_cabang', $kode_cabang)
            ->orderBy('no_pengajuan', 'desc')
            ->first();
        $lastno_pengajuan = $lastajuan ? $lastajuan->no_pengajuan : '';
        $no_pengajuan = buatkode($lastno_pengajuan, 'KL' . $kode_cabang . substr($tahun, 2, 2), 4);




        try {
            Ajuanprogramkumulatif::create([
                'no_pengajuan' => $no_pengajuan,
                'nomor_dokumen' => $request->no_dokumen,
                'tanggal' => $request->tanggal,
                'kode_cabang' => $kode_cabang,
                'keterangan' => $request->keterangan,
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function setajuankumulatif($no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $user = User::find(auth()->user()->id);
        $data['programkumulatif'] = Ajuanprogramkumulatif::where('no_pengajuan', $no_pengajuan)
            ->first();
        $data['detail'] = Detailajuanprogramkumulatif::join('pelanggan', 'marketing_program_kumulatif_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->where('marketing_program_kumulatif_detail.no_pengajuan', $no_pengajuan)
            ->join('marketing_program_kumulatif', 'marketing_program_kumulatif_detail.no_pengajuan', '=', 'marketing_program_kumulatif.no_pengajuan')
            ->get();
        $data['user'] = $user;
        return view('worksheetom.ajuanprogramkumulatif.setajuanprogramkumulatif', $data);
    }

    public function tambahpelanggan($no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $ajuanprogramkumulatif = Ajuanprogramkumulatif::where('no_pengajuan', $no_pengajuan)->first();
        $data['ajuanprogramkumulatif'] = $ajuanprogramkumulatif;

        $pelanggan = Pelanggan::where('kode_cabang', $ajuanprogramkumulatif->kode_cabang)->get();
        $data['pelanggan'] = $pelanggan;


        return view('worksheetom.ajuanprogramkumulatif.tambahpelanggan', $data);
    }

    public function editpelanggan($no_pengajuan, $kode_pelanggan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $data['detail'] = Detailajuanprogramkumulatif::where('no_pengajuan', $no_pengajuan)
            ->join('pelanggan', 'marketing_program_kumulatif_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->where('marketing_program_kumulatif_detail.kode_pelanggan', $kode_pelanggan)
            ->first();
        return view('worksheetom.ajuanprogramkumulatif.editpelanggan', $data);
    }

    public function storepelanggan(Request $request, $no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $request->validate([
            'kode_pelanggan' => 'required',
            'metode_pembayaran' => 'required',

        ]);
        $kode_pelanggan = Crypt::decrypt($request->kode_pelanggan);
        try {
            //code...
            $cek = Detailajuanprogramkumulatif::where('kode_pelanggan', $kode_pelanggan)
                ->first();

            if ($cek) {
                return Redirect::back()->with(messageError('Pelanggan Sudah Pernah di Ajukan'));
            }

            if ($request->file('file_doc')) {
                $file_name =  $no_pengajuan . "-" . $kode_pelanggan . "." . $request->file('file_doc')->getClientOriginalExtension();
                $destination_foto_path = "/public/ajuanprogramkumulatif";
                $file = $file_name;
                $request->file('file_doc')->storeAs($destination_foto_path, $file_name);
            } else {
                $file = null;
            }

            Detailajuanprogramkumulatif::create([
                'no_pengajuan' => $no_pengajuan,
                'kode_pelanggan' => $kode_pelanggan,
                'metode_pembayaran' => $request->metode_pembayaran,
                'file_doc' => $file

            ]);


            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            if ($request->file('file_doc')) {
                $file_name =  $no_pengajuan . "-" . $kode_pelanggan . "." . $request->file('file_doc')->getClientOriginalExtension();
                $destination_foto_path = "/public/ajuanprogramkumulatif";
                $file = $file_name;
                Storage::delete($destination_foto_path . "/" . $file_name);
            }
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function updatepelanggan(Request $request, $no_pengajuan, $kode_pelanggan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $request->validate([
            'metode_pembayaran' => 'required',
        ]);

        try {
            //code...
            $detail = Detailajuanprogramkumulatif::where('no_pengajuan', $no_pengajuan)
                ->where('kode_pelanggan', $kode_pelanggan)
                ->first();

            if ($request->file('file_doc')) {
                $file_name =  $no_pengajuan . "-" . $request->kode_pelanggan . "." . $request->file('file_doc')->getClientOriginalExtension();
                $destination_foto_path = "/public/ajuanprogramkumulatif";
                $file = $file_name;
                if ($detail->file_doc) {
                    Storage::delete($destination_foto_path . "/" . $detail->file_doc);
                }
                $request->file('file_doc')->storeAs($destination_foto_path, $file_name);
            } else {
                $file = $detail->file_doc;
            }

            Detailajuanprogramkumulatif::where('no_pengajuan', $no_pengajuan)
                ->where('kode_pelanggan', $kode_pelanggan)
                ->update([
                    'metode_pembayaran' => $request->metode_pembayaran,
                    'file_doc' => $file,
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
        $detail = Detailajuanprogramkumulatif::where('no_pengajuan', $no_pengajuan)
            ->where('kode_pelanggan', $kode_pelanggan)
            ->first();
        try {

            Detailajuanprogramkumulatif::where('no_pengajuan', $no_pengajuan)
                ->where('kode_pelanggan', $kode_pelanggan)
                ->delete();

            $destination_foto_path = "/public/ajuanprogramkumulatif";
            if ($detail->file_doc) {
                Storage::delete($destination_foto_path . "/" . $detail->file_doc);
            }
            return Redirect::back()->with(messageSuccess('Data Berhasil Di Hapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function getajuanprogramkumulatif()
    {
        $user = User::find(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $query = Ajuanprogramkumulatif::query();
        $query->join('cabang', 'marketing_program_kumulatif.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('program_kumulatif', 'marketing_program_kumulatif.kode_program', '=', 'program_kumulatif.kode_program');
        $query->orderBy('marketing_program_kumulatif.no_pengajuan', 'desc');
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('marketing_program_kumulatif.kode_cabang', auth()->user()->kode_cabang);
            }
        }
        $query->where('status', 1);
        $data['ajuanprogramkumulatif'] = $query->get();
        return view('worksheetom.ajuanprogramkumulatif.getajuanprogramkumulatif', $data);
    }

    public function destroy($no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);

        try {
            Ajuanprogramkumulatif::where('no_pengajuan', $no_pengajuan)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function approve($no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $data['programkumulatif'] = Ajuanprogramkumulatif::where('no_pengajuan', $no_pengajuan)
            ->first();
        $data['detail'] = Detailajuanprogramkumulatif::join('pelanggan', 'marketing_program_kumulatif_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->where('no_pengajuan', $no_pengajuan)
            ->get();
        return view('worksheetom.ajuanprogramkumulatif.approve', $data);
    }


    public function storeapprove(Request $request, $no_pengajuan)
    {
        $user = User::find(auth()->user()->id);
        if ($user->hasRole('operation manager')) {
            $field = 'om';
        } else if ($user->hasRole('regional sales manager')) {
            $field = 'rsm';
        } else if ($user->hasRole('gm marketing')) {
            $field = 'gm';
        } else if ($user->hasRole('direktur')) {
            $field = 'direktur';
        }


        // dd(isset($_POST['decline']));
        if (isset($_POST['decline'])) {
            $status  = 2;
        } else {
            $status = $user->hasRole('direktur') || $user->hasRole('super admin') ? 1 : 0;
        }

        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        try {
            if ($user->hasRole('super admin')) {
                Ajuanprogramkumulatif::where('no_pengajuan', $no_pengajuan)
                    ->update([
                        'status' => $status
                    ]);
            } else {
                Ajuanprogramkumulatif::where('no_pengajuan', $no_pengajuan)
                    ->update([
                        $field => auth()->user()->id,
                        'status' => $status
                    ]);
            }

            return Redirect::back()->with(messageSuccess('Data Berhasil Di Approve'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function cetak($no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $data['programkumulatif'] = Ajuanprogramkumulatif::where('no_pengajuan', $no_pengajuan)
            ->join('program_kumulatif', 'marketing_program_kumulatif.kode_program', '=', 'program_kumulatif.kode_program')
            ->first();
        $data['detail'] = Detailajuanprogramkumulatif::join('pelanggan', 'marketing_program_ikatan_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->where('no_pengajuan', $no_pengajuan)
            ->get();
        return view('worksheetom.ajuanprogramikatan.cetak', $data);
    }


    public function cetakkesepakatan($no_pengajuan, $kode_pelanggan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $data['kesepakatan'] = Detailajuanprogramkumulatif::where('marketing_program_kumulatif_detail.no_pengajuan', $no_pengajuan)
            ->where('marketing_program_kumulatif_detail.kode_pelanggan', $kode_pelanggan)
            ->join('pelanggan', 'marketing_program_kumulatif_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('salesman', 'pelanggan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('marketing_program_kumulatif', 'marketing_program_kumulatif_detail.no_pengajuan', '=', 'marketing_program_kumulatif.no_pengajuan')
            ->join('cabang', 'marketing_program_kumulatif.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();
        return view('worksheetom.ajuanprogramkumulatif.cetakkesepakatan', $data);
    }
}
