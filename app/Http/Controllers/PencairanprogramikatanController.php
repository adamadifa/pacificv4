<?php

namespace App\Http\Controllers;

use App\Models\Ajuanprogramikatan;
use App\Models\Cabang;
use App\Models\Detailajuanprogramikatan;
use App\Models\Detailpencairanprogramikatan;
use App\Models\Detailpenjualan;
use App\Models\Pencairanprogram;
use App\Models\Pencairanprogramikatan;
use App\Models\Programikatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PencairanprogramikatanController extends Controller
{
    public function index(Request $request)
    {

        $user = User::find(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');


        $query = Pencairanprogramikatan::query();
        $query->select(
            'marketing_pencairan_ikatan.*',
            'cabang.nama_cabang',
            'nama_program',
            'nomor_dokumen',
            'periode_dari',
            'periode_sampai',
        );
        $query->join('marketing_program_ikatan', 'marketing_pencairan_ikatan.no_pengajuan', '=', 'marketing_program_ikatan.no_pengajuan');
        $query->join('cabang', 'marketing_program_ikatan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('program_ikatan', 'marketing_program_ikatan.kode_program', '=', 'program_ikatan.kode_program');
        $query->orderBy('marketing_pencairan_ikatan.tanggal', 'desc');

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
            $query->whereBetween('marketing_pencairan_ikatan.tanggal', [$request->dari, $request->sampai]);
        }

        if (!empty($request->nomor_dokumen)) {
            $query->where('marketing_program_ikatan.nomor_dokumen', $request->nomor_dokumen);
        }

        if ($user->hasRole('regional sales manager')) {
            $query->whereNotNull('marketing_program_ikatan.om');
            $query->where('marketing_pencairan_ikatan.status', '!=', 2);
        }

        if ($user->hasRole('gm marketing')) {
            $query->whereNotNull('marketing_program_ikatan.rsm');
            $query->where('marketing_pencairan_ikatan.status', '!=', 2);
        }

        if ($user->hasRole('direktur')) {
            $query->whereNotNull('marketing_program_ikatan.gm');
            $query->where('marketing_pencairan_ikatan.status', '!=', 2);
        }


        $pencairanprogramikatan = $query->paginate(15);
        $pencairanprogramikatan->appends(request()->all());
        $data['pencairanprogramikatan'] = $pencairanprogramikatan;

        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();

        $data['programikatan'] = Programikatan::orderBy('kode_program')->get();
        return view('worksheetom.pencairanprogramikatan.index', $data);
    }


    public function create()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('worksheetom.pencairanprogramikatan.create', $data);
    }


    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required',
            'periodepencairan' => 'required',
            'no_pengajuan' => 'required',
            'keterangan' => 'required'
        ]);
        $ajuan = Ajuanprogramikatan::where('no_pengajuan', $request->no_pengajuan)->first();

        $lastpencairan = Pencairanprogramikatan::join('marketing_program_ikatan', 'marketing_pencairan_ikatan.no_pengajuan', '=', 'marketing_program_ikatan.no_pengajuan')
            ->select('kode_pencairan')->orderBy('kode_pencairan', 'desc')
            ->whereRaw('YEAR(marketing_pencairan_ikatan.tanggal)="' . date('Y', strtotime($request->tanggal)) . '"')
            ->where('kode_cabang', $ajuan->kode_cabang)
            ->first();
        $last_kode_pencairan = $lastpencairan != null ? $lastpencairan->kode_pencairan : '';

        // dd($last_kode_pencairan);
        $kode_pencairan = buatkode($last_kode_pencairan, "PI" . $ajuan->kode_cabang . date('y', strtotime($request->tanggal)), 4);

        try {
            $periodepencairan = explode('-', $request->periodepencairan);
            $bulan = $periodepencairan[0];
            $tahun = $periodepencairan[1];
            //code...
            Pencairanprogramikatan::create([
                'kode_pencairan' => $kode_pencairan,
                'tanggal' => $request->tanggal,
                'no_pengajuan' => $request->no_pengajuan,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'keterangan' => $request->keterangan
            ]);
            return Redirect::back()->with(messageSuccess("Data Berhasil Disimpan"));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }




    public function setpencairan($kode_pencairan)
    {
        $kode_pencairan = Crypt::decrypt($kode_pencairan);
        $query = Pencairanprogramikatan::query();
        $query->select(
            'marketing_pencairan_ikatan.*',
            'cabang.nama_cabang',
            'nama_program',
            'nomor_dokumen',
            'periode_dari',
            'periode_sampai'
        );
        $query->join('marketing_program_ikatan', 'marketing_pencairan_ikatan.no_pengajuan', '=', 'marketing_program_ikatan.no_pengajuan');
        $query->join('cabang', 'marketing_program_ikatan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('program_ikatan', 'marketing_program_ikatan.kode_program', '=', 'program_ikatan.kode_program');
        $query->orderBy('marketing_pencairan_ikatan.tanggal', 'desc');
        $query->where('kode_pencairan', $kode_pencairan);
        $pencairanprogramikatan = $query->first();


        $pelangganprogram = Detailajuanprogramikatan::where('no_pengajuan', $pencairanprogramikatan->no_pengajuan);
        $detail = Detailpencairanprogramikatan::join('pelanggan', 'marketing_pencairan_ikatan_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('marketing_pencairan_ikatan', 'marketing_pencairan_ikatan_detail.kode_pencairan', '=', 'marketing_pencairan_ikatan.kode_pencairan')
            ->leftJoinSub($pelangganprogram, 'pelangganprogram', function ($join) {
                $join->on('marketing_pencairan_ikatan_detail.kode_pelanggan', '=', 'pelangganprogram.kode_pelanggan');
            })
            ->where('marketing_pencairan_ikatan_detail.kode_pencairan', $kode_pencairan)
            ->orderBy('pelangganprogram.metode_pembayaran')
            ->get();
        $data['pencairanprogram'] = $pencairanprogramikatan;
        $data['detail'] = $detail;
        return view('worksheetom.pencairanprogramikatan.setpencairan', $data);
    }


    function tambahpelanggan($kode_pencairan)
    {
        $kode_pencairan = Crypt::decrypt($kode_pencairan);
        $data['kode_pencairan'] = $kode_pencairan;
        return view('worksheetom.pencairanprogramikatan.tambahpelanggan', $data);
    }

    public function getpelanggan(Request $request)
    {

        $kode_pencairan = Crypt::decrypt($request->kode_pencairan);
        $query = Pencairanprogramikatan::query();
        $query->select(
            'marketing_pencairan_ikatan.*',
            'marketing_program_ikatan.kode_cabang',
            'cabang.nama_cabang',
            'nama_program',
            'nomor_dokumen',
            'periode_dari',
            'periode_sampai',
            'produk',

        );
        $query->join('marketing_program_ikatan', 'marketing_pencairan_ikatan.no_pengajuan', '=', 'marketing_program_ikatan.no_pengajuan');
        $query->join('cabang', 'marketing_program_ikatan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('program_ikatan', 'marketing_program_ikatan.kode_program', '=', 'program_ikatan.kode_program');
        $query->orderBy('marketing_pencairan_ikatan.tanggal', 'desc');
        $query->where('kode_pencairan', $kode_pencairan);
        $pencairanprogram = $query->first();

        $start_date = $pencairanprogram->tahun . '-' . $pencairanprogram->bulan . '-01';
        $end_date = date('Y-m-t', strtotime($start_date));

        $produk = json_decode($pencairanprogram->produk, true) ?? [];

        $detailpenjualan = Detailpenjualan::select(
            'marketing_penjualan.kode_pelanggan',
            DB::raw('SUM(floor(jumlah/isi_pcs_dus)) as jml_dus'),
        )
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->whereBetween('marketing_penjualan.tanggal', [$start_date, $end_date])
            ->where('salesman.kode_cabang', $pencairanprogram->kode_cabang)
            // ->where('status', 1)
            // ->whereRaw("datediff(marketing_penjualan.tanggal_pelunasan, marketing_penjualan.tanggal) <= 14")
            ->where('status_batal', 0)
            ->whereIn('produk_harga.kode_produk', $produk)
            // ->whereNotIn('marketing_penjualan.kode_pelanggan', function ($query) use ($pencairanprogram) {
            //     $query->select('kode_pelanggan')
            //         ->from('marketing_pencairan_ikatan_detail')
            //         ->join('marketing_pencairan_ikatan', 'marketing_pencairan_ikatan_detail.kode_pencairan', '=', 'marketing_pencairan_ikatan.kode_pencairan')
            //         ->where('bulan', $pencairanprogram->bulan)
            //         ->where('tahun', $pencairanprogram->tahun);
            // })
            ->groupBy('marketing_penjualan.kode_pelanggan');



        $peserta = Detailajuanprogramikatan::select(
            'marketing_program_ikatan_detail.kode_pelanggan',
            'nama_pelanggan',
            'reward',
            'qty_target',
            'jml_dus'
        )
            ->join('pelanggan', 'marketing_program_ikatan_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('marketing_program_ikatan', 'marketing_program_ikatan_detail.no_pengajuan', '=', 'marketing_program_ikatan.no_pengajuan')
            ->leftJoinSub($detailpenjualan, 'detailpenjualan', function ($join) {
                $join->on('marketing_program_ikatan_detail.kode_pelanggan', '=', 'detailpenjualan.kode_pelanggan');
            })
            ->where('marketing_program_ikatan_detail.status', 1)
            ->where('marketing_program_ikatan.no_pengajuan', $pencairanprogram->no_pengajuan)
            ->get();


        // $data['detail'] = $detail;
        $data['kode_pencairan'] = $kode_pencairan;
        $data['peserta'] = $peserta;
        // $data['bulan'] = $request->bulan;
        // $data['tahun'] = $request->tahun;
        // $data['diskon'] = $request->diskon;
        // $data['kategori_diskon'] = $kategori_diskon;
        // $data['kode_program'] = $request->kode_program;
        // $data['kode_cabang'] = $request->kode_cabang;
        return view('worksheetom.pencairanprogramikatan.getpelanggan', $data);
    }

    public function storepelanggan(Request $request, $kode_pencairan)
    {
        $kode_pencairan = Crypt::decrypt($kode_pencairan);
        $kode_pelanggan = $request->kode_pelanggan;
        $jumlah = $request->jumlah;
        $status = $request->status;
        DB::beginTransaction();
        try {
            Detailpencairanprogramikatan::where('kode_pencairan', $kode_pencairan)->delete();
            for ($i = 0; $i < count($kode_pelanggan); $i++) {
                if ($status[$i] == 1) {
                    Detailpencairanprogramikatan::create([
                        'kode_pencairan' => $kode_pencairan,
                        'kode_pelanggan' => $kode_pelanggan[$i],
                        'jumlah' => toNumber($jumlah[$i])
                    ]);
                    Detailajuanprogramikatan::where('kode_pelanggan', $kode_pelanggan[$i])->update([
                        'status' => 1
                    ]);
                } else {
                    Detailajuanprogramikatan::where('kode_pelanggan', $kode_pelanggan[$i])->update([
                        'status' => 0
                    ]);
                }
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Pelanggan Berhasil Di Proses'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_pencairan)
    {
        $kode_pencairan = Crypt::decrypt($kode_pencairan);
        try {
            Pencairanprogramikatan::where('kode_pencairan', $kode_pencairan)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function approve($kode_pencairan)
    {
        $kode_pencairan = Crypt::decrypt($kode_pencairan);
        $query = Pencairanprogramikatan::query();
        $query->select(
            'marketing_pencairan_ikatan.*',
            'cabang.nama_cabang',
            'nama_program',
            'nomor_dokumen',
            'periode_dari',
            'periode_sampai'
        );
        $query->join('marketing_program_ikatan', 'marketing_pencairan_ikatan.no_pengajuan', '=', 'marketing_program_ikatan.no_pengajuan');
        $query->join('cabang', 'marketing_program_ikatan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('program_ikatan', 'marketing_program_ikatan.kode_program', '=', 'program_ikatan.kode_program');
        $query->orderBy('marketing_pencairan_ikatan.tanggal', 'desc');
        $query->where('kode_pencairan', $kode_pencairan);
        $pencairanprogramikatan = $query->first();


        $pelangganprogram = Detailajuanprogramikatan::where('no_pengajuan', $pencairanprogramikatan->no_pengajuan);
        $detail = Detailpencairanprogramikatan::join('pelanggan', 'marketing_pencairan_ikatan_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('marketing_pencairan_ikatan', 'marketing_pencairan_ikatan_detail.kode_pencairan', '=', 'marketing_pencairan_ikatan.kode_pencairan')
            ->leftJoinSub($pelangganprogram, 'pelangganprogram', function ($join) {
                $join->on('marketing_pencairan_ikatan_detail.kode_pelanggan', '=', 'pelangganprogram.kode_pelanggan');
            })
            ->where('marketing_pencairan_ikatan_detail.kode_pencairan', $kode_pencairan)
            ->orderBy('pelangganprogram.metode_pembayaran')
            ->get();
        $data['pencairanprogram'] = $pencairanprogramikatan;
        $data['detail'] = $detail;
        return view('worksheetom.pencairanprogramikatan.approve', $data);
    }

    public function storeapprove(Request $request, $kode_pencairan)
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

        $kode_pencairan = Crypt::decrypt($kode_pencairan);
        try {
            if ($user->hasRole('super admin')) {
                Pencairanprogramikatan::where('kode_pencairan', $kode_pencairan)
                    ->update([
                        'status' => $status
                    ]);
            } else {
                Pencairanprogramikatan::where('kode_pencairan', $kode_pencairan)
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

    public function upload($kode_pencairan, $kode_pelanggan)
    {
        $kode_pencairan = Crypt::decrypt($kode_pencairan);
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $data['kode_pencairan'] = $kode_pencairan;
        $data['kode_pelanggan'] = $kode_pelanggan;
        return view('worksheetom.pencairanprogramikatan.upload', $data);
    }

    public function storeupload(Request $request, $kode_pencairan, $kode_pelanggan)
    {
        $kode_pencairan = Crypt::decrypt($kode_pencairan);
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        try {
            //code...
            Detailpencairanprogramikatan::where('kode_pencairan', $kode_pencairan)
                ->where('kode_pelanggan', $kode_pelanggan)
                ->update([
                    'bukti_transfer' => $request->bukti_transfer
                ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Di Upload'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function cetak($kode_pencairan)
    {
        $kode_pencairan = Crypt::decrypt($kode_pencairan);
        $query = Pencairanprogramikatan::query();
        $query->select(
            'marketing_pencairan_ikatan.*',
            'cabang.nama_cabang',
            'nama_program',
            'nomor_dokumen',
            'periode_dari',
            'periode_sampai'
        );
        $query->join('marketing_program_ikatan', 'marketing_pencairan_ikatan.no_pengajuan', '=', 'marketing_program_ikatan.no_pengajuan');
        $query->join('cabang', 'marketing_program_ikatan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('program_ikatan', 'marketing_program_ikatan.kode_program', '=', 'program_ikatan.kode_program');
        $query->orderBy('marketing_pencairan_ikatan.tanggal', 'desc');
        $query->where('kode_pencairan', $kode_pencairan);
        $pencairanprogramikatan = $query->first();


        $pelangganprogram = Detailajuanprogramikatan::where('no_pengajuan', $pencairanprogramikatan->no_pengajuan);
        $detail = Detailpencairanprogramikatan::join('pelanggan', 'marketing_pencairan_ikatan_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('marketing_pencairan_ikatan', 'marketing_pencairan_ikatan_detail.kode_pencairan', '=', 'marketing_pencairan_ikatan.kode_pencairan')
            ->leftJoinSub($pelangganprogram, 'pelangganprogram', function ($join) {
                $join->on('marketing_pencairan_ikatan_detail.kode_pelanggan', '=', 'pelangganprogram.kode_pelanggan');
            })
            ->where('marketing_pencairan_ikatan_detail.kode_pencairan', $kode_pencairan)
            ->orderBy('pelangganprogram.metode_pembayaran')
            ->get();
        $data['pencairanprogram'] = $pencairanprogramikatan;
        $data['detail'] = $detail;
        return view('worksheetom.pencairanprogramikatan.cetak', $data);
    }


    public function detailfaktur($kode_pelanggan, $kode_pencairan)
    {

        $kode_pencairan = Crypt::decrypt($kode_pencairan);

        $pencairanprogram = Pencairanprogram::where('kode_pencairan', $kode_pencairan)->first();
        $bulan = $pencairanprogram->bulan;
        $tahun = $pencairanprogram->tahun;

        if ($pencairanprogram->kode_program == 'PR001') {
            $produk = ['BB', 'DEP'];
            $kategori_diskon = 'D001';
        } else {
            $produk = ['AB', 'AR', 'AS'];
            $kategori_diskon = 'D002';
        }
        $start_date = $tahun . '-' . $bulan . '-01';
        $end_date = date('Y-m-t', strtotime($start_date));
        $detailpenjualan = Detailpenjualan::select(
            'marketing_penjualan.no_faktur',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.tanggal_pelunasan',
            'marketing_penjualan.jenis_transaksi',
            'marketing_penjualan.kode_pelanggan',
            'nama_pelanggan',
            DB::raw('floor(jumlah/isi_pcs_dus) as jml_dus'),
            DB::raw('(SELECT diskon FROM produk_diskon WHERE floor(marketing_penjualan_detail.jumlah/produk.isi_pcs_dus) BETWEEN produk_diskon.min_qty AND produk_diskon.max_qty AND kode_kategori_diskon="' . $kategori_diskon . '") as diskon'),
            DB::raw('floor(jumlah/isi_pcs_dus) * (SELECT diskon FROM produk_diskon WHERE floor(marketing_penjualan_detail.jumlah/produk.isi_pcs_dus) BETWEEN produk_diskon.min_qty AND produk_diskon.max_qty AND kode_kategori_diskon="' . $kategori_diskon . '") as diskon_reguler'),

        )
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->whereBetween('marketing_penjualan.tanggal', [$start_date, $end_date])
            ->where('marketing_penjualan.kode_pelanggan', $kode_pelanggan)
            ->where('status', 1)
            ->whereRaw("datediff(marketing_penjualan.tanggal_pelunasan, marketing_penjualan.tanggal) <= 14")
            ->where('status_batal', 0)
            ->whereIn('produk_harga.kode_produk', $produk)
            ->orderBy('nama_pelanggan')
            ->get();

        return view('worksheetom.pencairanprogram.detailfaktur', compact('detailpenjualan'));
    }
}
