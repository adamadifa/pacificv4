<?php

namespace App\Http\Controllers;

use App\Models\Barangmasukmaintenance;
use App\Models\Barangpembelian;
use App\Models\Detailbarangmasukmaintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class BarangmasukmaintenanceController extends Controller
{
    public function index(Request $request)
    {

        $bm = new Barangmasukmaintenance();
        $barangmasuk = $bm->getBarangmasuk(request: $request)->simplePaginate(15);
        $barangmasuk->appends(request()->all());
        $data['barangmasuk'] = $barangmasuk;

        return view('maintenance.barangmasuk.index', $data);
    }

    public function create()
    {
        $kodeBarang = DB::table('maintenance_barang_masuk_detail')
            ->pluck('kode_barang');

        $data['barang'] = Barangpembelian::whereIn('kode_barang', $kodeBarang)
            ->get();
        return view('maintenance.barangmasuk.create', $data);
    }

    public function show($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $bm = new Barangmasukmaintenance();
        $data['barangmasuk'] = $bm->getBarangmasuk($no_bukti)->first();

        $data['detail'] = Detailbarangmasukmaintenance::where('no_bukti', $no_bukti)
            ->select('maintenance_barang_masuk_detail.*', 'pembelian_barang.nama_barang', 'pembelian_barang.satuan')
            ->join('pembelian_barang', 'maintenance_barang_masuk_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->get();
        return view('maintenance.barangmasuk.show', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_bukti' => 'required|unique:maintenance_barang_masuk,no_bukti',
            'tanggal' => 'required|date',
            'kode_barang' => 'required|array',
            'jumlah' => 'required|array',
        ]);

        DB::beginTransaction();

        try {

            // ======================
            // SIMPAN HEADER
            // ======================
            DB::table('maintenance_barang_masuk')->insert([
                'no_bukti' => $request->no_bukti,
                'tanggal' => $request->tanggal,
                'status' => '2',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // ======================
            // SIMPAN DETAIL
            // ======================
            $detail = [];

            foreach ($request->kode_barang as $i => $kode) {
                $detail[] = [
                    'no_bukti' => $request->no_bukti,
                    'kode_barang' => $kode,
                    'jumlah' => str_replace('.', '', $request->jumlah[$i]),
                    'keterangan' => $request->keterangan[$i] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('maintenance_barang_masuk_detail')->insert($detail);

            DB::commit();

            return redirect()
                ->route('barangmasukmtc.index')
                ->with('success', 'Barang masuk maintenance berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }
}
