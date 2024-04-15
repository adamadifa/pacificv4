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

class RejectgudangjadiController extends Controller
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
        $query->select('no_mutasi', 'tanggal');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('gudang_jadi_mutasi.tanggal', [$request->dari, $request->sampai]);
        } else {
            $query->whereBetween('gudang_jadi_mutasi.tanggal', [$start_date, $end_date]);
        }

        $query->where('jenis_mutasi', 'RJ');
        $query->orderBy('tanggal', 'desc');
        $reject = $query->paginate(15);
        $reject->appends($request->all());
        $data['reject'] = $reject;
        return view('gudangjadi.reject.index', $data);
    }

    public function create()
    {
        $data['produk'] = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        return view('gudangjadi.reject.create', $data);
    }

    public function store(Request $request)
    {

        $kode_produk = $request->kode_produk;
        $jml = $request->jml;
        DB::beginTransaction();
        try {
            //Buat Nomor  Reject
            $lastreject = Mutasigudangjadi::select('no_mutasi')
                ->where('jenis_mutasi', 'RJ')
                ->where('tanggal', $request->tanggal)
                ->orderBy('tanggal', 'desc')
                ->first();

            $hari = date('d', strtotime($request->tanggal));
            $bulan = date('m', strtotime($request->tanggal));
            $tahun = date('Y', strtotime($request->tanggal));
            $tgl  = "." . $hari . "." . $bulan . "." . $tahun;
            $last_no_reject  =  $lastreject != null ? $lastreject->no_mutasi : '';
            $no_reject = buatkode($last_no_reject, "RJ", 2) . $tgl;

            //Checking
            $cektutuplaporan = cektutupLaporan($request->tanggal, "gudangjadi");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            if (empty($kode_produk)) {
                return Redirect::back()->with(messageError('Data Detail Produk Masih Kosong !'));
            }

            $cek_reject = Mutasigudangjadi::where('no_mutasi', $no_reject)->count();
            if ($cek_reject > 0) {
                return Redirect::back()->with(messageError('Data Reject Sudah Ada !'));
            }
            //Simpan Data Reject

            Mutasigudangjadi::create([
                'no_mutasi' => $no_reject,
                'tanggal' => $request->tanggal,
                'jenis_mutasi' => 'RJ',
                'in_out' => 'O',
                'id_user' => auth()->user()->id
            ]);


            //Simpan Detail
            for ($i = 0; $i < count($kode_produk); $i++) {
                $detail[] = [
                    'no_mutasi' => $no_reject,
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
            return redirect()->back()->with('success', 'Data Reject Berhasil Disimpan !');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show($no_mutasi)
    {
        $no_mutasi = Crypt::decrypt($no_mutasi);
        $data['reject'] = Mutasigudangjadi::where('no_mutasi', $no_mutasi)
            ->select(
                'gudang_jadi_mutasi.no_mutasi',
                'gudang_jadi_mutasi.tanggal',
            )
            ->first();

        //Detail Reject
        $data['detail'] = Detailmutasigudangjadi::where('no_mutasi', $no_mutasi)
            ->join('produk', 'gudang_jadi_mutasi_detail.kode_produk', '=', 'produk.kode_produk')
            ->get();

        return view('gudangjadi.reject.show', $data);
    }

    public function edit($no_mutasi)
    {
        $no_mutasi = Crypt::decrypt($no_mutasi);
        $data['reject'] = Mutasigudangjadi::where('no_mutasi', $no_mutasi)
            ->select(
                'gudang_jadi_mutasi.no_mutasi',
                'gudang_jadi_mutasi.tanggal',
            )
            ->first();

        //Detail Reject
        $data['detail'] = Detailmutasigudangjadi::where('no_mutasi', $no_mutasi)
            ->join('produk', 'gudang_jadi_mutasi_detail.kode_produk', '=', 'produk.kode_produk')
            ->get();

        $data['produk'] = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        return view('gudangjadi.reject.edit', $data);
    }


    public function update($no_reject_old, Request $request)
    {

        $no_reject_old = Crypt::decrypt($no_reject_old);
        $kode_produk = $request->kode_produk;
        $jml = $request->jml;
        DB::beginTransaction();
        try {
            //Buat Nomor Reject Reject
            $lastreject = Mutasigudangjadi::select('no_mutasi')
                ->where('jenis_mutasi', 'RJ')
                ->where('tanggal', $request->tanggal)
                ->orderBy('tanggal', 'desc')
                ->first();

            $hari = date('d', strtotime($request->tanggal));
            $bulan = date('m', strtotime($request->tanggal));
            $tahun = date('Y', strtotime($request->tanggal));
            $tgl  = "." . $hari . "." . $bulan . "." . $tahun;
            $last_no_reject  =  $lastreject != null ? $lastreject->no_mutasi : '';
            $no_reject = buatkode($last_no_reject, "RJ", 2) . $tgl;

            //Checking
            $cektutuplaporan = cektutupLaporan($request->tanggal, "gudangjadi");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            if (empty($kode_produk)) {
                return Redirect::back()->with(messageError('Data Detail Produk Masih Kosong !'));
            }

            $cek_reject = Mutasigudangjadi::where('no_mutasi', $no_reject)
                ->where('no_mutasi', '!=', $no_reject_old)
                ->count();
            if ($cek_reject > 0) {
                return Redirect::back()->with(messageError('Data Reject Sudah Ada !'));
            }

            //Hapus Detail
            Detailmutasigudangjadi::where('no_mutasi', $no_reject_old)->delete();

            //Simpan Data Reject

            Mutasigudangjadi::where('no_mutasi', $no_reject_old)->update([
                'no_mutasi' => $no_reject,
                'tanggal' => $request->tanggal,
                'in_out' => 'O',
                'id_user' => auth()->user()->id
            ]);


            //Simpan Detail
            for ($i = 0; $i < count($kode_produk); $i++) {
                $detail[] = [
                    'no_mutasi' => $no_reject,
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
            return redirect()->back()->with('success', 'Data Reject Berhasil Diupdate !');
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
