<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Driverhelper;
use App\Models\Fpb;
use App\Models\FpbFaktur;
use App\Models\FpbHelper;
use App\Models\Kendaraan;
use App\Models\Salesman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class FpbController extends Controller
{
    public function index(Request $request)
    {
        $query = Fpb::with(['salesman', 'cabang', 'driver', 'helpers.driverhelper', 'kendaraan'])
            ->withCount('fakturs')
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc');

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
        }

        if (!empty($request->kode_cabang)) {
            $query->where('kode_cabang', $request->kode_cabang);
        } elseif (!auth()->user()->hasRole(config('global.roles_access_all_cabang'))) {
            $query->where('kode_cabang', auth()->user()->kode_cabang);
        }

        if (!empty($request->kode_salesman)) {
            $query->where('kode_salesman', $request->kode_salesman);
        }

        $fpb = $query->paginate(15)->appends($request->all());

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $salesman = Salesman::where('status_aktif_salesman', 1)->orderBy('nama_salesman')->get();

        return view('v2.marketing.fpb.index', compact('fpb', 'cabang', 'salesman'));
    }

    public function create()
    {
        $userCabang = auth()->user()->kode_cabang;
        $isPst = $userCabang === 'PST';

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();

        if ($isPst) {
            $salesman = collect();
            $drivers = collect();
            $helpers = collect();
            $kendaraan = collect();
        } else {
            $salesman = Salesman::where('status_aktif_salesman', 1)
                ->where('kode_cabang', $userCabang)
                ->orderBy('nama_salesman')
                ->get();

            $allDriverHelper = Driverhelper::where('kode_cabang', $userCabang)
                ->orderBy('nama_driver_helper')
                ->get();

            $drivers = $allDriverHelper->where('posisi', 'D');
            $helpers = $allDriverHelper->where('posisi', 'H');
            if ($drivers->isEmpty()) $drivers = $allDriverHelper;
            if ($helpers->isEmpty()) $helpers = $allDriverHelper;

            $kendaraan = Kendaraan::where('kode_cabang', $userCabang)
                ->where('status_aktif_kendaraan', 1)
                ->orderBy('no_polisi')
                ->get();
        }

        $produk = \App\Models\Produk::select('kode_produk', 'nama_produk')
            ->where('status_aktif_produk', 1)
            ->orderBy('urutan')
            ->orderBy('kode_produk')
            ->toBase()
            ->get();

        return view('v2.marketing.fpb.create', compact('cabang', 'salesman', 'drivers', 'helpers', 'kendaraan', 'produk', 'isPst', 'userCabang'));
    }

    public function getbycabang(Request $request)
    {
        $kode_cabang = $request->get('kode_cabang');
        if (empty($kode_cabang)) {
            return response()->json(['salesman' => [], 'drivers' => [], 'helpers' => [], 'kendaraan' => []]);
        }

        $salesman = Salesman::select('kode_salesman', 'nama_salesman')
            ->where('status_aktif_salesman', 1)
            ->where('kode_cabang', $kode_cabang)
            ->orderBy('nama_salesman')
            ->get();

        $allDriverHelper = Driverhelper::select('kode_driver_helper', 'nama_driver_helper', 'posisi')
            ->where('kode_cabang', $kode_cabang)
            ->orderBy('nama_driver_helper')
            ->get();

        $drivers = $allDriverHelper->where('posisi', 'D')->values();
        $helpers = $allDriverHelper->where('posisi', 'H')->values();
        if ($drivers->isEmpty()) $drivers = $allDriverHelper->values();
        if ($helpers->isEmpty()) $helpers = $allDriverHelper->values();

        $kendaraan = Kendaraan::select('kode_kendaraan', 'no_polisi', 'merek')
            ->where('kode_cabang', $kode_cabang)
            ->where('status_aktif_kendaraan', 1)
            ->orderBy('no_polisi')
            ->get();

        return response()->json([
            'salesman' => $salesman,
            'drivers' => $drivers,
            'helpers' => $helpers,
            'kendaraan' => $kendaraan,
        ]);
    }

    public function getfaktur(Request $request)
    {
        $kode_salesman = $request->get('kode_salesman');
        $dari = $request->get('dari');
        $sampai = $request->get('sampai');

        if (empty($kode_salesman) || empty($dari) || empty($sampai)) {
            return response()->json(['fakturs' => []]);
        }

        $fakturs = DB::table('marketing_penjualan as mp')
            ->join('pelanggan as p', 'mp.kode_pelanggan', '=', 'p.kode_pelanggan')
            ->select('mp.no_faktur', 'mp.tanggal', 'mp.kode_pelanggan', 'p.nama_pelanggan', 'mp.jenis_transaksi')
            ->where('mp.kode_salesman', $kode_salesman)
            ->whereBetween('mp.tanggal', [$dari, $sampai])
            ->where('mp.status_batal', '0')
            ->orderBy('mp.tanggal')
            ->orderBy('mp.no_faktur')
            ->get();

        if ($fakturs->isEmpty()) {
            return response()->json(['fakturs' => []]);
        }

        $noFakturList = $fakturs->pluck('no_faktur')->toArray();

        $details = DB::table('marketing_penjualan_detail as d')
            ->join('produk_harga as ph', 'd.kode_harga', '=', 'ph.kode_harga')
            ->join('produk as pr', 'ph.kode_produk', '=', 'pr.kode_produk')
            ->select(
                'd.no_faktur',
                'pr.kode_produk',
                'pr.nama_produk',
                DB::raw('SUM(d.jumlah / pr.isi_pcs_dus) as qty_dus')
            )
            ->whereIn('d.no_faktur', $noFakturList)
            ->groupBy('d.no_faktur', 'pr.kode_produk', 'pr.nama_produk')
            ->get()
            ->groupBy('no_faktur');

        $result = $fakturs->map(function ($f) use ($details) {
            return [
                'no_faktur' => $f->no_faktur,
                'tanggal' => DateToIndo($f->tanggal),
                'kode_pelanggan' => $f->kode_pelanggan,
                'nama_pelanggan' => strtoupper($f->nama_pelanggan),
                'jenis_transaksi' => $f->jenis_transaksi == 'T' ? 'TUNAI' : 'KREDIT',
                'items' => ($details->get($f->no_faktur) ?? collect())->values(),
            ];
        });

        return response()->json(['fakturs' => $result]);
    }

    public function store(Request $request)
    {
        $isPst = auth()->user()->kode_cabang === 'PST';

        $rules = [
            'tanggal' => 'required|date',
            'kode_salesman' => 'required|exists:salesman,kode_salesman',
            'kode_driver' => 'required|exists:driver_helper,kode_driver_helper',
            'kode_kendaraan' => 'nullable|exists:kendaraan,kode_kendaraan',
            'kode_helper' => 'nullable|array',
            'no_faktur' => 'required|array|min:1',
        ];
        if ($isPst) {
            $rules['kode_cabang'] = 'required|exists:cabang,kode_cabang';
        }
        $request->validate($rules);

        DB::beginTransaction();
        try {
            $prefix = 'FPB' . date('ym', strtotime($request->tanggal));
            $last = Fpb::where('no_fpb', 'like', $prefix . '%')
                ->orderBy('no_fpb', 'desc')
                ->first();
            $lastNum = $last ? (int) substr($last->no_fpb, -4) : 0;
            $no_fpb = $prefix . str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);

            $kode_cabang = $isPst ? $request->kode_cabang : auth()->user()->kode_cabang;

            Fpb::create([
                'no_fpb' => $no_fpb,
                'tanggal' => $request->tanggal,
                'kode_cabang' => $kode_cabang,
                'kode_salesman' => $request->kode_salesman,
                'area_rute' => $request->area_rute,
                'kode_driver' => $request->kode_driver,
                'kode_kendaraan' => $request->kode_kendaraan ?: null,
                'catatan' => $request->catatan,
                'id_user' => auth()->user()->id,
            ]);

            if (!empty($request->kode_helper) && is_array($request->kode_helper)) {
                foreach (array_unique($request->kode_helper) as $kodeHelper) {
                    if (!empty($kodeHelper)) {
                        FpbHelper::create([
                            'no_fpb' => $no_fpb,
                            'kode_driver_helper' => $kodeHelper,
                            'posisi' => 'H',
                        ]);
                    }
                }
            }

            foreach (array_unique($request->no_faktur) as $noFaktur) {
                if (!empty($noFaktur)) {
                    FpbFaktur::create([
                        'no_fpb' => $no_fpb,
                        'no_faktur' => $noFaktur,
                    ]);
                }
            }

            DB::commit();
            return Redirect::route('fpb.index')->with(messageSuccess('Data FPB Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withInput()->with(messageError($e->getMessage()));
        }
    }

    public function show($no_fpb)
    {
        $no_fpb = Crypt::decrypt($no_fpb);
        $fpb = Fpb::with(['helpers.driverhelper', 'driver', 'kendaraan', 'salesman', 'cabang'])
            ->where('no_fpb', $no_fpb)
            ->firstOrFail();

        $fakturs = DB::table('marketing_fpb_faktur as ff')
            ->join('marketing_penjualan as mp', 'ff.no_faktur', '=', 'mp.no_faktur')
            ->join('pelanggan as p', 'mp.kode_pelanggan', '=', 'p.kode_pelanggan')
            ->select('ff.no_faktur', 'mp.tanggal', 'p.nama_pelanggan')
            ->where('ff.no_fpb', $no_fpb)
            ->orderBy('mp.tanggal')
            ->orderBy('ff.no_faktur')
            ->get();

        $items = DB::table('marketing_fpb_faktur as ff')
            ->join('marketing_penjualan_detail as d', 'ff.no_faktur', '=', 'd.no_faktur')
            ->join('produk_harga as ph', 'd.kode_harga', '=', 'ph.kode_harga')
            ->join('produk as pr', 'ph.kode_produk', '=', 'pr.kode_produk')
            ->select(
                'pr.kode_produk',
                'pr.nama_produk',
                DB::raw('SUM(d.jumlah / pr.isi_pcs_dus) as jml_permintaan')
            )
            ->where('ff.no_fpb', $no_fpb)
            ->groupBy('pr.kode_produk', 'pr.nama_produk', 'pr.urutan')
            ->orderBy('pr.urutan')
            ->orderBy('pr.kode_produk')
            ->get();

        return view('v2.marketing.fpb.show', compact('fpb', 'fakturs', 'items'));
    }

    public function destroy($no_fpb)
    {
        $no_fpb = Crypt::decrypt($no_fpb);
        DB::beginTransaction();
        try {
            FpbFaktur::where('no_fpb', $no_fpb)->delete();
            FpbHelper::where('no_fpb', $no_fpb)->delete();
            Fpb::where('no_fpb', $no_fpb)->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data FPB Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
