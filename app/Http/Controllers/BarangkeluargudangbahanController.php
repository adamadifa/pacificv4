<?php

namespace App\Http\Controllers;

use App\Models\Barangkeluargudangbahan;
use App\Models\Barangpembelian;
use App\Models\Cabang;
use App\Models\Detailbarangkeluargudangbahan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class BarangkeluargudangbahanController extends Controller
{
    public function index(Request $request)
    {

        $start_date = config('global.start_date');
        $end_date = config('global.end_date');

        $query = Barangkeluargudangbahan::query();
        $query->orderBy('tanggal', 'desc');
        $query->orderBy('created_at', 'desc');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
        } else {
            $query->whereBetween('tanggal', [$start_date, $end_date]);
        }

        if (!empty($request->no_bukti_search)) {
            $query->where('no_bukti', $request->no_bukti_search);
        }

        if (!empty($request->kode_asal_barang_search)) {
            $query->where('kode_asal_barang', $request->kode_asal_barang_search);
        }
        $barangkeluar = $query->simplePaginate(20);
        $barangkeluar->appends(request()->all());

        $data['barangkeluar'] = $barangkeluar;
        $data['jenis_pengeluaran'] = config('gudangbahan.jenis_pengeluaran');
        $data['list_jenis_pengeluaran'] = config('gudangbahan.list_jenis_pengeluaran');
        return view('gudangbahan.barangkeluar.index', $data);
    }

    public function create()
    {
        $data['barang'] = Barangpembelian::where('kode_group', 'GDB')->get();
        $data['list_jenis_pengeluaran'] = config('gudangbahan.list_jenis_pengeluaran');
        $data['cabang'] = Cabang::orderby('kode_cabang')->get();
        return view('gudangbahan.barangkeluar.create', $data);
    }

    public function store(Request $request)
    {

        $kode_barang = $request->kode_barang;
        $qty_unit = $request->qty_unit;
        $qty_berat = $request->qty_berat;
        $qty_lebih = $request->qty_lebih;
        $keterangan = $request->ket;
        DB::beginTransaction();
        try {

            //Checking
            $cektutuplaporan = cektutupLaporan($request->tanggal, "gudangbahan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            if (empty($kode_barang)) {
                return Redirect::back()->with(messageError('Data Detail Produk Masih Kosong !'));
            }

            $cek_barang_keluar = Barangkeluargudangbahan::where('no_bukti', $request->no_bukti)->count();
            if ($cek_barang_keluar > 0) {
                return Redirect::back()->with(messageError('Data Sudah Ada !'));
            }
            //Simpan Data Repack

            Barangkeluargudangbahan::create([
                'no_bukti' => $request->no_bukti,
                'tanggal' => $request->tanggal,
                'kode_jenis_pengeluaran' => $request->kode_jenis_pengeluaran,
                'keterangan' => $request->keterangan_barang_keluar,
                'kode_cabang' => $request->kode_jenis_pengeluaran == "CBG" ? $request->kode_cabang : NULL,
            ]);


            //Simpan Detail
            for ($i = 0; $i < count($kode_barang); $i++) {
                $detail[] = [
                    'no_bukti' => $request->no_bukti,
                    'kode_barang' => $kode_barang[$i],
                    'qty_unit' => toNumber($qty_unit[$i]),
                    'qty_berat' => toNumber($qty_berat[$i]),
                    'qty_lebih' => toNumber($qty_lebih[$i]),
                    'keterangan' => $keterangan[$i]
                ];
            }

            $timestamp = Carbon::now();

            foreach ($detail as &$record) {
                $record['created_at'] = $timestamp;
                $record['updated_at'] = $timestamp;
            }


            $chunks_buffer = array_chunk($detail, 5);
            foreach ($chunks_buffer as $chunk_buffer) {
                Detailbarangkeluargudangbahan::insert($chunk_buffer);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Disimpan !');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function edit()
    {
        $data['barang'] = Barangpembelian::where('kode_group', 'GDB')->get();
        $data['list_jenis_pengeluaran'] = config('gudangbahan.list_jenis_pengeluaran');
        $data['cabang'] = Cabang::orderby('kode_cabang')->get();
        return view('gudangbahan.barangkeluar.edit', $data);
    }
}
