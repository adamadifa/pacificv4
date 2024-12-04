<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailajuanprogramikatan;
use App\Models\Detailpenjualan;
use App\Models\Pencairanprogram;
use App\Models\Pencairanprogramikatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PencairanprogramikatanController extends Controller
{
    public function index()
    {
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;

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
        $pencairanprogramikatan = $query->paginate(15);
        $pencairanprogramikatan->appends(request()->all());
        $data['pencairanprogramikatan'] = $pencairanprogramikatan;
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
            'bulan' => 'required',
            'no_pengajuan' => 'required',
            'tahun' => 'required',
            'keterangan' => 'required'
        ]);

        $lastpencairan = Pencairanprogramikatan::join('marketing_program_ikatan', 'marketing_pencairan_ikatan.no_pengajuan', '=', 'marketing_program_ikatan.no_pengajuan')
            ->select('kode_pencairan')->orderBy('kode_pencairan', 'desc')
            ->whereRaw('YEAR(marketing_pencairan_ikatan.tanggal)="' . date('Y', strtotime($request->tanggal)) . '"')
            ->where('kode_cabang', $request->kode_cabang)
            ->first();
        $last_kode_pencairan = $lastpencairan != null ? $lastpencairan->kode_pencairan : '';
        $kode_pencairan = buatkode($last_kode_pencairan, "PI" . $request->kode_cabang . date('y', strtotime($request->tanggal)), 4);

        try {
            //code...
            Pencairanprogramikatan::create([
                'kode_pencairan' => $kode_pencairan,
                'tanggal' => $request->tanggal,
                'no_pengajuan' => $request->no_pengajuan,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
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
            'periode_sampai',
        );
        $query->join('marketing_program_ikatan', 'marketing_pencairan_ikatan.no_pengajuan', '=', 'marketing_program_ikatan.no_pengajuan');
        $query->join('cabang', 'marketing_program_ikatan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('program_ikatan', 'marketing_program_ikatan.kode_program', '=', 'program_ikatan.kode_program');
        $query->orderBy('marketing_pencairan_ikatan.tanggal', 'desc');
        $query->where('kode_pencairan', $kode_pencairan);
        $pencairanprogramikatan = $query->first();
        $data['pencairanprogram'] = $pencairanprogramikatan;
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
            ->whereNotIn('marketing_penjualan.kode_pelanggan', function ($query) use ($pencairanprogram) {
                $query->select('kode_pelanggan')
                    ->from('marketing_pencairan_ikatan_detail')
                    ->join('marketing_pencairan_ikatan', 'marketing_pencairan_ikatan_detail.kode_pencairan', '=', 'marketing_pencairan_ikatan.kode_pencairan')
                    ->where('bulan', $pencairanprogram->bulan)
                    ->where('tahun', $pencairanprogram->tahun);
            })
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
}
