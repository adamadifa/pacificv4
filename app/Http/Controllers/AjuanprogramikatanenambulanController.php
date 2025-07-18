<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Programikatan;
use App\Models\Ajuanprogramikatanenambulan;
use App\Models\Detailajuanprogramikatanenambulan;
use App\Models\Detailpenjualan;
use App\Models\Detailtargetikatan;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class AjuanprogramikatanenambulanController extends Controller
{
    public function index(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $query = Ajuanprogramikatanenambulan::query();
        $query->join('cabang', 'marketing_program_ikatan_enambulan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('program_ikatan', 'marketing_program_ikatan_enambulan.kode_program', '=', 'program_ikatan.kode_program');
        // $query->orderBy('marketing_program_ikatan_enambulan.no_pengajuan', 'desc');
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('marketing_program_ikatan_enambulan.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        if (!empty($request->kode_cabang)) {
            $query->where('marketing_program_ikatan_enambulan.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_program)) {
            $query->where('marketing_program_ikatan_enambulan.kode_program', $request->kode_program);
        }

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('marketing_program_ikatan_enambulan.tanggal', [$request->dari, $request->sampai]);
        }

        if (!empty($request->nomor_dokumen)) {
            $query->where('marketing_program_ikatan_enambulan.nomor_dokumen', $request->nomor_dokumen);
        }

        if ($user->hasRole('regional sales manager')) {
            if (!empty($request->status)) {
                if ($request->status == 'pending') {
                    $query->whereNotnull('marketing_program_ikatan_enambulan.om');
                    $query->whereNull('marketing_program_ikatan_enambulan.rsm');
                } else if ($request->status == 'approved') {
                    $query->whereNotnull('marketing_program_ikatan_enambulan.rsm');
                    $query->where('status', 0);
                } else if ($request->status == 'rejected') {
                    $query->where('status', 2);
                }
            }
            $query->whereNotNull('marketing_program_ikatan_enambulan.om');
            // $query->where('marketing_program_ikatan.status', '!=', 2);
        } else if ($user->hasRole('gm marketing')) {
            if (!empty($request->status)) {
                if ($request->status == 'pending') {
                    $query->whereNotnull('marketing_program_ikatan_enambulan.rsm');
                    $query->whereNull('marketing_program_ikatan_enambulan.gm');
                } else if ($request->status == 'approved') {
                    $query->whereNotnull('marketing_program_ikatan_enambulan.gm');
                    $query->where('status', 0);
                } else if ($request->status == 'rejected') {
                    $query->where('status', 2);
                }
            }
            $query->whereNotNull('marketing_program_ikatan_enambulan.rsm');
            // $query->where('marketing_program_ikatan.status', '!=', 2);
        } else if ($user->hasRole('direktur')) {

            if (!empty($request->status)) {
                if ($request->status == 'pending') {
                    $query->whereNotnull('marketing_program_ikatan_enambulan.gm');
                    $query->whereNull('marketing_program_ikatan_enambulan.direktur');
                    $query->where('status', 0);
                } else if ($request->status == 'approved') {
                    $query->where('status', 1);
                } else if ($request->status == 'rejected') {
                    $query->where('status', 2);
                }
            }
            $query->whereNotNull('marketing_program_ikatan_enambulan.gm');
            // $query->where('marketing_program_ikatan.status', '!=', 2);
        } else {
            if ($request->status == 'pending') {
                $query->where('status', 0);
            } else if ($request->status == 'approved') {
                $query->where('status', 1);
            } else if ($request->status == 'rejected') {
                $query->where('status', 2);
            }
        }
        $query->orderBy('marketing_program_ikatan_enambulan.tanggal', 'desc');
        $ajuanprogramikatan = $query->paginate(15);
        $ajuanprogramikatan->appends(request()->all());
        $data['ajuanprogramikatan'] = $ajuanprogramikatan;

        $cbg = new Cabang();
        $data['user'] = $user;
        $data['cabang'] = $cbg->getCabang();
        $data['programikatan'] = Programikatan::orderBy('kode_program')->get();
        return view('worksheetom.ajuanprogramikatanenambulan.index', $data);
    }


    public function create()
    {
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $data['programikatan'] = Programikatan::orderBy('kode_program')->get();
        return view('worksheetom.ajuanprogramikatanenambulan.create', $data);
    }

    public function  store(Request $request)
    {

        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);
        if ($request->semester == '1') {
            $periode_dari = $request->tahun . '-01-01';
            $sampai = $request->tahun . '-06-01';
        } else {
            $periode_dari = $request->tahun . '-07-01';
            $sampai = $request->tahun . '-12-01';
        }
        // $periode_dari = $request->tahun_dari . '-' . $request->bulan_dari . '-01';
        // $sampai = $request->tahun_sampai . '-' . $request->bulan_sampai . '-01';
        $periode_sampai = date('Y-m-t', strtotime($sampai));

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
            $request->validate([
                'tanggal' => 'required',
                'kode_program' => 'required',
                'semester' => 'required',
                'tahun' => 'required',
                'keterangan' => 'required',
                'periode_pencairan' => 'required',

            ]);
        } else {
            $request->validate([
                'tanggal' => 'required',
                'kode_cabang' => 'required',
                'kode_program' => 'required',
                'semester' => 'required',
                'tahun' => 'required',
                'keterangan' => 'required',
                'periode_pencairan' => 'required',

            ]);
            $kode_cabang = $request->kode_cabang;
        }


        $tahun = date('Y', strtotime($request->tanggal));
        $lastajuan = Ajuanprogramikatanenambulan::select('no_pengajuan')
            ->whereRaw('YEAR(tanggal) = "' . $tahun . '"')
            ->where('kode_cabang', $kode_cabang)
            ->orderBy('no_pengajuan', 'desc')
            ->first();
        $lastno_pengajuan = $lastajuan ? $lastajuan->no_pengajuan : '';
        $no_pengajuan = buatkode($lastno_pengajuan, 'IK' . $kode_cabang . substr($tahun, 2, 2), 4);




        try {
            Ajuanprogramikatanenambulan::create([
                'no_pengajuan' => $no_pengajuan,
                'tanggal' => $request->tanggal,
                'kode_program' => $request->kode_program,
                'kode_cabang' => $kode_cabang,
                'periode_dari' => $periode_dari,
                'periode_sampai' => $periode_sampai,
                'periode_pencairan' => $request->periode_pencairan,
                'status' => 0,
                // 'keterangan' => $request->keterangan,
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function setajuanprogramenambulan($no_pengajuan)
    {
        $user = User::find(auth()->user()->id);
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $programikatan = Ajuanprogramikatanenambulan::where('no_pengajuan', $no_pengajuan)
            ->join('program_ikatan', 'marketing_program_ikatan_enambulan.kode_program', '=', 'program_ikatan.kode_program')
            ->first();
        $list_pelanggan = Detailajuanprogramikatanenambulan::where('no_pengajuan', $no_pengajuan)
            ->select('marketing_program_ikatan_enambulan_detail.kode_pelanggan')
            ->get();
        $tanggal_ajuan = $programikatan->tanggal;
        $tahun = date('Y', strtotime($tanggal_ajuan));
        $tahunlalu = $tahun - 1;
        $produk = json_decode($programikatan->produk, true) ?? [];

        $dari = $tahunlalu . "-" . date('m-d', strtotime($programikatan->periode_dari));
        $sampai = $tahunlalu . "-" . date('m-d', strtotime($programikatan->periode_sampai));



        $data['detail'] = Detailajuanprogramikatanenambulan::join('pelanggan', 'marketing_program_ikatan_enambulan_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('marketing_program_ikatan_detail', function ($join) {
                $join->on('marketing_program_ikatan_enambulan_detail.no_pengajuan_programikatan', '=', 'marketing_program_ikatan_detail.no_pengajuan')
                    ->on('marketing_program_ikatan_enambulan_detail.kode_pelanggan', '=', 'marketing_program_ikatan_detail.kode_pelanggan');
            })
            ->where('marketing_program_ikatan_enambulan_detail.no_pengajuan', $no_pengajuan)

            ->select(
                'marketing_program_ikatan_enambulan_detail.*',
                'pelanggan.nama_pelanggan',
                'metode_pembayaran',
                'top',
                'qty_target',
                'reward',
                'budget_smm',
                'budget_rsm',
                'budget_gm',
                'file_doc'
            )
            ->get();

        $data['user'] = $user;
        $data['programikatan'] = $programikatan;
        return view('worksheetom.ajuanprogramikatanenambulan.setajuanprogramikatan', $data);
    }

    public function tambahpelanggan($no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $ajuanprogramikatan = Ajuanprogramikatanenambulan::where('no_pengajuan', $no_pengajuan)
            ->join('program_ikatan', 'marketing_program_ikatan_enambulan.kode_program', '=', 'program_ikatan.kode_program')
            ->first();
        $produk = json_decode($ajuanprogramikatan->produk, true) ?? [];
        $data['ajuanprogramikatan'] = $ajuanprogramikatan;

        // $start_date = $ajuanprogramikatan->periode_dari;
        // $end_date = $ajuanprogramikatan->periode_sampai;
        // $bulan = date('m') != '01' ? date('m') - 1 : 1;
        // $tahun = date('Y');


        // $listpelangganikatan = Detailtargetikatan::select(
        //     'marketing_program_ikatan_target.kode_pelanggan',
        //     'marketing_program_ikatan_detail.top'
        // )
        //     ->join('pelanggan', 'marketing_program_ikatan_target.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
        //     ->join('marketing_program_ikatan_detail', function ($join) {
        //         $join->on('marketing_program_ikatan_target.no_pengajuan', '=', 'marketing_program_ikatan_detail.no_pengajuan')
        //             ->on('marketing_program_ikatan_target.kode_pelanggan', '=', 'marketing_program_ikatan_detail.kode_pelanggan');
        //     })
        //     ->join('marketing_program_ikatan', 'marketing_program_ikatan_detail.no_pengajuan', '=', 'marketing_program_ikatan.no_pengajuan')
        //     ->where('marketing_program_ikatan.status', 1)
        //     ->where('marketing_program_ikatan.kode_program', $ajuanprogramikatan->kode_program)
        //     ->where('marketing_program_ikatan.kode_cabang', $ajuanprogramikatan->kode_cabang)
        //     ->groupBy('marketing_program_ikatan_target.kode_pelanggan', 'marketing_program_ikatan_detail.top');

        // $detailpenjualan_bulanlalu = Detailpenjualan::select(
        //     'marketing_penjualan.kode_pelanggan',
        //     DB::raw('MONTH(marketing_penjualan.tanggal) as bulan'),
        //     DB::raw('SUM(floor(jumlah/isi_pcs_dus)) as jml_dus'),
        // )
        //     ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
        //     ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
        //     ->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
        //     ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
        //     ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
        //     ->joinSub($listpelangganikatan, 'listpelangganikatan', function ($join) {
        //         $join->on('marketing_penjualan.kode_pelanggan', '=', 'listpelangganikatan.kode_pelanggan');
        //     })
        //     ->whereBetween('marketing_penjualan.tanggal', [$start_date, $end_date])
        //     ->where('salesman.kode_cabang', $ajuanprogramikatan->kode_cabang)
        //     ->where('marketing_penjualan.status', 1)
        //     ->whereRaw("datediff(marketing_penjualan.tanggal_pelunasan, marketing_penjualan.tanggal) <= listpelangganikatan.top + 3")
        //     ->where('status_batal', 0)
        //     ->whereIn('produk_harga.kode_produk', $produk)
        //     // ->whereNotIn('marketing_penjualan.kode_pelanggan', function ($query) use ($pencairanprogram) {
        //     //     $query->select('kode_pelanggan')
        //     //         ->from('marketing_pencairan_ikatan_detail')
        //     //         ->join('marketing_pencairan_ikatan', 'marketing_pencairan_ikatan_detail.kode_pencairan', '=', 'marketing_pencairan_ikatan.kode_pencairan')
        //     //         ->where('bulan', $pencairanprogram->bulan)
        //     //         ->where('tahun', $pencairanprogram->tahun);
        //     // })
        //     ->groupBy('marketing_penjualan.kode_pelanggan', DB::raw('MONTH(marketing_penjualan.tanggal)'));

        // $peserta_gagal = Detailtargetikatan::select(
        //     'marketing_program_ikatan_target.kode_pelanggan',


        // )
        //     ->join('pelanggan', 'marketing_program_ikatan_target.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
        //     ->join('marketing_program_ikatan_detail', function ($join) {
        //         $join->on('marketing_program_ikatan_target.no_pengajuan', '=', 'marketing_program_ikatan_detail.no_pengajuan')
        //             ->on('marketing_program_ikatan_target.kode_pelanggan', '=', 'marketing_program_ikatan_detail.kode_pelanggan');
        //     })
        //     ->leftJoinSub($detailpenjualan_bulanlalu, 'detailpenjualan', function ($join) {
        //         $join->on('marketing_program_ikatan_target.kode_pelanggan', '=', 'detailpenjualan.kode_pelanggan');
        //         $join->on('marketing_program_ikatan_target.bulan', '=', 'detailpenjualan.bulan');
        //     })
        //     ->join('marketing_program_ikatan', 'marketing_program_ikatan_detail.no_pengajuan', '=', 'marketing_program_ikatan.no_pengajuan')

        //     ->where('marketing_program_ikatan.status', 1)
        //     ->where('marketing_program_ikatan.kode_program', $ajuanprogramikatan->kode_program)
        //     ->where('marketing_program_ikatan_target.bulan', '<=', $bulan)
        //     ->where('marketing_program_ikatan_target.tahun', $tahun)
        //     ->where('marketing_program_ikatan.kode_cabang', $ajuanprogramikatan->kode_cabang)
        //     ->whereRaw('IFNULL(jml_dus,0) < target_perbulan');


        // $pelanggan = Pelanggan::where('kode_cabang', $ajuanprogramikatan->kode_cabang)
        //     ->whereIn('kode_pelanggan', $peserta_gagal)
        //     ->get();
        // $data['pelanggan'] = $pelanggan;


        return view('worksheetom.ajuanprogramikatanenambulan.tambahpelanggan', $data);
    }

    public function storepelanggan(Request $request, $no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $kode_pelanggan = Crypt::decrypt($request->kode_pelanggan);
        $no_pengajuan_programikatan = $request->no_pengajuan_programikatan;
        $request->validate([
            'kode_pelanggan' => 'required',
        ]);


        $ajuan = Ajuanprogramikatanenambulan::where('no_pengajuan', $no_pengajuan)->first();


        DB::beginTransaction();
        try {
            //code...
            $cek = Detailajuanprogramikatanenambulan::join('marketing_program_ikatan_enambulan', 'marketing_program_ikatan_enambulan_detail.no_pengajuan', '=', 'marketing_program_ikatan_enambulan.no_pengajuan')
                ->where('marketing_program_ikatan_enambulan.kode_program', $ajuan->kode_program)
                ->where('marketing_program_ikatan_enambulan_detail.kode_pelanggan', $kode_pelanggan)
                ->where('marketing_program_ikatan_enambulan.periode_dari', $ajuan->periode_dari)
                ->where('marketing_program_ikatan_enambulan.periode_sampai', $ajuan->periode_sampai)
                ->first();

            if ($cek) {
                return Redirect::back()->with(messageError('Pelanggan Sudah Ada'));
            }



            Detailajuanprogramikatanenambulan::create([
                'no_pengajuan' => $no_pengajuan,
                'no_pengajuan_programikatan' => $no_pengajuan_programikatan,
                'kode_pelanggan' => $kode_pelanggan,


            ]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {

            DB::rollBack();
            dd($e->getMessage());
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);

        try {
            Ajuanprogramikatanenambulan::where('no_pengajuan', $no_pengajuan)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function deletepelanggan($no_pengajuan, $kode_pelanggan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        //dd($no_pengajuan, $kode_pelanggan);
        try {

            Detailajuanprogramikatanenambulan::where('no_pengajuan', $no_pengajuan)
                ->where('kode_pelanggan', $kode_pelanggan)
                ->delete();

            return Redirect::back()->with(messageSuccess('Data Berhasil Di Hapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function cetak($no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $programikatan = Ajuanprogramikatanenambulan::where('no_pengajuan', $no_pengajuan)
            ->join('program_ikatan', 'marketing_program_ikatan_enambulan.kode_program', '=', 'program_ikatan.kode_program')
            ->first();
        $list_pelanggan = Detailajuanprogramikatanenambulan::where('no_pengajuan', $no_pengajuan)
            ->select('marketing_program_ikatan_enambulan_detail.kode_pelanggan')
            ->get();
        $tanggal_ajuan = $programikatan->tanggal;
        $tahun = date('Y', strtotime($tanggal_ajuan));
        $tahunlalu = $tahun - 1;
        $produk = json_decode($programikatan->produk, true) ?? [];

        $dari = $tahunlalu . "-" . date('m-d', strtotime($programikatan->periode_dari));
        $sampai = $tahunlalu . "-" . date('m-d', strtotime($programikatan->periode_sampai));




        $data['programikatan'] = $programikatan;





        $data['detail'] = Detailajuanprogramikatanenambulan::join('pelanggan', 'marketing_program_ikatan_enambulan_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('marketing_program_ikatan_detail', function ($join) {
                $join->on('marketing_program_ikatan_enambulan_detail.no_pengajuan_programikatan', '=', 'marketing_program_ikatan_detail.no_pengajuan')
                    ->on('marketing_program_ikatan_enambulan_detail.kode_pelanggan', '=', 'marketing_program_ikatan_detail.kode_pelanggan');
            })
            ->where('marketing_program_ikatan_enambulan_detail.no_pengajuan', $no_pengajuan)

            ->select(
                'marketing_program_ikatan_enambulan_detail.*',
                'pelanggan.nama_pelanggan',
                'metode_pembayaran',
                'top',
                'qty_target',
                'reward',
                'budget_smm',
                'budget_rsm',
                'budget_gm',
                'file_doc'
            )
            ->get();
        return view('worksheetom.ajuanprogramikatanenambulan.cetak', $data);
    }


    public function approve($no_pengajuan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $programikatan = Ajuanprogramikatanenambulan::where('no_pengajuan', $no_pengajuan)
            ->join('program_ikatan', 'marketing_program_ikatan_enambulan.kode_program', '=', 'program_ikatan.kode_program')
            ->first();
        $list_pelanggan = Detailajuanprogramikatanenambulan::where('no_pengajuan', $no_pengajuan)
            ->select('marketing_program_ikatan_enambulan_detail.kode_pelanggan')
            ->get();
        $tanggal_ajuan = $programikatan->tanggal;
        $tahun = date('Y', strtotime($tanggal_ajuan));
        $tahunlalu = $tahun - 1;
        $produk = json_decode($programikatan->produk, true) ?? [];

        $dari = $tahunlalu . "-" . date('m-d', strtotime($programikatan->periode_dari));
        $sampai = $tahunlalu . "-" . date('m-d', strtotime($programikatan->periode_sampai));



        $data['programikatan'] = $programikatan;



        $data['detail'] = Detailajuanprogramikatanenambulan::join('pelanggan', 'marketing_program_ikatan_enambulan_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('marketing_program_ikatan_detail', function ($join) {
                $join->on('marketing_program_ikatan_enambulan_detail.no_pengajuan_programikatan', '=', 'marketing_program_ikatan_detail.no_pengajuan')
                    ->on('marketing_program_ikatan_enambulan_detail.kode_pelanggan', '=', 'marketing_program_ikatan_detail.kode_pelanggan');
            })
            ->where('marketing_program_ikatan_enambulan_detail.no_pengajuan', $no_pengajuan)

            ->select(
                'marketing_program_ikatan_enambulan_detail.*',
                'pelanggan.nama_pelanggan',
                'metode_pembayaran',
                'top',
                'qty_target',
                'reward',
                'budget_smm',
                'budget_rsm',
                'budget_gm',
                'file_doc'
            )
            ->get();
        return view('worksheetom.ajuanprogramikatanenambulan.approve', $data);
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
                Ajuanprogramikatanenambulan::where('no_pengajuan', $no_pengajuan)
                    ->update([
                        'status' => $status
                    ]);
            } else {
                Ajuanprogramikatanenambulan::where('no_pengajuan', $no_pengajuan)
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


    public function detailtarget($no_pengajuan, $kode_pelanggan)
    {
        $no_pengajuan = Crypt::decrypt($no_pengajuan);
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);

        $programikatanenambulan = Ajuanprogramikatanenambulan::where('no_pengajuan', $no_pengajuan)
            ->join('program_ikatan', 'marketing_program_ikatan_enambulan.kode_program', '=', 'program_ikatan.kode_program')
            ->first();
        $periode_dari = $programikatanenambulan->periode_dari;
        $periode_sampai = $programikatanenambulan->periode_sampai;
        $list_bulan = [];
        $list_tahun = [];
        $current_date = $periode_dari;
        while ($current_date <= $periode_sampai) {
            $list_bulan[] = date('m', strtotime($current_date));
            $list_tahun[] = date('Y', strtotime($current_date));
            $current_date = date('Y-m-d', strtotime('+1 month', strtotime($current_date)));
        }
        $detailprogramikatan = Detailajuanprogramikatanenambulan::where('no_pengajuan', $no_pengajuan)
            ->where('kode_pelanggan', $kode_pelanggan)
            ->first();

        $data['detailtarget'] = Detailtargetikatan::where('no_pengajuan', $detailprogramikatan->no_pengajuan_programikatan)
            ->where('kode_pelanggan', $kode_pelanggan)
            ->whereIn('bulan', $list_bulan)
            ->get();
        return view('worksheetom.ajuanprogramikatan.detailtarget', $data);
    }
}
