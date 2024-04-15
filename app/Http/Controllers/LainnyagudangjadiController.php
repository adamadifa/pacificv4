<?php

namespace App\Http\Controllers;

use App\Models\Detailmutasigudangjadi;
use App\Models\Mutasigudangjadi;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class LainnyagudangjadiController extends Controller
{
    public function index(Request $request)
    {
        $start_year = config('global.start_year');
        $start_date = config('global.start_date');
        $end_date = config('global.end_date');


        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }

        $query = Mutasigudangjadi::query();
        $query->select('no_mutasi', 'tanggal', 'in_out', 'keterangan');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('gudang_jadi_mutasi.tanggal', [$request->dari, $request->sampai]);
        } else {
            $query->whereBetween('gudang_jadi_mutasi.tanggal', [$start_date, $end_date]);
        }

        $query->where('jenis_mutasi', 'LN');
        $query->orderBy('tanggal', 'desc');
        $lainnya = $query->paginate(15);
        $lainnya->appends($request->all());
        $data['lainnya'] = $lainnya;
        return view('gudangjadi.lainnya.index', $data);
    }

    public function create()
    {
        $data['produk'] = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        return view('gudangjadi.lainnya.create', $data);
    }

    public function store(Request $request)
    {

        $kode_produk = $request->kode_produk;
        $jml = $request->jml;
        DB::beginTransaction();
        try {
            //Buat Nomor Repack Reject
            $lastlainnya = Mutasigudangjadi::select('no_mutasi')
                ->where('jenis_mutasi', 'LN')
                ->where('tanggal', $request->tanggal)
                ->orderBy('tanggal', 'desc')
                ->first();

            $hari = date('d', strtotime($request->tanggal));
            $bulan = date('m', strtotime($request->tanggal));
            $tahun = date('Y', strtotime($request->tanggal));
            $tgl  = "." . $hari . "." . $bulan . "." . $tahun;
            $last_no_lainnya  =  $lastlainnya != null ? $lastlainnya->no_mutasi : '';
            $no_lainnya = buatkode($last_no_lainnya, "ML", 2) . $tgl;

            //Checking
            $cektutuplaporan = cektutupLaporan($request->tanggal, "gudangjadi");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            if (empty($kode_produk)) {
                return Redirect::back()->with(messageError('Data Detail Produk Masih Kosong !'));
            }

            $cek_lainnya = Mutasigudangjadi::where('no_mutasi', $no_lainnya)->count();
            if ($cek_lainnya > 0) {
                return Redirect::back()->with(messageError('Data Lainnya  Sudah Ada !'));
            }
            //Simpan Data Repack

            Mutasigudangjadi::create([
                'no_mutasi' => $no_lainnya,
                'tanggal' => $request->tanggal,
                'jenis_mutasi' => 'LN',
                'in_out' => $request->in_out,
                'keterangan' => $request->keterangan,
                'id_user' => auth()->user()->id
            ]);


            //Simpan Detail
            for ($i = 0; $i < count($kode_produk); $i++) {
                $detail[] = [
                    'no_mutasi' => $no_lainnya,
                    'kode_produk' => $kode_produk[$i],
                    'jumlah' => toNumber($jml[$i])
                ];
            }

            $timestamp = Carbon::now();

            foreach ($detail as &$record) {
                $record['created_at'] = $timestamp;
                $record['updated_at'] = $timestamp;
            }

            $chunks_buffer = array_chunk($detail, 5);
            foreach ($chunks_buffer as $chunk_buffer) {
                Detailmutasigudangjadi::insert($chunk_buffer);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Data Lainnya Berhasil Disimpan !');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show($no_mutasi)
    {
        $no_mutasi = Crypt::decrypt($no_mutasi);
        $data['lainnya'] = Mutasigudangjadi::where('no_mutasi', $no_mutasi)
            ->select(
                'gudang_jadi_mutasi.no_mutasi',
                'gudang_jadi_mutasi.tanggal',
                'gudang_jadi_mutasi.in_out',
                'gudang_jadi_mutasi.keterangan'
            )
            ->first();

        //Detail Repack
        $data['detail'] = Detailmutasigudangjadi::where('no_mutasi', $no_mutasi)
            ->join('produk', 'gudang_jadi_mutasi_detail.kode_produk', '=', 'produk.kode_produk')
            ->get();

        return view('gudangjadi.lainnya.show', $data);
    }

    public function edit($no_mutasi)
    {
        $no_mutasi = Crypt::decrypt($no_mutasi);
        $data['lainnya'] = Mutasigudangjadi::where('no_mutasi', $no_mutasi)
            ->select(
                'gudang_jadi_mutasi.no_mutasi',
                'gudang_jadi_mutasi.tanggal',
                'gudang_jadi_mutasi.in_out',
                'gudang_jadi_mutasi.keterangan'
            )
            ->first();

        //Detail Repack
        $data['detail'] = Detailmutasigudangjadi::where('no_mutasi', $no_mutasi)
            ->join('produk', 'gudang_jadi_mutasi_detail.kode_produk', '=', 'produk.kode_produk')
            ->get();

        $data['produk'] = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        return view('gudangjadi.lainnya.edit', $data);
    }


    public function update($no_lainnya_old, Request $request)
    {

        $no_lainnya_old = Crypt::decrypt($no_lainnya_old);
        $kode_produk = $request->kode_produk;
        $jml = $request->jml;
        DB::beginTransaction();
        try {
            //Buat Nomor Repack Reject
            $lastlainnya = Mutasigudangjadi::select('no_mutasi')
                ->where('jenis_mutasi', 'LN')
                ->where('tanggal', $request->tanggal)
                ->orderBy('tanggal', 'desc')
                ->first();

            $hari = date('d', strtotime($request->tanggal));
            $bulan = date('m', strtotime($request->tanggal));
            $tahun = date('Y', strtotime($request->tanggal));
            $tgl  = "." . $hari . "." . $bulan . "." . $tahun;
            $last_no_lainnya  =  $lastlainnya != null ? $lastlainnya->no_mutasi : '';
            $no_lainnya = buatkode($last_no_lainnya, "ML", 2) . $tgl;

            //Checking
            $cektutuplaporan = cektutupLaporan($request->tanggal, "gudangjadi");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            if (empty($kode_produk)) {
                return Redirect::back()->with(messageError('Data Detail Produk Masih Kosong !'));
            }

            $cek_lainnya = Mutasigudangjadi::where('no_mutasi', $no_lainnya)
                ->where('no_mutasi', '!=', $no_lainnya_old)
                ->count();
            if ($cek_lainnya > 0) {
                return Redirect::back()->with(messageError('Data Lainnya Sudah Ada !'));
            }

            //Hapus Detail
            Detailmutasigudangjadi::where('no_mutasi', $no_lainnya_old)->delete();

            //Simpan Data Repack

            Mutasigudangjadi::where('no_mutasi', $no_lainnya_old)->update([
                'no_mutasi' => $no_lainnya,
                'tanggal' => $request->tanggal,
                'in_out' => $request->in_out,
                'keterangan' => $request->keterangan,
                'id_user' => auth()->user()->id
            ]);


            //Simpan Detail
            for ($i = 0; $i < count($kode_produk); $i++) {
                $detail[] = [
                    'no_mutasi' => $no_lainnya,
                    'kode_produk' => $kode_produk[$i],
                    'jumlah' => toNumber($jml[$i])
                ];
            }

            $timestamp = Carbon::now();

            foreach ($detail as &$record) {
                $record['created_at'] = $timestamp;
                $record['updated_at'] = $timestamp;
            }

            $chunks_buffer = array_chunk($detail, 5);
            foreach ($chunks_buffer as $chunk_buffer) {
                Detailmutasigudangjadi::insert($chunk_buffer);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Data Lainnya Berhasil Diupdate !');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function destroy($no_mutasi)
    {
        $no_mutasi = Crypt::decrypt($no_mutasi);
        $reject = Mutasigudangjadi::where('no_mutasi', $no_mutasi)->first();
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($reject->tanggal, "gudangjadi");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            //Hapus Surat Jalan
            Mutasigudangjadi::where('no_mutasi', $no_mutasi)->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
