<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\DetailPengirimanDriver;
use App\Models\Driverhelper;
use App\Models\Pelanggan;
use App\Models\PengirimanDriver;
use App\Models\PengirimanDriverHelper;
use App\Models\Produk;
use App\Models\Salesman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PengirimanDriverController extends Controller
{
    public function index(Request $request)
    {
        $query = PengirimanDriver::with(['salesman', 'cabang', 'driver', 'helpers.driverhelper'])
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

        $pengirimandriver = $query->paginate(15)->appends($request->all());

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $salesman = Salesman::where('status_aktif_salesman', 1)->orderBy('nama_salesman')->get();

        return view('v2.marketing.pengirimandriver.index', compact('pengirimandriver', 'cabang', 'salesman'));
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
            if ($drivers->isEmpty()) {
                $drivers = $allDriverHelper;
            }
            if ($helpers->isEmpty()) {
                $helpers = $allDriverHelper;
            }
        }

        $produk = Produk::where('status_aktif_produk', 1)
            ->orderBy('urutan')
            ->orderBy('kode_produk')
            ->get();

        return view('v2.marketing.pengirimandriver.create', compact('cabang', 'salesman', 'drivers', 'helpers', 'produk', 'isPst', 'userCabang'));
    }

    public function getbycabang(Request $request)
    {
        $kode_cabang = $request->get('kode_cabang');
        if (empty($kode_cabang)) {
            return response()->json(['salesman' => [], 'drivers' => [], 'helpers' => []]);
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
        if ($drivers->isEmpty()) {
            $drivers = $allDriverHelper->values();
        }
        if ($helpers->isEmpty()) {
            $helpers = $allDriverHelper->values();
        }

        return response()->json([
            'salesman' => $salesman,
            'drivers' => $drivers,
            'helpers' => $helpers,
        ]);
    }

    public function getpelanggan(Request $request)
    {
        $search = trim($request->get('q', ''));
        $kode_cabang = auth()->user()->kode_cabang === 'PST'
            ? $request->get('kode_cabang')
            : auth()->user()->kode_cabang;
        $kode_salesman = $request->get('kode_salesman');

        $query = Pelanggan::select('kode_pelanggan', 'nama_pelanggan')
            ->where('status_aktif_pelanggan', 1);

        if (!empty($kode_cabang)) {
            $query->where('kode_cabang', $kode_cabang);
        }

        if (!empty($kode_salesman)) {
            $query->where('kode_salesman', $kode_salesman);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'like', '%' . $search . '%')
                    ->orWhere('kode_pelanggan', 'like', '%' . $search . '%');
            });
        }

        $pelanggan = $query->orderBy('nama_pelanggan')->limit(30)->get();

        return response()->json([
            'results' => $pelanggan->map(function ($p) {
                return [
                    'id' => $p->kode_pelanggan,
                    'text' => $p->kode_pelanggan . ' - ' . strtoupper($p->nama_pelanggan),
                ];
            }),
        ]);
    }

    public function store(Request $request)
    {
        $isPst = auth()->user()->kode_cabang === 'PST';

        $rules = [
            'tanggal' => 'required|date',
            'kode_salesman' => 'required|exists:salesman,kode_salesman',
            'kode_driver' => 'required|exists:driver_helper,kode_driver_helper',
            'kode_helper' => 'nullable|array',
            'rows' => 'required|array|min:1',
        ];
        if ($isPst) {
            $rules['kode_cabang'] = 'required|exists:cabang,kode_cabang';
        }
        $request->validate($rules);

        DB::beginTransaction();
        try {
            $prefix = 'PD' . date('ym', strtotime($request->tanggal));
            $last = PengirimanDriver::where('no_pengiriman', 'like', $prefix . '%')
                ->orderBy('no_pengiriman', 'desc')
                ->first();
            $lastNum = $last ? (int) substr($last->no_pengiriman, -4) : 0;
            $no_pengiriman = $prefix . str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);

            $kode_cabang = $isPst ? $request->kode_cabang : auth()->user()->kode_cabang;

            PengirimanDriver::create([
                'no_pengiriman' => $no_pengiriman,
                'tanggal' => $request->tanggal,
                'kode_cabang' => $kode_cabang,
                'kode_salesman' => $request->kode_salesman,
                'kode_driver' => $request->kode_driver,
                'keterangan' => $request->keterangan,
                'id_user' => auth()->user()->id,
            ]);

            if (!empty($request->kode_helper) && is_array($request->kode_helper)) {
                foreach (array_unique($request->kode_helper) as $kodeHelper) {
                    if (!empty($kodeHelper)) {
                        PengirimanDriverHelper::create([
                            'no_pengiriman' => $no_pengiriman,
                            'kode_driver_helper' => $kodeHelper,
                            'posisi' => 'H',
                        ]);
                    }
                }
            }

            $noUrut = 1;
            $hasDetail = false;
            foreach ($request->rows as $row) {
                $kodePelanggan = trim($row['kode_pelanggan'] ?? '');
                if ($kodePelanggan === '') {
                    continue;
                }

                if (!empty($row['produk']) && is_array($row['produk'])) {
                    foreach ($row['produk'] as $kodeProduk => $qtyRaw) {
                        $clean = str_replace('.', '', trim((string) $qtyRaw));
                        $qtyVal = str_replace(',', '.', $clean);
                        if ($qtyVal !== '' && is_numeric($qtyVal) && (float) $qtyVal > 0) {
                            DetailPengirimanDriver::create([
                                'no_pengiriman' => $no_pengiriman,
                                'no_urut' => $noUrut,
                                'kode_pelanggan' => $kodePelanggan,
                                'kode_produk' => $kodeProduk,
                                'qty' => (float) $qtyVal,
                            ]);
                            $hasDetail = true;
                        }
                    }
                }
                $noUrut++;
            }

            if (!$hasDetail) {
                DB::rollBack();
                return Redirect::back()->withInput()->with(messageError('Minimal 1 pelanggan dan 1 qty produk harus diisi.'));
            }

            DB::commit();
            return Redirect::route('pengirimandriver.index')->with(messageSuccess('Data Pengiriman Driver Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withInput()->with(messageError($e->getMessage()));
        }
    }

    public function show($no_pengiriman)
    {
        $no_pengiriman = Crypt::decrypt($no_pengiriman);
        $pengirimandriver = PengirimanDriver::with(['helpers.driverhelper', 'driver', 'salesman', 'cabang'])
            ->where('no_pengiriman', $no_pengiriman)
            ->firstOrFail();

        $produk = Produk::select('kode_produk', 'nama_produk')
            ->where('status_aktif_produk', 1)
            ->orderBy('urutan')
            ->orderBy('kode_produk')
            ->toBase()
            ->get();

        $details = DB::table('marketing_pengiriman_driver_detail as d')
            ->leftJoin('pelanggan as p', 'd.kode_pelanggan', '=', 'p.kode_pelanggan')
            ->select('d.no_urut', 'd.kode_pelanggan', 'p.nama_pelanggan', 'd.kode_produk', 'd.qty')
            ->where('d.no_pengiriman', $no_pengiriman)
            ->orderBy('d.no_urut')
            ->get();

        $rows = $details->groupBy('no_urut')->map(function ($items) {
            $first = $items->first();
            $qtyMap = [];
            foreach ($items as $item) {
                $qtyMap[$item->kode_produk] = $item->qty;
            }
            return (object) [
                'no_urut' => $first->no_urut,
                'kode_pelanggan' => $first->kode_pelanggan,
                'nama_pelanggan' => $first->nama_pelanggan ?? $first->kode_pelanggan,
                'qty_map' => $qtyMap,
            ];
        });

        return view('v2.marketing.pengirimandriver.show', compact('pengirimandriver', 'produk', 'rows'));
    }

    public function destroy($no_pengiriman)
    {
        $no_pengiriman = Crypt::decrypt($no_pengiriman);
        DB::beginTransaction();
        try {
            DetailPengirimanDriver::where('no_pengiriman', $no_pengiriman)->delete();
            PengirimanDriverHelper::where('no_pengiriman', $no_pengiriman)->delete();
            PengirimanDriver::where('no_pengiriman', $no_pengiriman)->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Pengiriman Driver Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
