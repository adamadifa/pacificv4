<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailpenjualan;
use App\Models\MktIkatan2026;
use App\Models\MktIkatanDetail2026;
use App\Models\MktIkatanTarget2026;
use App\Models\Pelanggan;
use App\Models\Programikatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class ProgramIkatan2026Controller extends Controller
{
    public function index(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $query = MktIkatan2026::query();
        $query->join('cabang', 'mkt_ikatan_2026.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('program_ikatan', 'mkt_ikatan_2026.kode_program', '=', 'program_ikatan.kode_program');
        $query->orderBy('mkt_ikatan_2026.no_pengajuan', 'desc');

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('mkt_ikatan_2026.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        if (!empty($request->kode_cabang)) {
            $query->where('mkt_ikatan_2026.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_program)) {
            $query->where('mkt_ikatan_2026.kode_program', $request->kode_program);
        }

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('mkt_ikatan_2026.tanggal', [$request->dari, $request->sampai]);
        }

        if (!empty($request->nomor_dokumen)) {
            $query->where('mkt_ikatan_2026.nomor_dokumen', $request->nomor_dokumen);
        }

        if ($user->hasRole('regional sales manager')) {
             if ($request->status == 'pending') {
                $query->whereNotnull('mkt_ikatan_2026.om');
                $query->whereNull('mkt_ikatan_2026.rsm');
            } else if ($request->status == 'approved') {
                $query->whereNotnull('mkt_ikatan_2026.rsm');
                $query->where('status', 0);
            } else if ($request->status == 'rejected') {
                $query->where('status', 2);
            }
        } else if ($user->hasRole('gm marketing')) {
             if ($request->status == 'pending') {
                $query->whereNotnull('mkt_ikatan_2026.rsm');
                $query->whereNull('mkt_ikatan_2026.gm');
            } else if ($request->status == 'approved') {
                $query->whereNotnull('mkt_ikatan_2026.gm');
                $query->where('status', 0);
            } else if ($request->status == 'rejected') {
                $query->where('status', 2);
            }
        } else if ($user->hasRole('direktur')) {
             if ($request->status == 'pending') {
                $query->whereNotnull('mkt_ikatan_2026.gm');
                $query->whereNull('mkt_ikatan_2026.direktur');
                $query->where('status', 0);
            } else if ($request->status == 'approved') {
                $query->where('status', 1);
            } else if ($request->status == 'rejected') {
                $query->where('status', 2);
            }
        } else {
            if ($request->status == 'pending') {
                $query->where('status', 0);
            } else if ($request->status == 'approved') {
                $query->where('status', 1);
            } else if ($request->status == 'rejected') {
                $query->where('status', 2);
            }
        }

        $ajuanprogramikatan = $query->paginate(15);
        $ajuanprogramikatan->appends(request()->all());

        $cbg = new Cabang();
        $data['user'] = $user;
        $data['cabang'] = $cbg->getCabang();
        $data['programikatan'] = Programikatan::orderBy('kode_program')->get();
        $data['ajuanprogramikatan'] = $ajuanprogramikatan;
        $data['roles_access_all_cabang'] = $roles_access_all_cabang;

        return view('worksheetom.programikatan2026.index', $data);
    }

    public function create()
    {
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $data['programikatan'] = Programikatan::orderBy('kode_program')->get();
        $data['roles_access_all_cabang'] = config('global.roles_access_all_cabang');
        return view('worksheetom.programikatan2026.create', $data);
    }

    public function store(Request $request)
    {
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
            $request->validate([
                'no_dokumen' => 'required',
                'tanggal' => 'required',
                'kode_program' => 'required',
                'bulan_dari' => 'required',
                'tahun_dari' => 'required',
                'bulan_sampai' => 'required',
                'tahun_sampai' => 'required',
            ]);
        } else {
            $request->validate([
                'no_dokumen' => 'required',
                'tanggal' => 'required',
                'kode_cabang' => 'required',
                'kode_program' => 'required',
                'bulan_dari' => 'required',
                'tahun_dari' => 'required',
                'bulan_sampai' => 'required',
                'tahun_sampai' => 'required',
            ]);
            $kode_cabang = $request->kode_cabang;
        }

        $tahun = date('Y', strtotime($request->tanggal));
        $lastajuan = MktIkatan2026::select('no_pengajuan')
            ->whereRaw('YEAR(tanggal) = "' . $tahun . '"')
            ->where('kode_cabang', $kode_cabang)
            ->orderBy('no_pengajuan', 'desc')
            ->first();
        $lastno_pengajuan = $lastajuan ? $lastajuan->no_pengajuan : '';
        $no_pengajuan = buatkode($lastno_pengajuan, 'IK' . $kode_cabang . substr($tahun, 2, 2), 4);

        try {
            MktIkatan2026::create([
                'no_pengajuan' => $no_pengajuan,
                'nomor_dokumen' => $request->no_dokumen,
                'tanggal' => $request->tanggal,
                'kode_program' => $request->kode_program,
                'kode_cabang' => $kode_cabang,
                'periode_dari' => $periode_dari,
                'periode_sampai' => $periode_sampai,
            ]);
            return Redirect::route('programikatan2026.index')->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
    public function setajuanprogramikatan($no_pengajuan)
    {
        $user = User::find(auth()->user()->id);
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $programikatan = MktIkatan2026::where('no_pengajuan', $no_pengajuan)
            ->join('program_ikatan', 'mkt_ikatan_2026.kode_program', '=', 'program_ikatan.kode_program')
            ->first();
        $list_pelanggan = MktIkatanDetail2026::where('no_pengajuan', $no_pengajuan)
            ->select('mkt_ikatan_detail_2026.kode_pelanggan')
            ->get();
        $tanggal_ajuan = $programikatan->tanggal;
        $tahun = date('Y', strtotime($tanggal_ajuan));
        $tahunlalu = $tahun - 1;
        $produk = json_decode($programikatan->produk, true) ?? [];

        $dari = $tahunlalu . "-" . date('m-d', strtotime($programikatan->periode_dari));
        $sampai = $tahunlalu . "-" . date('m-d', strtotime($programikatan->periode_sampai));

        $detailpenjualan = Detailpenjualan::join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->whereIn('produk_harga.kode_produk', $produk)
            ->whereBetween('marketing_penjualan.tanggal', [$dari, $sampai])
            ->whereIn('marketing_penjualan.kode_pelanggan', $list_pelanggan)
            ->where('status_promosi', 0)
            ->where('status_batal', 0)
            ->select(
                'marketing_penjualan.kode_pelanggan',
                'nama_pelanggan',
                DB::raw('SUM(ROUND(marketing_penjualan_detail.jumlah / produk.isi_pcs_dus,2)) as qty_rata_rata'),
            )
            ->groupBy('marketing_penjualan.kode_pelanggan', 'nama_pelanggan');
        $data['programikatan'] = $programikatan;

        $data['detail'] = MktIkatanDetail2026::join('pelanggan', 'mkt_ikatan_detail_2026.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->where('mkt_ikatan_detail_2026.no_pengajuan', $no_pengajuan)
            ->join('mkt_ikatan_2026', 'mkt_ikatan_detail_2026.no_pengajuan', '=', 'mkt_ikatan_2026.no_pengajuan')
            ->leftJoinSub($detailpenjualan, 'detailpenjualan', function ($join) {
                $join->on('detailpenjualan.kode_pelanggan', '=', 'mkt_ikatan_detail_2026.kode_pelanggan');
            })
            ->select('mkt_ikatan_detail_2026.*', 'detailpenjualan.qty_rata_rata', 'pelanggan.nama_pelanggan')
            ->get();
        // dd($data['detail']);
        $data['user'] = $user;
        return view('worksheetom.programikatan2026.setajuanprogramikatan', $data);
    }

    public function tambahpelanggan($no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $ajuanprogramikatan = MktIkatan2026::where('no_pengajuan', $no_pengajuan)->first();
        $data['ajuanprogramikatan'] = $ajuanprogramikatan;

        $pelanggan = Pelanggan::where('kode_cabang', $ajuanprogramikatan->kode_cabang)->get();
        $data['pelanggan'] = $pelanggan;

        return view('worksheetom.programikatan2026.tambahpelanggan', $data);
    }

    public function editpelanggan($no_pengajuan, $kode_pelanggan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $ajuanprogramikatan = MktIkatan2026::where('no_pengajuan', $no_pengajuan)->first();
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $data['ajuanprogramikatan'] = $ajuanprogramikatan;
        $data['detail'] = MktIkatanDetail2026::where('no_pengajuan', $no_pengajuan)
            ->join('pelanggan', 'mkt_ikatan_detail_2026.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->where('mkt_ikatan_detail_2026.kode_pelanggan', $kode_pelanggan)
            ->first();
        $data['total_target_tambahan'] = MktIkatanTarget2026::where('no_pengajuan', $no_pengajuan)
            ->where('kode_pelanggan', $kode_pelanggan)
            ->sum('target_perbulan');
        $detailtarget = MktIkatanTarget2026::where('no_pengajuan', $no_pengajuan)
            ->where('kode_pelanggan', $kode_pelanggan)
            ->get();
        $array_target = [];
        foreach ($detailtarget as $d) {
            $array_target[$d->bulan . $d->tahun] = $d->target_perbulan;
        }

        $data['array_target'] = $array_target;
        return view('worksheetom.programikatan2026.editpelanggan', $data);
    }

    public function storepelanggan(Request $request, $no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $request->validate([
            'kode_pelanggan' => 'required',
            'target' => 'required',
            // 'reward' => 'required',
            'metode_pembayaran' => 'required',
            'top' => 'required',
        ]);
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $target_perbulan = $request->target_perbulan;
        $avg_perbulan = $request->avg_perbulan;
        $ajuan = MktIkatan2026::where('no_pengajuan', $no_pengajuan)->first();

        DB::beginTransaction();
        try {
            $cek = MktIkatanDetail2026::join('mkt_ikatan_2026', 'mkt_ikatan_detail_2026.no_pengajuan', '=', 'mkt_ikatan_2026.no_pengajuan')
                ->where('mkt_ikatan_2026.kode_program', $ajuan->kode_program)
                ->where('mkt_ikatan_2026.periode_dari', '<=', $ajuan->periode_sampai)
                ->where('mkt_ikatan_2026.periode_sampai', '>=', $ajuan->periode_dari)
                ->where('mkt_ikatan_detail_2026.kode_pelanggan', $request->kode_pelanggan)
                ->first();

            if ($cek) {
                return Redirect::back()->with(messageError('Pelanggan Sudah Ada'));
            }

            if ($request->file('file_doc')) {
                $file_name =  $no_pengajuan . "-" . $request->kode_pelanggan . "." . $request->file('file_doc')->getClientOriginalExtension();
                $destination_foto_path = "/public/programikatan2026";
                $file = $file_name;
                $request->file('file_doc')->storeAs($destination_foto_path, $file_name);
            } else {
                $file = null;
            }

            $grand_total_target = 0;
            for ($i = 0; $i < count($bulan); $i++) {
                $target_perbulan[$i] = toNumber($target_perbulan[$i]);
                $avg_perbulan[$i] = toNumber($avg_perbulan[$i]);
                $grand_total_target += $target_perbulan[$i] + $avg_perbulan[$i];
                $detailtarget[] = [
                    'no_pengajuan' => $no_pengajuan,
                    'kode_pelanggan' => $request->kode_pelanggan,
                    'bulan' => $bulan[$i],
                    'tahun' => $tahun[$i],
                    'avg' => $avg_perbulan[$i],
                    'target_perbulan' => toNumber($target_perbulan[$i])
                ];
            }

            MktIkatanDetail2026::create([
                'no_pengajuan' => $no_pengajuan,
                'kode_pelanggan' => $request->kode_pelanggan,
                'qty_target' => $grand_total_target,
                'qty_avg' => !empty($request->qty_avg) ? toNumber($request->qty_avg) : 0,
                'reward' => 0,
                'tipe_reward' => 0,
                'budget_smm' => 0,
                'budget_rsm' => 0,
                'budget_gm' => 0,
                'metode_pembayaran' => $request->metode_pembayaran,
                'periode_pencairan' => $request->periode_pencairan,
                'top' => $request->top,
                'file_doc' => $file
            ]);

            MktIkatanTarget2026::insert($detailtarget);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            if ($request->file('file_doc')) {
                $file_name =  $no_pengajuan . "-" . $request->kode_pelanggan . "." . $request->file('file_doc')->getClientOriginalExtension();
                $destination_foto_path = "/public/programikatan2026";
                $file = $file_name;
                Storage::delete($destination_foto_path . "/" . $file_name);
            }
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function updatepelanggan(Request $request, $no_pengajuan, $kode_pelanggan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $request->validate([
            'target' => 'required',
            // 'reward' => 'required',
            'metode_pembayaran' => 'required',
            'file_doc' => 'file|mimes:pdf|max:2048',
        ]);
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $target_perbulan = $request->target_perbulan;
        $avg_perbulan = $request->avg_perbulan;
        DB::beginTransaction();
        try {
            $detail = MktIkatanDetail2026::where('no_pengajuan', $no_pengajuan)
                ->where('kode_pelanggan', $kode_pelanggan)
                ->first();

            if ($request->file('file_doc')) {
                $file_name =  $no_pengajuan . "-" . $request->kode_pelanggan . "." . $request->file('file_doc')->getClientOriginalExtension();
                $destination_foto_path = "/public/programikatan2026";
                $file = $file_name;
                if ($detail->file_doc) {
                    Storage::delete($destination_foto_path . "/" . $detail->file_doc);
                }
                $request->file('file_doc')->storeAs($destination_foto_path, $file_name);
            } else {
                $file = $detail->file_doc;
            }

            $grand_total_target = 0;
            for ($i = 0; $i < count($bulan); $i++) {
                $target_perbulan[$i] = toNumber($target_perbulan[$i]);
                $avg_perbulan[$i] = toNumber($avg_perbulan[$i]);
                $grand_total_target += $target_perbulan[$i] + $avg_perbulan[$i];
                $detailtarget[] = [
                    'no_pengajuan' => $no_pengajuan,
                    'kode_pelanggan' => $request->kode_pelanggan,
                    'bulan' => $bulan[$i],
                    'tahun' => $tahun[$i],
                    'avg' => $avg_perbulan[$i],
                    'target_perbulan' => toNumber($target_perbulan[$i])
                ];
            }

            MktIkatanDetail2026::where('no_pengajuan', $no_pengajuan)
                ->where('kode_pelanggan', $kode_pelanggan)
                ->update([
                    'qty_target' => $grand_total_target,
                    'reward' => 0,
                    'tipe_reward' => 0,
                    'budget_smm' => 0,
                    'budget_rsm' => 0,
                    'budget_gm' => 0,
                    'metode_pembayaran' => $request->metode_pembayaran,
                    'periode_pencairan' => $request->periode_pencairan,
                    'file_doc' => $file,
                ]);

            DB::commit();
            MktIkatanTarget2026::where('no_pengajuan', $no_pengajuan)
                ->where('kode_pelanggan', $kode_pelanggan)
                ->delete();
            MktIkatanTarget2026::insert($detailtarget);
            return Redirect::back()->with(messageSuccess('Data Berhasil Di Update'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function deletepelanggan($no_pengajuan, $kode_pelanggan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $detail = MktIkatanDetail2026::where('no_pengajuan', $no_pengajuan)
            ->where('kode_pelanggan', $kode_pelanggan)
            ->first();
        try {
            MktIkatanDetail2026::where('no_pengajuan', $no_pengajuan)
                ->where('kode_pelanggan', $kode_pelanggan)
                ->delete();

            $destination_foto_path = "/public/programikatan2026";
            if ($detail->file_doc) {
                Storage::delete($destination_foto_path . "/" . $detail->file_doc);
            }
            return Redirect::back()->with(messageSuccess('Data Berhasil Di Hapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);

        try {
            MktIkatan2026::where('no_pengajuan', $no_pengajuan)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function approve($no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $programikatan = MktIkatan2026::where('no_pengajuan', $no_pengajuan)
            ->join('program_ikatan', 'mkt_ikatan_2026.kode_program', '=', 'program_ikatan.kode_program')
            ->first();
        $list_pelanggan = MktIkatanDetail2026::where('no_pengajuan', $no_pengajuan)
            ->select('mkt_ikatan_detail_2026.kode_pelanggan')
            ->get();
        $tanggal_ajuan = $programikatan->tanggal;
        $tahun = date('Y', strtotime($tanggal_ajuan));
        $tahunlalu = $tahun - 1;
        $produk = json_decode($programikatan->produk, true) ?? [];

        $dari = $tahunlalu . "-" . date('m-d', strtotime($programikatan->periode_dari));
        $sampai = $tahunlalu . "-" . date('m-d', strtotime($programikatan->periode_sampai));

        $detailpenjualan = Detailpenjualan::join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->whereIn('produk_harga.kode_produk', $produk)
            ->whereBetween('marketing_penjualan.tanggal', [$dari, $sampai])
            ->whereIn('marketing_penjualan.kode_pelanggan', $list_pelanggan)
            ->where('status_promosi', 0)
            ->where('status_batal', 0)
            ->select(
                'marketing_penjualan.kode_pelanggan',
                'nama_pelanggan',
                DB::raw('SUM(ROUND(marketing_penjualan_detail.jumlah / produk.isi_pcs_dus,2)) as qty_rata_rata'),
            )
            ->groupBy('marketing_penjualan.kode_pelanggan', 'nama_pelanggan');
        $data['programikatan'] = $programikatan;

        $data['detail'] = MktIkatanDetail2026::join('pelanggan', 'mkt_ikatan_detail_2026.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->where('mkt_ikatan_detail_2026.no_pengajuan', $no_pengajuan)
            ->join('mkt_ikatan_2026', 'mkt_ikatan_detail_2026.no_pengajuan', '=', 'mkt_ikatan_2026.no_pengajuan')
            ->leftJoinSub($detailpenjualan, 'detailpenjualan', function ($join) {
                $join->on('detailpenjualan.kode_pelanggan', '=', 'mkt_ikatan_detail_2026.kode_pelanggan');
            })
            ->select('mkt_ikatan_detail_2026.*', 'detailpenjualan.qty_rata_rata', 'pelanggan.nama_pelanggan')
            ->get();
        return view('worksheetom.programikatan2026.approve', $data);
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

        if (isset($_POST['decline'])) {
            $status  = 2;
        } else {
            $status = $user->hasRole('direktur') || $user->hasRole('super admin') ? 1 : 0;
        }

        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        try {
            if ($user->hasRole('super admin')) {
                MktIkatan2026::where('no_pengajuan', $no_pengajuan)
                    ->update([
                        'status' => $status
                    ]);
            } else {
                MktIkatan2026::where('no_pengajuan', $no_pengajuan)
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
        $programikatan = MktIkatan2026::where('no_pengajuan', $no_pengajuan)
            ->join('program_ikatan', 'mkt_ikatan_2026.kode_program', '=', 'program_ikatan.kode_program')
            ->first();
        $list_pelanggan = MktIkatanDetail2026::where('no_pengajuan', $no_pengajuan)
            ->select('mkt_ikatan_detail_2026.kode_pelanggan')
            ->get();
        $tanggal_ajuan = $programikatan->tanggal;
        $tahun = date('Y', strtotime($tanggal_ajuan));
        $tahunlalu = $tahun - 1;
        $produk = json_decode($programikatan->produk, true) ?? [];

        $dari = $tahunlalu . "-" . date('m-d', strtotime($programikatan->periode_dari));
        $sampai = $tahunlalu . "-" . date('m-d', strtotime($programikatan->periode_sampai));

        $detailpenjualan = Detailpenjualan::join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->whereIn('produk_harga.kode_produk', $produk)
            ->whereBetween('marketing_penjualan.tanggal', [$dari, $sampai])
            ->whereIn('marketing_penjualan.kode_pelanggan', $list_pelanggan)
            ->where('status_promosi', 0)
            ->where('status_batal', 0)
            ->select(
                'marketing_penjualan.kode_pelanggan',
                'nama_pelanggan',
                DB::raw('SUM(ROUND(marketing_penjualan_detail.jumlah / produk.isi_pcs_dus,2)) as qty_rata_rata'),
            )
            ->groupBy('marketing_penjualan.kode_pelanggan', 'nama_pelanggan');
        $data['programikatan'] = $programikatan;

        $data['detail'] = MktIkatanDetail2026::join('pelanggan', 'mkt_ikatan_detail_2026.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->where('mkt_ikatan_detail_2026.no_pengajuan', $no_pengajuan)
            ->join('mkt_ikatan_2026', 'mkt_ikatan_detail_2026.no_pengajuan', '=', 'mkt_ikatan_2026.no_pengajuan')
            ->leftJoinSub($detailpenjualan, 'detailpenjualan', function ($join) {
                $join->on('detailpenjualan.kode_pelanggan', '=', 'mkt_ikatan_detail_2026.kode_pelanggan');
            })
            ->select('mkt_ikatan_detail_2026.*', 'detailpenjualan.qty_rata_rata', 'pelanggan.nama_pelanggan')
            ->get();
        return view('worksheetom.programikatan2026.cetak', $data);
    }

    public function cetakkesepakatan($no_pengajuan, $kode_pelanggan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $data['kesepakatan'] = MktIkatanDetail2026::where('mkt_ikatan_detail_2026.no_pengajuan', $no_pengajuan)
            ->where('mkt_ikatan_detail_2026.kode_pelanggan', $kode_pelanggan)
            ->join('pelanggan', 'mkt_ikatan_detail_2026.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('salesman', 'pelanggan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('mkt_ikatan_2026', 'mkt_ikatan_detail_2026.no_pengajuan', '=', 'mkt_ikatan_2026.no_pengajuan')
            ->join('program_ikatan', 'mkt_ikatan_2026.kode_program', '=', 'program_ikatan.kode_program')
            ->join('cabang', 'mkt_ikatan_2026.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();
        $data['detailtarget'] = MktIkatanTarget2026::where('no_pengajuan', $no_pengajuan)
            ->where('kode_pelanggan', $kode_pelanggan)
            ->get();
        return view('worksheetom.programikatan2026.cetakkesepakatan', $data);
    }

    public function detailtarget($no_pengajuan, $kode_pelanggan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);

        $data['programikatan'] = MktIkatan2026::where('no_pengajuan', $no_pengajuan)
            ->join('program_ikatan', 'mkt_ikatan_2026.kode_program', '=', 'program_ikatan.kode_program')
            ->first();
        $data['detailtarget'] = MktIkatanTarget2026::where('no_pengajuan', $no_pengajuan)
            ->where('kode_pelanggan', $kode_pelanggan)
            ->get();
        return view('worksheetom.programikatan2026.detailtarget', $data);
    }

    public function getAvgpelanggan($kode_pelanggan, $kode_program)
    {
        $kode_program = Crypt::decrypt($kode_program);
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $programikatan = MktIkatan2026::where('mkt_ikatan_2026.kode_program', $kode_program)
            ->join('program_ikatan', 'mkt_ikatan_2026.kode_program', '=', 'program_ikatan.kode_program')
            ->first();
        $bulan = date('m', strtotime($programikatan->periode_dari));
        $tahun = date('Y', strtotime($programikatan->periode_dari));
        $lastbulan = getbulandantahunlalu($bulan, $tahun, "bulan");
        $lasttahun = getbulandantahunlalu($bulan, $tahun, "tahun");
        $dari_lastbulan = $lasttahun . "-" . $lastbulan . "-01";


        $lastduabulan = getbulandantahunlalu($lastbulan, $lasttahun, "bulan");
        $lastduabulantahun = getbulandantahunlalu($lastbulan, $lasttahun, "tahun");

        $lastigabulan = getbulandantahunlalu($lastduabulan, $lastduabulantahun, "bulan");
        $lasttigabulantahun = getbulandantahunlalu($lastduabulan, $lastduabulantahun, "tahun");



        $dari_lasttigabulan = $lasttigabulantahun . "-" . $lastigabulan . "-01";
        $sampai_lastbulan = date('Y-m-t', strtotime($dari_lastbulan));

        $tahunlalu = $tahun - 1;
        $produk = json_decode($programikatan->produk, true) ?? [];
        $detailpenjualan = Detailpenjualan::join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->whereIn('produk_harga.kode_produk', $produk)
            ->where('marketing_penjualan.kode_pelanggan', $kode_pelanggan)
            // ->whereBetween('marketing_penjualan.tanggal', [$dari_lasttigabulan, $sampai_lastbulan])
            ->whereRaw('YEAR(marketing_penjualan.tanggal)="' . $tahunlalu . '"')
            ->where('status_promosi', 0)
            ->where('status_batal', 0)
            ->select(
                'marketing_penjualan.kode_pelanggan',
                'nama_pelanggan',
                DB::raw('SUM(FLOOR(marketing_penjualan_detail.jumlah / produk.isi_pcs_dus)) as qty'),
                DB::raw('MIN(MONTH(marketing_penjualan.tanggal)) as bulan_awal')
            )
            ->groupBy('marketing_penjualan.kode_pelanggan', 'nama_pelanggan')
            ->first();

        if ($detailpenjualan != null) {
            $pembagi = 13 - $detailpenjualan->bulan_awal;
            $detailpenjualan->qty = ceil($detailpenjualan->qty / $pembagi);
            return response()->json([
                'success' => true,
                'message' => 'Detail Pelanggan',
                'type'    => 1,
                'data'    => $detailpenjualan
            ]);
        } else {
            $pelanggan = Pelanggan::where('kode_pelanggan', $kode_pelanggan)->first();
            return response()->json([
                'success' => true,
                'message' => 'Detail Pelanggan',
                'type'    => 2,
                'data'    => $pelanggan
            ]);
        }
    }

    public function gethistoripelangganprogram($kode_pelanggan, $kode_program)
    {
        $kode_program = Crypt::decrypt($kode_program);
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $programikatan = MktIkatan2026::where('mkt_ikatan_2026.kode_program', $kode_program)
            ->join('program_ikatan', 'mkt_ikatan_2026.kode_program', '=', 'program_ikatan.kode_program')
            ->first();
        $bulan = date('m', strtotime($programikatan->periode_dari));
        $tahun = date('Y', strtotime($programikatan->periode_dari));
        $lastbulan = getbulandantahunlalu($bulan, $tahun, "bulan");
        $lasttahun = getbulandantahunlalu($bulan, $tahun, "tahun");
        $dari_lastbulan = $lasttahun . "-" . $lastbulan . "-01";


        $lastduabulan = getbulandantahunlalu($lastbulan, $lasttahun, "bulan");
        $lastduabulantahun = getbulandantahunlalu($lastbulan, $lasttahun, "tahun");

        $lastigabulan = getbulandantahunlalu($lastduabulan, $lastduabulantahun, "bulan");
        $lasttigabulantahun = getbulandantahunlalu($lastduabulan, $lastduabulantahun, "tahun");



        $dari_lasttigabulan = $lasttigabulantahun . "-" . $lastigabulan . "-01";
        $sampai_lastbulan = date('Y-m-t', strtotime($dari_lastbulan));

        $tahunlalu = $tahun - 1;
        $produk = json_decode($programikatan->produk, true) ?? [];
        $detailpenjualan = Detailpenjualan::join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->whereIn('produk_harga.kode_produk', $produk)
            ->where('marketing_penjualan.kode_pelanggan', $kode_pelanggan)
            // ->whereBetween('marketing_penjualan.tanggal', [$dari_lasttigabulan, $sampai_lastbulan])
            ->whereRaw('YEAR(marketing_penjualan.tanggal)="' . $tahunlalu . '"')
            ->where('status_promosi', 0)
            ->where('status_batal', 0)
            ->select(
                'marketing_penjualan.no_faktur',
                'marketing_penjualan.tanggal',
                DB::raw('SUM(FLOOR(marketing_penjualan_detail.jumlah / produk.isi_pcs_dus)) as qty'),
            )
            ->groupBy('marketing_penjualan.no_faktur', 'marketing_penjualan.tanggal')
            ->get();

        return view('datamaster.pelanggan.gethistoripelangganprogram', compact('detailpenjualan'));
    }
}
