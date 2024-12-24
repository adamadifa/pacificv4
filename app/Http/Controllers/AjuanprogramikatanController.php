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
use Illuminate\Support\Facades\Storage;

class AjuanprogramikatanController extends Controller
{
    public function index(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $query = Ajuanprogramikatan::query();
        $query->join('cabang', 'marketing_program_ikatan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('program_ikatan', 'marketing_program_ikatan.kode_program', '=', 'program_ikatan.kode_program');
        $query->orderBy('marketing_program_ikatan.no_pengajuan', 'desc');
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('marketing_program_ikatan.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        if (!empty($request->kode_cabang)) {
            $query->where('marketing_program_ikatan.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_program)) {
            $query->where('marketing_program_ikatan.kode_program', $request->kode_program);
        }

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('marketing_program_ikatan.tanggal', [$request->dari, $request->sampai]);
        }

        if (!empty($request->nomor_dokumen)) {
            $query->where('marketing_program_ikatan.nomor_dokumen', $request->nomor_dokumen);
        }

        if ($user->hasRole('regional sales manager')) {
            $query->whereNotNull('marketing_program_ikatan.om');
            $query->where('marketing_program_ikatan.status', '!=', 2);
        }

        if ($user->hasRole('gm marketing')) {
            $query->whereNotNull('marketing_program_ikatan.rsm');
            $query->where('marketing_program_ikatan.status', '!=', 2);
        }

        if ($user->hasRole('direktur')) {
            $query->whereNotNull('marketing_program_ikatan.gm');
            $query->where('marketing_program_ikatan.status', '!=', 2);
        }
        $ajuanprogramikatan = $query->paginate(15);
        $ajuanprogramikatan->appends(request()->all());
        $data['ajuanprogramikatan'] = $ajuanprogramikatan;

        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();

        $data['programikatan'] = Programikatan::orderBy('kode_program')->get();
        return view('worksheetom.ajuanprogramikatan.index', $data);
    }

    public function create()
    {
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
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
            'bulan_dari' => 'required',
            'tahun_dari' => 'required',
            'bulan_sampai' => 'required',
            'tahun_sampai' => 'required',
            'keterangan' => 'required',

        ]);

        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);
        $periode_dari = $request->tahun_dari . '-' . $request->bulan_dari . '-01';
        $sampai = $request->tahun_sampai . '-' . $request->bulan_sampai . '-01';
        $periode_sampai = date('Y-m-t', strtotime($sampai));

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
                'periode_dari' => $periode_dari,
                'periode_sampai' => $periode_sampai,
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
            'metode_pembayaran' => 'required',
            'top' => 'required',

        ]);

        try {
            //code...
            $cek = Detailajuanprogramikatan::where('no_pengajuan', $no_pengajuan)
                ->where('kode_pelanggan', $request->kode_pelanggan)
                ->first();

            if ($cek) {
                return Redirect::back()->with(messageError('Pelanggan Sudah Ada'));
            }

            if ($request->file('file_doc')) {
                $file_name =  $no_pengajuan . "-" . $request->kode_pelanggan . "." . $request->file('file_doc')->getClientOriginalExtension();
                $destination_foto_path = "/public/ajuanprogramikatan";
                $file = $file_name;
                $request->file('file_doc')->storeAs($destination_foto_path, $file_name);
            } else {
                $file = null;
            }

            Detailajuanprogramikatan::create([
                'no_pengajuan' => $no_pengajuan,
                'kode_pelanggan' => $request->kode_pelanggan,
                'qty_target' => toNumber($request->target),
                'qty_avg' => !empty($request->qty_avg) ? toNumber($request->qty_avg) : 0,
                'reward' => toNumber($request->reward),
                'budget_smm' => toNumber($request->budget_smm),
                'budget_rsm' => toNumber($request->budget_rsm),
                'budget_gm' => toNumber($request->budget_gm),
                'metode_pembayaran' => $request->metode_pembayaran,
                'top' => $request->top,
                'file_doc' => $file

            ]);


            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            if ($request->file('file_doc')) {
                $file_name =  $no_pengajuan . "-" . $request->kode_pelanggan . "." . $request->file('file_doc')->getClientOriginalExtension();
                $destination_foto_path = "/public/ajuanprogramikatan";
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
            'target' => 'required',
            'reward' => 'required',
            'metode_pembayaran' => 'required',
            'file_doc' => 'file|mimes:pdf|max:2048',
        ]);

        try {
            //code...
            $detail = Detailajuanprogramikatan::where('no_pengajuan', $no_pengajuan)
                ->where('kode_pelanggan', $kode_pelanggan)
                ->first();

            if ($request->file('file_doc')) {
                $file_name =  $no_pengajuan . "-" . $request->kode_pelanggan . "." . $request->file('file_doc')->getClientOriginalExtension();
                $destination_foto_path = "/public/ajuanprogramikatan";
                $file = $file_name;
                if ($detail->file_doc) {
                    Storage::delete($destination_foto_path . "/" . $detail->file_doc);
                }
                $request->file('file_doc')->storeAs($destination_foto_path, $file_name);
            } else {
                $file = $detail->file_doc;
            }

            Detailajuanprogramikatan::where('no_pengajuan', $no_pengajuan)
                ->where('kode_pelanggan', $kode_pelanggan)
                ->update([
                    'qty_target' => toNumber($request->target),
                    'reward' => toNumber($request->reward),
                    'budget_smm' => toNumber($request->budget_smm),
                    'budget_rsm' => toNumber($request->budget_rsm),
                    'budget_gm' => toNumber($request->budget_gm),
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
        $detail = Detailajuanprogramikatan::where('no_pengajuan', $no_pengajuan)
            ->where('kode_pelanggan', $kode_pelanggan)
            ->first();
        try {

            Detailajuanprogramikatan::where('no_pengajuan', $no_pengajuan)
                ->where('kode_pelanggan', $kode_pelanggan)
                ->delete();

            $destination_foto_path = "/public/ajuanprogramikatan";
            Storage::delete($destination_foto_path . "/" . $detail->file_doc);
            return Redirect::back()->with(messageSuccess('Data Berhasil Di Hapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function getajuanprogramikatan()
    {
        $user = User::find(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $query = Ajuanprogramikatan::query();
        $query->join('cabang', 'marketing_program_ikatan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('program_ikatan', 'marketing_program_ikatan.kode_program', '=', 'program_ikatan.kode_program');
        $query->orderBy('marketing_program_ikatan.no_pengajuan', 'desc');
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('marketing_program_ikatan.kode_cabang', auth()->user()->kode_cabang);
            }
        }
        $query->where('status', 1);
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

    public function approve($no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $data['programikatan'] = Ajuanprogramikatan::where('no_pengajuan', $no_pengajuan)
            ->join('program_ikatan', 'marketing_program_ikatan.kode_program', '=', 'program_ikatan.kode_program')
            ->first();
        $data['detail'] = Detailajuanprogramikatan::join('pelanggan', 'marketing_program_ikatan_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->where('no_pengajuan', $no_pengajuan)
            ->get();
        return view('worksheetom.ajuanprogramikatan.approve', $data);
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
                Ajuanprogramikatan::where('no_pengajuan', $no_pengajuan)
                    ->update([
                        'status' => $status
                    ]);
            } else {
                Ajuanprogramikatan::where('no_pengajuan', $no_pengajuan)
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
        $data['programikatan'] = Ajuanprogramikatan::where('no_pengajuan', $no_pengajuan)
            ->join('program_ikatan', 'marketing_program_ikatan.kode_program', '=', 'program_ikatan.kode_program')
            ->first();
        $data['detail'] = Detailajuanprogramikatan::join('pelanggan', 'marketing_program_ikatan_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->where('no_pengajuan', $no_pengajuan)
            ->get();
        return view('worksheetom.ajuanprogramikatan.cetak', $data);
    }


    public function cetakkesepakatan($no_pengajuan, $kode_pelanggan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $data['kesepakatan'] = Detailajuanprogramikatan::where('marketing_program_ikatan_detail.no_pengajuan', $no_pengajuan)
            ->where('marketing_program_ikatan_detail.kode_pelanggan', $kode_pelanggan)
            ->join('pelanggan', 'marketing_program_ikatan_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('marketing_program_ikatan', 'marketing_program_ikatan_detail.no_pengajuan', '=', 'marketing_program_ikatan.no_pengajuan')
            ->join('program_ikatan', 'marketing_program_ikatan.kode_program', '=', 'program_ikatan.kode_program')
            ->join('cabang', 'marketing_program_ikatan.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();
        return view('worksheetom.ajuanprogramikatan.cetakkesepakatan', $data);
    }
}
