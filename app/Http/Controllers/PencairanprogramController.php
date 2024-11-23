<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailpenjualan;
use App\Models\Pencairanprogram;
use App\Models\Penjualan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
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

    public function tambahpelanggan(Request $request)
    {
        if ($request->kode_program == 'PR001') {
            $produk = ['BB', 'DEP'];
        } else {
            $produk = ['AB', 'AR', 'AS'];
        }

        $start_date = $request->tahun . '-' . $request->bulan . '-01';
        $end_date = date('Y-m-t', strtotime($start_date));

        $detailpenjualan = Detailpenjualan::select('marketing_penjualan.kode_pelanggan', 'nama_pelanggan')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('marketing_penjualan', 'marketing_penjualan_detail.kode_penjualan', '=', 'marketing_penjualan.kode_penjualan')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->whereBetween('marketing_penjualan.tanggal', [$start_date, $end_date])
            ->where('salesman.kode_cabang', $request->kode_cabang)
            ->where('status', 1)
            ->whereRaw("datediff(marketing_penjualan.tanggal_pelunasan, marketing_penjualan.tanggal) <= 14")
            ->where('status_batal', 0)
            ->groupBy('marketing_penjualan.kode_pelanggan', 'nama_pelanggan')
            ->get();

        $data['detail'] = $detailpenjualan;
        return view('worksheetom.pencairanprogram.tambahpelanggan', $data);
    }
}
