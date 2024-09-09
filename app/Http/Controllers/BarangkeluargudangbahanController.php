<?php

namespace App\Http\Controllers;

use App\Models\Barangkeluargudangbahan;
use App\Models\Barangpembelian;
use App\Models\Cabang;
use App\Models\Detailbarangkeluargudangbahan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class BarangkeluargudangbahanController extends Controller
{
    public function index(Request $request)
    {

        $start_date = config('global.start_date');
        $end_date = config('global.end_date');

        $query = Barangkeluargudangbahan::query();
        $query->leftJoin('cabang', 'gudang_bahan_barang_keluar.kode_cabang', '=', 'cabang.kode_cabang');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
        } else {
            $query->whereBetween('tanggal', [$start_date, $end_date]);
        }

        if (!empty($request->no_bukti_search)) {
            $query->where('no_bukti', $request->no_bukti_search);
        }

        if (!empty($request->kode_jenis_pengeluaran_search)) {
            $query->where('kode_jenis_pengeluaran', $request->kode_jenis_pengeluaran_search);
        }
        $query->orderBy('tanggal', 'desc');
        $query->orderBy('gudang_bahan_barang_keluar.created_at', 'desc');
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
            //Simpan Data
            if ($request->kode_jenis_pengeluaran == "PRD") {
                $keterangan_barang_keluar = $request->unit;
            } else if ($request->kode_jenis_pengeluaran == "CBG") {
                $keterangan_barang_keluar = NULL;
            } else {
                $keterangan_barang_keluar = $request->keterangan_barang_keluar;
            }

            $bulan = date('m', strtotime($request->tanggal));
            $tahun = date('Y', strtotime($request->tanggal));
            $thn = substr($tahun, 2, 2);
            $dari = $tahun . "-" . $bulan . "-01";
            $sampai = date("Y-m-t", strtotime($dari));
            $lastpengeluaran = Barangkeluargudangbahan::select('no_bukti')
                ->whereBetween('tanggal', [$dari, $sampai])
                ->orderBy('no_bukti', 'desc')
                ->first();
            $last_no_bukti = $lastpengeluaran != null ? $lastpengeluaran->no_bukti : '';
            $no_bukti = buatkode($last_no_bukti, 'GBK/' . $bulan . $thn . "/", 3);


            Barangkeluargudangbahan::create([
                'no_bukti' => $no_bukti,
                'tanggal' => $request->tanggal,
                'kode_jenis_pengeluaran' => $request->kode_jenis_pengeluaran,
                'keterangan' => $keterangan_barang_keluar,
                'kode_cabang' => $request->kode_jenis_pengeluaran == "CBG" ? $request->kode_cabang : NULL,
            ]);


            //Simpan Detail
            for ($i = 0; $i < count($kode_barang); $i++) {
                $detail[] = [
                    'no_bukti' => $no_bukti,
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


    public function edit($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $data['barangkeluar'] = Barangkeluargudangbahan::where('no_bukti', $no_bukti)->first();
        $data['detail'] = Detailbarangkeluargudangbahan::join('pembelian_barang', 'gudang_bahan_barang_keluar_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->where('no_bukti', $no_bukti)
            ->get();
        $data['barang'] = Barangpembelian::where('kode_group', 'GDB')->get();
        $data['list_jenis_pengeluaran'] = config('gudangbahan.list_jenis_pengeluaran');
        $data['cabang'] = Cabang::orderby('kode_cabang')->get();
        return view('gudangbahan.barangkeluar.edit', $data);
    }

    public function update($no_bukti, Request $request)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $kode_barang = $request->kode_barang;
        $qty_unit = $request->qty_unit;
        $qty_berat = $request->qty_berat;
        $qty_lebih = $request->qty_lebih;
        $keterangan = $request->ket;
        DB::beginTransaction();
        try {

            $barangkeluar = Barangkeluargudangbahan::where('no_bukti', $no_bukti)->first();

            //Checking
            $cektutuplaporan_barangkeluar = cektutupLaporan($barangkeluar->tanggal, "gudangbahan");
            if ($cektutuplaporan_barangkeluar > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            //Checking
            $cektutuplaporan = cektutupLaporan($request->tanggal, "gudangbahan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            if (empty($kode_barang)) {
                return Redirect::back()->with(messageError('Data Detail Produk Masih Kosong !'));
            }

            $cek_barang_keluar = Barangkeluargudangbahan::where('no_bukti', $request->no_bukti)
                ->where('no_bukti', '!=', $no_bukti)
                ->count();
            if ($cek_barang_keluar > 0) {
                return Redirect::back()->with(messageError('Data Sudah Ada !'));
            }

            //Hapus Detail
            Detailbarangkeluargudangbahan::where('no_bukti', $no_bukti)->delete();
            //Simpan Data
            if ($request->kode_jenis_pengeluaran == "PRD") {
                $keterangan_barang_keluar = $request->unit;
            } else if ($request->kode_jenis_pengeluaran == "CBG") {
                $keterangan_barang_keluar = NULL;
            } else {
                $keterangan_barang_keluar = $request->keterangan_barang_keluar;
            }
            Barangkeluargudangbahan::where('no_bukti', $no_bukti)->update([
                'no_bukti' => $request->no_bukti,
                'tanggal' => $request->tanggal,
                'kode_jenis_pengeluaran' => $request->kode_jenis_pengeluaran,
                'keterangan' => $keterangan_barang_keluar,
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

    public function show($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $data['barangkeluar'] = Barangkeluargudangbahan::leftJoin('cabang', 'gudang_bahan_barang_keluar.kode_cabang', '=', 'cabang.kode_cabang')
            ->where('no_bukti', $no_bukti)->first();
        $data['detail'] = Detailbarangkeluargudangbahan::join('pembelian_barang', 'gudang_bahan_barang_keluar_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->where('no_bukti', $no_bukti)
            ->get();
        $data['jenis_pengeluaran'] = config('gudangbahan.jenis_pengeluaran');
        return view('gudangbahan.barangkeluar.show', $data);
    }

    public function destroy($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $barangkeluar = Barangkeluargudangbahan::where('no_bukti', $no_bukti)->first();
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($barangkeluar->tanggal, "gudangbahan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            //Hapus Surat Jalan
            Barangkeluargudangbahan::where('no_bukti', $no_bukti)->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
