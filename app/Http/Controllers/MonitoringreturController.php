<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailretur;
use App\Models\Detailvalidasiretur;
use App\Models\Retur;
use App\Models\Salesman;
use App\Models\User;
use App\Models\Validasiitemretur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class MonitoringreturController extends Controller
{
    public function index(Request $request)
    {
        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }
        $rtr = new Retur();
        $retur = $rtr->getRetur($request, $no_retur = "")->cursorPaginate();
        $retur->appends(request()->all());
        $data['retur'] = $retur;


        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('worksheetom.monitoringretur.index', $data);
    }


    public function create($no_retur)
    {
        $no_retur = Crypt::decrypt($no_retur);
        $rtr = new Retur();
        $retur = $rtr->getRetur($request = null, $no_retur)->first();
        $data['retur'] = $retur;
        $data['detail'] = $rtr->getDetailretur($no_retur);

        $data['validasi_item'] = Validasiitemretur::orderBy('kode_item')->get();
        $validasi_cek = Detailvalidasiretur::select('kode_item')->where('no_retur', $no_retur)->get();
        $kode_item_cek = [];
        foreach ($validasi_cek as $d) {
            $kode_item_cek[] = $d->kode_item;
        }

        $data['kode_item_cek'] = $kode_item_cek;
        return view('worksheetom.monitoringretur.create', $data);
    }

    public function store(Request $request, $no_retur)
    {
        $no_retur = Crypt::decrypt($no_retur);
        $request->validate([
            'kode_item' => 'required',
        ]);

        try {
            for ($i = 0; $i < count($request->kode_item); $i++) {
                Detailvalidasiretur::create([
                    'no_retur' => $no_retur,
                    'kode_item' => $request->kode_item[$i],
                ]);
            }

            return Redirect::back()->with(messageSuccess('Data Berhasil Tersimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function cetak(Request $request)
    {
        $start_date = config('global.start_date');
        $end_date = config('global.end_date');
        $user = User::findorfail(auth()->user()->id);
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
        $query = Detailretur::query();
        $query->select(
            'marketing_retur.tanggal',
            'marketing_retur.no_faktur',
            'marketing_penjualan.kode_pelanggan',
            'nama_pelanggan',
            'marketing_retur_detail.*',
            'produk_harga.kode_produk',
            'nama_produk',
            'isi_pcs_dus',
            'isi_pcs_pack',
            'subtotal',
            'worksheetom_retur_pelunasan.jumlah as jumlah_pelunasan'
        );
        $query->join('produk_harga', 'marketing_retur_detail.kode_harga', '=', 'produk_harga.kode_harga');
        $query->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk');
        $query->leftjoin('worksheetom_retur_pelunasan', function ($join) {
            $join->on('marketing_retur_detail.no_retur', '=', 'worksheetom_retur_pelunasan.no_retur')
                ->on('marketing_retur_detail.kode_harga', '=', 'worksheetom_retur_pelunasan.kode_harga');
        });
        $query->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur');
        $query->join('marketing_penjualan', 'marketing_retur.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $query->leftJoin(
            DB::raw("(
                    SELECT
                        marketing_penjualan.no_faktur,
                        IF( salesbaru IS NULL, marketing_penjualan.kode_salesman, salesbaru ) AS kode_salesman_baru,
                        IF( cabangbaru IS NULL, salesman.kode_cabang, cabangbaru ) AS kode_cabang_baru
                    FROM
                        marketing_penjualan
                    INNER JOIN salesman ON marketing_penjualan.kode_salesman = salesman.kode_salesman
                    LEFT JOIN (
                    SELECT
                        no_faktur,
                        marketing_penjualan_movefaktur.kode_salesman_baru AS salesbaru,
                        salesman.kode_cabang AS cabangbaru
                    FROM
                        marketing_penjualan_movefaktur
                        INNER JOIN salesman ON marketing_penjualan_movefaktur.kode_salesman_baru = salesman.kode_salesman
                    WHERE id IN (SELECT MAX(id) as id FROM marketing_penjualan_movefaktur GROUP BY no_faktur)
                    ) movefaktur ON ( marketing_penjualan.no_faktur = movefaktur.no_faktur)
                ) pindahfaktur"),
            function ($join) {
                $join->on('marketing_penjualan.no_faktur', '=', 'pindahfaktur.no_faktur');
            }
        );
        $query->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $query->join('salesman', 'pindahfaktur.kode_salesman_baru', '=', 'salesman.kode_salesman');
        $query->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');


        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('marketing_retur.tanggal', [$request->dari, $request->sampai]);
        } else {
            $query->whereBetween('marketing_retur.tanggal', [$start_date, $end_date]);
        }

        if (!empty($request->no_faktur)) {
            $query->where('marketing_retur.no_faktur', $request->no_faktur);
        }

        if (!empty($kode_cabang)) {
            $query->where('kode_cabang_baru', $kode_cabang);
        }

        if (!empty($request->kode_salesman)) {
            $query->where('kode_salesman_baru', $request->kode_salesman);
        }

        if (!empty($request->kode_pelanggan)) {
            $query->where('marketing_penjualan.kode_pelanggan', $request->kode_pelanggan);
        }


        if (!empty($request->nama_pelanggan)) {
            $query->where('nama_pelanggan', 'like', '%' . $request->nama_pelanggan . '%');
        }

        $query->orderBy('marketing_retur.tanggal');
        $query->orderBy('marketing_retur.no_retur');
        $detail = $query->get();
        $data['detail'] = $detail;
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();
        $data['salesman'] = Salesman::where('kode_salesman', $request->kode_salesman)->first();

        return view('worksheetom.monitoringretur.cetak', $data);
    }
}
