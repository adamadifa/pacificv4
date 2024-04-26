<?php

namespace App\Http\Controllers;

use App\Models\Barangkeluargudanglogistik;
use App\Models\Barangpembelian;
use App\Models\Cabang;
use App\Models\Detailbarangkeluargudanglogistik;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class BarangkeluargudanglogistikController extends Controller
{
    public function index(Request $request)
    {

        $start_date = config('global.start_date');
        $end_date = config('global.end_date');

        $query = Barangkeluargudanglogistik::query();
        $query->select('gudang_logistik_barang_keluar.*');
        $query->orderBy('gudang_logistik_barang_keluar.tanggal', 'desc');
        $query->orderBy('gudang_logistik_barang_keluar.created_at', 'desc');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('gudang_logistik_barang_keluar.tanggal', [$request->dari, $request->sampai]);
        } else {
            $query->whereBetween('gudang_logistik_barang_keluar.tanggal', [$start_date, $end_date]);
        }

        if (!empty($request->no_bukti_search)) {
            $query->where('no_bukti', $request->no_bukti_search);
        }

        if (!empty($request->kode_jenis_pengeluaran)) {
            $query->where('kode_jenis_pengeluaran', $request->kode_jenis_pengeluaran);
        }

        $barangkeluar = $query->simplePaginate(20);
        $barangkeluar->appends(request()->all());
        $data['barangkeluar'] = $barangkeluar;
        $data['list_jenis_pengeluaran'] = config('gudanglogistik.blade.list_jenis_pengeluaran');
        $data['jenis_pengeluaran'] = config('gudanglogistik.blade.jenis_pengeluaran');
        return view('gudanglogistik.barangkeluar.index', $data);
    }


    public function create()
    {
        $data['barang'] = Barangpembelian::where('kode_group', 'GDL')->get();
        $data['list_jenis_pengeluaran'] = config('gudanglogistik.blade.list_jenis_pengeluaran');
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        return view('gudanglogistik.barangkeluar.create', $data);
    }

    public function store(Request $request)
    {

        $kode_barang = $request->kode_barang;
        $jml = $request->jml;
        $kode_cbg = $request->kode_cbg;
        $keterangan = $request->ket;
        DB::beginTransaction();
        try {

            //Checking
            $cektutuplaporan = cektutupLaporan($request->tanggal, "gudanglogistik");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            if (empty($kode_barang)) {
                return Redirect::back()->with(messageError('Data Detail Barang Masih Kosong !'));
            }

            $cek_barang_keluar = Barangkeluargudanglogistik::where('no_bukti', $request->no_bukti)->count();
            if ($cek_barang_keluar > 0) {
                return Redirect::back()->with(messageError('Data Sudah Ada !'));
            }
            //Simpan Data Repack

            Barangkeluargudanglogistik::create([
                'no_bukti' => $request->no_bukti,
                'tanggal' => $request->tanggal,
                'kode_jenis_pengeluaran' => $request->kode_jenis_pengeluaran,
                'kode_cabang' => $request->kode_jenis_pengeluaran == "CBG" ? $request->kode_cabang : NULL,
            ]);


            //Simpan Detail
            for ($i = 0; $i < count($kode_barang); $i++) {
                $detail[] = [
                    'no_bukti' => $request->no_bukti,
                    'kode_barang' => $kode_barang[$i],
                    'jumlah' => toNumber($jml[$i]),
                    'kode_cabang' => $kode_cbg[$i],
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
                Detailbarangkeluargudanglogistik::insert($chunk_buffer);
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
        $data['barangkeluar'] = Barangkeluargudanglogistik::where('no_bukti', $no_bukti)->first();
        $data['detail'] = Detailbarangkeluargudanglogistik::join('pembelian_barang', 'gudang_logistik_barang_keluar_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->leftJoin('cabang', 'gudang_logistik_barang_keluar_detail.kode_cabang', '=', 'cabang.kode_cabang')
            ->where('no_bukti', $no_bukti)->get();
        $data['barang'] = Barangpembelian::where('kode_group', 'GDL')->get();
        $data['list_jenis_pengeluaran'] = config('gudanglogistik.blade.list_jenis_pengeluaran');
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        return view('gudanglogistik.barangkeluar.edit', $data);
    }

    public function update($no_bukti, Request $request)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $kode_barang = $request->kode_barang;
        $jml = $request->jml;
        $kode_cbg = $request->kode_cbg;
        $keterangan = $request->ket;
        DB::beginTransaction();
        try {

            $barangkeluar = Barangkeluargudanglogistik::where('no_bukti', $no_bukti)->first();

            //Checking
            $cektutuplaporan_barangmasuk = cektutupLaporan($barangkeluar->tanggal, "gudanglogistik");
            if ($cektutuplaporan_barangmasuk > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }


            $cektutuplaporan = cektutupLaporan($request->tanggal, "gudanglogistik");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            if (empty($kode_barang)) {
                return Redirect::back()->with(messageError('Data Detail Produk Masih Kosong !'));
            }

            $cek_barang_keluar = Barangkeluargudanglogistik::where('no_bukti', $request->no_bukti)
                ->where('no_bukti', '!=', $no_bukti)
                ->count();
            if ($cek_barang_keluar > 0) {
                return Redirect::back()->with(messageError('Data Sudah Ada !'));
            }

            //Delete Detail
            Detailbarangkeluargudanglogistik::where('no_bukti', $no_bukti)->delete();
            //Simpan Data
            Barangkeluargudanglogistik::where('no_bukti', $no_bukti)->update([
                'no_bukti' => $request->no_bukti,
                'tanggal' => $request->tanggal,
                'kode_jenis_pengeluaran' => $request->kode_jenis_pengeluaran,
                'kode_cabang' => $request->kode_jenis_pengeluaran == "CBG" ? $request->kode_cabang : NULL,
            ]);


            //Simpan Detail
            for ($i = 0; $i < count($kode_barang); $i++) {
                $detail[] = [
                    'no_bukti' => $request->no_bukti,
                    'kode_barang' => $kode_barang[$i],
                    'jumlah' => toNumber($jml[$i]),
                    'kode_cabang' => $kode_cbg[$i],
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
                Detailbarangkeluargudanglogistik::insert($chunk_buffer);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Di Update !');
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $data['barangkeluar'] = Barangkeluargudanglogistik::where('gudang_logistik_barang_keluar.no_bukti', $no_bukti)
            ->leftJoin('cabang', 'gudang_logistik_barang_keluar.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();
        $data['detail'] = Detailbarangkeluargudanglogistik::where('no_bukti', $no_bukti)
            ->join('pembelian_barang', 'gudang_logistik_barang_keluar_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->leftJoin('cabang', 'gudang_logistik_barang_keluar_detail.kode_cabang', '=', 'cabang.kode_cabang')
            ->get();
        $data['jenis_pengeluaran'] = config('gudanglogistik.blade.jenis_pengeluaran');
        return view('gudanglogistik.barangkeluar.show', $data);
    }


    public function destroy($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $barangkeluar = Barangkeluargudanglogistik::where('no_bukti', $no_bukti)->first();
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($barangkeluar->tanggal, "gudanglogistik");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            //Hapus Surat Jalan
            Barangkeluargudanglogistik::where('no_bukti', $no_bukti)->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
