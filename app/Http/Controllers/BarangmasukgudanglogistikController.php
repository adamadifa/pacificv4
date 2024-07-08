<?php

namespace App\Http\Controllers;

use App\Models\Barangmasukgudanglogistik;
use App\Models\Barangpembelian;
use App\Models\Detailbarangmasukgudanglogistik;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class BarangmasukgudanglogistikController extends Controller
{
    public function index(Request $request)
    {

        $start_date = config('global.start_date');
        $end_date = config('global.end_date');

        $query = Barangmasukgudanglogistik::query();
        $query->select('gudang_logistik_barang_masuk.*', 'pembelian.tanggal as tanggal_pembelian', 'nama_supplier');
        $query->leftJoin('pembelian', 'gudang_logistik_barang_masuk.no_bukti', '=', 'pembelian.no_bukti');
        $query->leftJoin('supplier', 'pembelian.kode_supplier', '=', 'supplier.kode_supplier');
        $query->orderBy('gudang_logistik_barang_masuk.tanggal', 'desc');
        $query->orderBy('gudang_logistik_barang_masuk.created_at', 'desc');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('gudang_logistik_barang_masuk.tanggal', [$request->dari, $request->sampai]);
        } else {
            $query->whereBetween('gudang_logistik_barang_masuk.tanggal', [$start_date, $end_date]);
        }

        if (!empty($request->no_bukti_search)) {
            $query->where('no_bukti', $request->no_bukti_search);
        }


        $barangmasuk = $query->simplePaginate(20);
        $barangmasuk->appends(request()->all());
        $data['barangmasuk'] = $barangmasuk;
        return view('gudanglogistik.barangmasuk.index', $data);
    }

    public function create()
    {
        $data['barang'] = Barangpembelian::where('kode_group', 'GDL')->get();
        return view('gudanglogistik.barangmasuk.create', $data);
    }

    public function store(Request $request)
    {

        $kode_barang = $request->kode_barang;
        $jml = $request->jml;
        $harga = $request->harga;
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

            $cek_barang_masuk = Barangmasukgudanglogistik::where('no_bukti', $request->no_bukti)->count();
            if ($cek_barang_masuk > 0) {
                return Redirect::back()->with(messageError('Data Sudah Ada !'));
            }
            //Simpan Data Repack

            Barangmasukgudanglogistik::create([
                'no_bukti' => $request->no_bukti,
                'tanggal' => $request->tanggal,
            ]);


            //Simpan Detail
            for ($i = 0; $i < count($kode_barang); $i++) {
                $detail[] = [
                    'no_bukti' => $request->no_bukti,
                    'kode_barang' => $kode_barang[$i],
                    'jumlah' => toNumber($jml[$i]),
                    'harga' => toNumber($harga[$i]),
                    'penyesuaian' => 0,
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
                Detailbarangmasukgudanglogistik::insert($chunk_buffer);
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
        $data['barangmasuk'] = Barangmasukgudanglogistik::where('no_bukti', $no_bukti)->first();
        $data['detail'] = Detailbarangmasukgudanglogistik::join('pembelian_barang', 'gudang_logistik_barang_masuk_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->where('no_bukti', $no_bukti)->get();
        $data['barang'] = Barangpembelian::where('kode_group', 'GDL')->get();
        return view('gudanglogistik.barangmasuk.edit', $data);
    }

    public function update($no_bukti, Request $request)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $kode_barang = $request->kode_barang;
        $jml = $request->jml;
        $harga = $request->harga;
        $keterangan = $request->ket;
        DB::beginTransaction();
        try {

            $barangmasuk = Barangmasukgudanglogistik::where('no_bukti', $no_bukti)->first();

            //Checking
            $cektutuplaporan_barangmasuk = cektutupLaporan($barangmasuk->tanggal, "gudanglogistik");
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

            $cek_barang_masuk = Barangmasukgudanglogistik::where('no_bukti', $request->no_bukti)
                ->where('no_bukti', '!=', $no_bukti)
                ->count();
            if ($cek_barang_masuk > 0) {
                return Redirect::back()->with(messageError('Data Sudah Ada !'));
            }

            //Delete Detail
            Detailbarangmasukgudanglogistik::where('no_bukti', $no_bukti)->delete();
            //Simpan Data
            Barangmasukgudanglogistik::where('no_bukti', $no_bukti)->update([
                'no_bukti' => $request->no_bukti,
                'tanggal' => $request->tanggal,
            ]);


            //Simpan Detail
            for ($i = 0; $i < count($kode_barang); $i++) {
                $detail[] = [
                    'no_bukti' => $request->no_bukti,
                    'kode_barang' => $kode_barang[$i],
                    'jumlah' => toNumber($jml[$i]),
                    'harga' => toNumber($harga[$i]),
                    'penyesuaian' => 0,
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
                Detailbarangmasukgudanglogistik::insert($chunk_buffer);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Di Update !');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $barangmasuk = Barangmasukgudanglogistik::where('no_bukti', $no_bukti)->first();
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($barangmasuk->tanggal, "gudanglogistik");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            //Hapus Surat Jalan
            Barangmasukgudanglogistik::where('no_bukti', $no_bukti)->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function show($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $data['barangmasuk'] = Barangmasukgudanglogistik::where('gudang_logistik_barang_masuk.no_bukti', $no_bukti)
            ->select('gudang_logistik_barang_masuk.*', 'pembelian.kode_supplier', 'nama_supplier')
            ->leftJoin('pembelian', 'gudang_logistik_barang_masuk.no_bukti', '=', 'pembelian.no_bukti')
            ->leftJoin('supplier', 'pembelian.kode_supplier', '=', 'supplier.kode_supplier')
            ->first();
        $data['detail'] = Detailbarangmasukgudanglogistik::join('pembelian_barang', 'gudang_logistik_barang_masuk_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->where('no_bukti', $no_bukti)
            ->get();

        return view('gudanglogistik.barangmasuk.show', $data);
    }
}
