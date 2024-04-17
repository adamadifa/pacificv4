<?php

namespace App\Http\Controllers;

use App\Models\Barangmasukgudangbahan;
use App\Models\Barangpembelian;
use App\Models\Detailbarangmasukgudangbahan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class BarangmasukgudangbahanController extends Controller
{
    public function index(Request $request)
    {

        $start_date = config('global.start_date');
        $end_date = config('global.end_date');

        $query = Barangmasukgudangbahan::query();
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
        $barangmasuk = $query->simplePaginate(20);
        $barangmasuk->appends(request()->all());

        $data['barangmasuk'] = $barangmasuk;
        $data['asal_barang'] = config('gudangbahan.asal_barang_gudang_bahan');
        $data['list_asal_barang'] = config('gudangbahan.list_asal_barang');
        return view('gudangbahan.barangmasuk.index', $data);
    }


    public function create()
    {
        $data['barang'] = Barangpembelian::where('kode_group', 'GDB')->get();
        $data['list_asal_barang'] = config('gudangbahan.list_asal_barang');
        return view('gudangbahan.barangmasuk.create', $data);
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

            $cek_barang_masuk = Barangmasukgudangbahan::where('no_bukti', $request->no_bukti)->count();
            if ($cek_barang_masuk > 0) {
                return Redirect::back()->with(messageError('Data Sudah Ada !'));
            }
            //Simpan Data Repack

            Barangmasukgudangbahan::create([
                'no_bukti' => $request->no_bukti,
                'tanggal' => $request->tanggal,
                'kode_asal_barang' => $request->kode_asal_barang
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
                Detailbarangmasukgudangbahan::insert($chunk_buffer);
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
        $data['barangmasuk'] = Barangmasukgudangbahan::where('no_bukti', $no_bukti)->first();
        $data['detail'] = Detailbarangmasukgudangbahan::join('pembelian_barang', 'gudang_bahan_barang_masuk_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->where('no_bukti', $no_bukti)
            ->get();
        $data['barang'] = Barangpembelian::where('kode_group', 'GDB')->get();
        $data['list_asal_barang'] = config('gudangbahan.list_asal_barang');
        return view('gudangbahan.barangmasuk.edit', $data);
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

            $barangmasuk = Barangmasukgudangbahan::where('no_bukti', $no_bukti)->first();

            //Checking
            $cektutuplaporan_barangmasuk = cektutupLaporan($barangmasuk->tanggal, "gudangbahan");
            if ($cektutuplaporan_barangmasuk > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }


            $cektutuplaporan = cektutupLaporan($request->tanggal, "gudangbahan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            if (empty($kode_barang)) {
                return Redirect::back()->with(messageError('Data Detail Produk Masih Kosong !'));
            }

            $cek_barang_masuk = Barangmasukgudangbahan::where('no_bukti', $request->no_bukti)
                ->where('no_bukti', '!=', $no_bukti)
                ->count();
            if ($cek_barang_masuk > 0) {
                return Redirect::back()->with(messageError('Data Sudah Ada !'));
            }

            //Delete Detail
            Detailbarangmasukgudangbahan::where('no_bukti', $no_bukti)->delete();
            //Simpan Data
            Barangmasukgudangbahan::where('no_bukti', $no_bukti)->update([
                'no_bukti' => $request->no_bukti,
                'tanggal' => $request->tanggal,
                'kode_asal_barang' => $request->kode_asal_barang
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
                Detailbarangmasukgudangbahan::insert($chunk_buffer);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Di Update !');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function show($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $data['barangmasuk'] = Barangmasukgudangbahan::where('no_bukti', $no_bukti)->first();
        $data['detail'] = Detailbarangmasukgudangbahan::join('pembelian_barang', 'gudang_bahan_barang_masuk_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->where('no_bukti', $no_bukti)
            ->get();
        $data['asal_barang'] = config('gudangbahan.asal_barang_gudang_bahan');
        return view('gudangbahan.barangmasuk.show', $data);
    }


    public function destroy($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $barangmasuk = Barangmasukgudangbahan::where('no_bukti', $no_bukti)->first();
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($barangmasuk->tanggal, "gudangbahan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            //Hapus Surat Jalan
            Barangmasukgudangbahan::where('no_bukti', $no_bukti)->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
