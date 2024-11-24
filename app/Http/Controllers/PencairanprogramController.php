<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailpenjualan;
use App\Models\Diskon;
use App\Models\Pencairanprogram;
use App\Models\Penjualan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PencairanprogramController extends Controller
{
    public function index(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang;
        }
        $query = Pencairanprogram::query();
        if (!empty($kode_cabang)) {
            $query->where('kode_cabang', $kode_cabang);
        }

        $pencairanprogram = $query->get();

        $data['pencairanprogram'] = $pencairanprogram;

        return view('worksheetom.pencairanprogram.index', $data);
    }

    public function create()
    {
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('worksheetom.pencairanprogram.create', $data);
    }

    public function store(Request $request)
    {
        //Kode Pencairan Program PCBDG240001
        $request->validate([
            'tanggal' => 'required',
            'kode_cabang' => 'required',
            'bulan' => 'required',
            'tahun' => 'required',
            'kode_program' => 'required',
            'keterangan' => 'required'
        ]);
        $lastpencairan = Pencairanprogram::select('kode_pencairan')->orderBy('kode_pencairan', 'desc')
            ->whereRaw('YEAR(tanggal)="' . date('Y', strtotime($request->tanggal)) . '"')
            ->where('kode_cabang', $request->kode_cabang)
            ->first();
        $last_kode_pencairan = $lastpencairan != null ? $lastpencairan->kode_pencairan : '';
        $kode_pencairan = buatkode($last_kode_pencairan, "PC" . $request->kode_cabang . date('y', strtotime($request->tanggal)), 4);

        try {
            //code...
            Pencairanprogram::create([
                'kode_pencairan' => $kode_pencairan,
                'tanggal' => $request->tanggal,
                'kode_cabang' => $request->kode_cabang,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'kode_jenis_program' => 'KM',
                'kode_program' => $request->kode_program,
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
        $pencairanprogram = Pencairanprogram::where('kode_pencairan', $kode_pencairan)->first();
        $data['pencairanprogram'] = $pencairanprogram;
        return view('worksheetom.pencairanprogram.setpencairan', $data);
    }

    function tambahpelanggan(Request $request)
    {
        $data['kode_program'] = $request->kode_program;
        $data['bulan'] = $request->bulan;
        $data['tahun'] = $request->tahun;
        $data['kode_cabang'] = $request->kode_cabang;
        return view('worksheetom.pencairanprogram.tambahpelanggan', $data);
    }

    public function getpelanggan(Request $request)
    {
        if ($request->kode_program == 'PR001') {
            $produk = ['BB', 'DEP'];
            $kategori_diskon = 'D001';
        } else {
            $produk = ['AB', 'AR', 'AS'];
            $kategori_diskon = 'D002';
        }

        $start_date = $request->tahun . '-' . $request->bulan . '-01';
        $end_date = date('Y-m-t', strtotime($start_date));

        $detailpenjualan = Detailpenjualan::select(
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
            ->where('salesman.kode_cabang', $request->kode_cabang)
            ->where('status', 1)
            ->whereRaw("datediff(marketing_penjualan.tanggal_pelunasan, marketing_penjualan.tanggal) <= 14")
            ->where('status_batal', 0)
            ->whereIn('produk_harga.kode_produk', $produk)
            ->orderBy('nama_pelanggan')
            ->get();

        $diskon = Diskon::where('kode_kategori_diskon', $kategori_diskon)->get();
        $detail = $detailpenjualan->groupBy('kode_pelanggan')
            ->map(function ($group) use ($diskon) {
                $diskon_kumulatif = $diskon->first(function ($diskonItem) use ($group) {
                    return $diskonItem->min_qty <= $group->sum('jml_dus') && $diskonItem->max_qty >= $group->sum('jml_dus');
                })->diskon ?? 0;
                $total_diskon_kumulatif = $diskon_kumulatif * $group->sum('jml_dus');
                $cashback = $total_diskon_kumulatif - $group->sum('diskon_reguler');
                return [
                    'kode_pelanggan' => $group->first()->kode_pelanggan,
                    'nama_pelanggan' => $group->first()->nama_pelanggan,
                    'jml_dus' => $group->sum('jml_dus'),
                    'diskon_reguler' => $group->sum('diskon_reguler'),
                    'diskon_kumulatif' => $total_diskon_kumulatif,
                    'cashback' => $cashback,
                ];
            })
            ->sortBy('nama_pelanggan')
            ->filter(function ($item) {
                return $item['cashback'] > 0;
            })
            ->values()
            ->all();



        $data['detail'] = $detail;
        $data['bulan'] = $request->bulan;
        $data['tahun'] = $request->tahun;
        $data['diskon'] = $request->diskon;
        $data['kategori_diskon'] = $kategori_diskon;
        $data['kode_program'] = $request->kode_program;
        $data['kode_cabang'] = $request->kode_cabang;
        return view('worksheetom.pencairanprogram.getpelanggan', $data);
    }


    public function detailfaktur($kode_pelanggan, $kategori_diskon, $bulan, $tahun)
    {

        if ($kategori_diskon == 'D001') {
            $produk = ['BB', 'DEP'];
        } else {
            $produk = ['AB', 'AR', 'AS'];
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
