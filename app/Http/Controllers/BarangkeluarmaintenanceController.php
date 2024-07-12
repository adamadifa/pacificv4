<?php

namespace App\Http\Controllers;

use App\Models\Barangkeluarmaintenance;
use App\Models\Barangpembelian;
use App\Models\Departemen;
use App\Models\Detailbarangkeluarmaintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class BarangkeluarmaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $bk = new Barangkeluarmaintenance();
        $barangkeluar = $bk->getBarangKeluar(request: $request)->cursorPaginate();
        $barangkeluar->appends(request()->all());
        $data['barangkeluar'] = $barangkeluar;
        return view('maintenance.barangkeluar.index', $data);
    }

    public function create()
    {
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        $data['barang'] = Barangpembelian::whereIn('kode_barang', ['GA-002', 'GA-007', 'GA-588'])->get();
        return view('maintenance.barangkeluar.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_bukti' => 'required',
            'tanggal' => 'required',
            'kode_dept' => 'required',
        ]);

        $kode_barang = $request->kode_barang_item;
        $jumlah = $request->jumlah_item;
        $keterangan = $request->keterangan_item;
        DB::beginTransaction();
        try {
            $cektutulaporan = cektutupLaporan($request->tanggal, "maintenance");
            if ($cektutulaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            Barangkeluarmaintenance::create([
                'no_bukti' => $request->no_bukti,
                'tanggal' => $request->tanggal,
                'kode_dept' => $request->kode_dept
            ]);
            for ($i = 0; $i < count($kode_barang); $i++) {
                $detail[] = [
                    'no_bukti' => $request->no_bukti,
                    'kode_barang' => $kode_barang[$i],
                    'keterangan' => $keterangan[$i],
                    'jumlah' => toNumber($jumlah[$i])
                ];
            }

            Detailbarangkeluarmaintenance::insert($detail);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            //dd($e);
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
    public function show($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $bk = new Barangkeluarmaintenance();
        $data['barangkeluar'] = $bk->getBarangkeluar($no_bukti)->first();

        $data['detail'] = Detailbarangkeluarmaintenance::where('no_bukti', $no_bukti)
            ->join('pembelian_barang', 'maintenance_barang_keluar_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->get();

        return view('maintenance.barangkeluar.show', $data);
    }

    public function destroy($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $barangkeluar = Barangkeluarmaintenance::where('no_bukti', $no_bukti)->first();
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($barangkeluar->tanggal, "maintenance");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            //Hapus Surat Jalan
            Barangkeluarmaintenance::where('no_bukti', $no_bukti)->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
