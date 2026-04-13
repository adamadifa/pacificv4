<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class BBMController extends Controller
{

    public function index(Request $request)
    {

        $data['bbm'] = DB::table('kontrol_bbm')
            ->leftJoin('cabang', 'kontrol_bbm.kode_cabang', '=', 'cabang.kode_cabang')
            ->leftJoin('kendaraan', 'kontrol_bbm.kode_kendaraan', '=', 'kendaraan.kode_kendaraan')
            ->leftJoin('driver_helper', 'kontrol_bbm.kode_driver_helper', '=', 'driver_helper.kode_driver_helper')

            ->select(
                'kontrol_bbm.*',
                'cabang.nama_cabang',
                'kendaraan.no_polisi',
                'driver_helper.nama_driver_helper',

                DB::raw('(kilometer_akhir - kilometer_awal) as jarak_tempuh'),

                DB::raw('
                CASE
                    WHEN jumlah_liter > 0
                    THEN ROUND((kilometer_akhir - kilometer_awal) / jumlah_liter,2)
                    ELSE 0
                END as rasio_bbm
            ')
            )
            ->when(Auth::user()->kode_cabang != 'PST', function ($q) {
                $q->where('kontrol_bbm.kode_cabang', Auth::user()->kode_cabang);
            })
            ->when($request->filled('dari_search') && $request->filled('sampai_search'), function ($q) use ($request) {
                $q->whereBetween('tanggal', [$request->dari_search, $request->sampai_search]);
            })
            ->when($request->filled('kode_kendaraan_search'), function ($q) use ($request) {
                $q->where('kontrol_bbm.kode_kendaraan', $request->kode_kendaraan_search);
            })
            ->when($request->filled('kode_driver_helper_search'), function ($q) use ($request) {
                $q->where('kontrol_bbm.kode_driver_helper', $request->kode_driver_helper_search);
            })
            ->when($request->filled('kode_cabang_search'), function ($q) use ($request) {
                $q->where('kontrol_bbm.kode_cabang', $request->kode_cabang_search);
            })
            ->orderBy('tanggal', 'desc')
            ->paginate(20);

        $data['kendaraan'] = DB::table('kendaraan')
            ->where('kode_cabang', Auth::user()->kode_cabang)
            ->orderBy('no_polisi')
            ->get();

        $data['driver'] = DB::table('driver_helper')
            ->where('kode_cabang', Auth::user()->kode_cabang)
            ->where('posisi', 'D')
            ->orderBy('nama_driver_helper')
            ->get();
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('worksheetom.bbm.index', $data);
    }


    public function create()
    {

        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        $data['kendaraan'] = DB::table('kendaraan')
            ->where('kode_cabang', Auth::user()->kode_cabang)
            ->orderBy('no_polisi')
            ->get();

        $data['driver'] = DB::table('driver_helper')
            ->where('kode_cabang', Auth::user()->kode_cabang)
            ->where('posisi', 'D')
            ->orderBy('nama_driver_helper')
            ->get();

        return view('worksheetom.bbm.create', $data);
    }



    public function store(Request $request)
    {

        DB::beginTransaction();

        try {
            $cektutuplaporan = cektutupLaporan($request->tanggal, "worksheetom");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            DB::table('kontrol_bbm')->insert([
                'tanggal' => $request->tanggal,
                'kode_kendaraan' => $request->kode_kendaraan,
                'kode_cabang' => $request->kode_cabang,
                'kode_driver_helper' => $request->kode_driver_helper,
                'kilometer_awal' => $request->kilometer_awal,
                'kilometer_akhir' => $request->kilometer_akhir,
                'tujuan' => $request->tujuan,
                'jumlah_liter' => str_replace(',', '.', $request->jumlah_liter),
                'jumlah_harga' => str_replace(',', '.', $request->jumlah_harga),
                'keterangan' => $request->keterangan,
                'created_at' => now()
            ]);

            DB::commit();

            return Redirect::back()->with('success', 'Data BBM Berhasil Disimpan');

        } catch (\Exception $e) {

            DB::rollback();
            return Redirect::back()->with('error', $e->getMessage());
        }
    }


    public function edit($id)
    {

        $id = Crypt::decrypt($id);

        $data['bbm'] = DB::table('kontrol_bbm')
            ->where('id', $id)
            ->first();

        $data['kendaraan'] = DB::table('kendaraan')
            ->where('kode_cabang', Auth::user()->kode_cabang)
            ->orderBy('no_polisi')
            ->get();

        $data['driver'] = DB::table('driver_helper')
            ->where('kode_cabang', Auth::user()->kode_cabang)
            ->where('posisi', 'D')
            ->orderBy('nama_driver_helper')
            ->get();

        return view('worksheetom.bbm.edit', $data);
    }


    public function update(Request $request, $id)
    {

        $id = Crypt::decrypt($id);

        DB::beginTransaction();

        try {

            $cektutuplaporan = cektutupLaporan($request->tanggal, "worksheetom");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            DB::table('kontrol_bbm')
                ->where('id', $id)
                ->update([
                    'tanggal' => $request->tanggal,
                    'kode_kendaraan' => $request->kode_kendaraan,
                    'kode_driver_helper' => $request->kode_driver_helper,
                    'kilometer_awal' => $request->kilometer_awal,
                    'kilometer_akhir' => $request->kilometer_akhir,
                    'tujuan' => $request->tujuan,
                    'jumlah_liter' => $request->jumlah_liter,
                    'jumlah_harga' => $request->jumlah_harga,
                    'keterangan' => $request->keterangan,
                    'updated_at' => now()
                ]);

            DB::commit();

            return Redirect::back()->with('success', 'Data BBM Berhasil Diupdate');

        } catch (\Exception $e) {

            DB::rollback();
            return Redirect::back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        $bbm = DB::table('kontrol_bbm')->where('id', $id)->first();
        DB::beginTransaction();

        try {

            $cektutuplaporan = cektutupLaporan($bbm->tanggal, "worksheetom");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            DB::table('kontrol_bbm')
                ->where('id', $id)
                ->delete();
            DB::commit();
            return Redirect::back()->with('success', 'Data BBM Berhasil Dihapus');
        } catch (\Exception $e) {
            DB::rollback();
            return Redirect::back()->with('error', $e->getMessage());
        }
    }
    public function getKmTerakhir($kode_kendaraan)
    {
        $km = DB::table('kontrol_bbm')
            ->where('kode_kendaraan', $kode_kendaraan)
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        if ($km) {
            return response()->json([
                'status' => true,
                'km_akhir' => $km->kilometer_akhir
            ]);
        } else {
            return response()->json([
                'status' => false
            ]);
        }
    }

    public function cetak(Request $request)
    {
        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $cabang = Auth::user()->kode_cabang;
        $jenis_laporan = $request->jenis_laporan_search ?? 'detail';
        $query = DB::table('kontrol_bbm');
        if ($jenis_laporan == 'detail') {

            $query->select(
                'kontrol_bbm.*',
                'cabang.nama_cabang',
                'kendaraan.no_polisi',
                'driver_helper.nama_driver_helper',

                DB::raw('(kilometer_akhir - kilometer_awal) as jarak_tempuh'),

                DB::raw('
            CASE
                WHEN jumlah_liter > 0
                THEN ROUND((kilometer_akhir - kilometer_awal) / jumlah_liter,2)
                ELSE 0
            END as rasio_bbm
        ')
            );

        } else {
            $query->select(
                'kontrol_bbm.kode_kendaraan',
                'kendaraan.no_polisi',
                'cabang.nama_cabang',
                DB::raw('MIN(kilometer_awal) as km_awal'),
                DB::raw('MAX(kilometer_akhir) as km_akhir'),
                DB::raw('(MAX(kilometer_akhir) - MIN(kilometer_awal)) as total_km'),
                DB::raw('SUM(jumlah_liter) as total_liter'),
                DB::raw('SUM(jumlah_harga) as total_rupiah'),
                DB::raw('
            CASE
                WHEN SUM(jumlah_liter) > 0
                THEN ROUND((MAX(kilometer_akhir) - MIN(kilometer_awal)) / SUM(jumlah_liter),2)
                ELSE 0
            END as rasio_bbm
        ')
            );
        }
        $query->leftJoin('cabang', 'kontrol_bbm.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftJoin('kendaraan', 'kontrol_bbm.kode_kendaraan', '=', 'kendaraan.kode_kendaraan');
        $query->leftJoin('driver_helper', 'kontrol_bbm.kode_driver_helper', '=', 'driver_helper.kode_driver_helper');

        $query->when($request->filled('dari_search') && $request->filled('sampai_search'), function ($q) use ($request) {
            $q->whereBetween('tanggal', [$request->dari_search, $request->sampai_search]);
        });
        $query->when($request->filled('dari_search') && $request->filled('sampai_search'), function ($q) use ($request) {
            $q->whereBetween('tanggal', [$request->dari_search, $request->sampai_search]);
        });
        $query->when($request->filled('kode_kendaraan_search'), function ($q) use ($request) {
            $q->where('kontrol_bbm.kode_kendaraan', $request->kode_kendaraan_search);
        });
        $query->when($request->filled('kode_driver_helper_search'), function ($q) use ($request) {
            $q->where('kontrol_bbm.kode_driver_helper', $request->kode_driver_helper_search);
        });
        $query->when(Auth::user()->kode_cabang != 'PST', function ($q) {
                $q->where('kontrol_bbm.kode_cabang', Auth::user()->kode_cabang);
        });
        $query->when($request->filled('kode_cabang_search'), function ($q) use ($request) {
            $q->where('kontrol_bbm.kode_cabang', $request->kode_cabang_search);
        });
        if ($jenis_laporan == 'rekap') {

            $query->groupBy(
                'kontrol_bbm.kode_kendaraan',
                'kendaraan.no_polisi',
                'cabang.nama_cabang'
            );

        }
        $query->orderBy('kendaraan.no_polisi');
        $query->orderBy('tanggal');
        $data['bbm'] = $query->get();
        $data['kendaraan'] = DB::table('kendaraan')
            ->where('kode_kendaraan', $request->kode_kendaraan_search)
            ->first();
        $data['driver'] = DB::table('driver_helper')
            ->where('kode_driver_helper', $request->kode_driver_helper_search)
            ->first();
        $data['dari'] = $request->dari_search;
        $data['sampai'] = $request->sampai_search;
        $data['jenis_laporan'] = $jenis_laporan;
        $time = date('H:i:s');
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            header("Content-Disposition: attachment; filename=Laporan BBM Kendaraan $request->dari-$request->sampai - $time.xls");
        }
        return view('worksheetom.bbm.cetak', $data);
    }
}
