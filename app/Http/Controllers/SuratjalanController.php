<?php

namespace App\Http\Controllers;

use App\Models\Angkutan;
use App\Models\Detailmutasigudangjadi;
use App\Models\Detailpermintaankiriman;
use App\Models\Mutasigudangjadi;
use App\Models\Permintaankiriman;
use App\Models\Produk;
use App\Models\Suratjalanangkutan;
use App\Models\Tujuanangkutan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class SuratjalanController extends Controller
{
    public function index(Request $request)
    {
        $start_year = config('global.start_year');
        $start_date = $start_year . "-01-01";
        $end_date = date('Y-m-d');


        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }

        $query = Mutasigudangjadi::query();
        $query->select(
            'gudang_jadi_mutasi.no_mutasi',
            'gudang_jadi_mutasi.tanggal',
            'no_dok',
            'status_surat_jalan',
            'marketing_permintaan_kiriman.kode_cabang',
            'gudang_cabang_mutasi.tanggal as tgl_mutasi_cabang',
            'tanggal_transit_in'
        );
        $query->join('marketing_permintaan_kiriman', 'gudang_jadi_mutasi.no_permintaan', '=', 'marketing_permintaan_kiriman.no_permintaan');
        $query->leftJoin('gudang_cabang_mutasi', 'gudang_jadi_mutasi.no_mutasi', '=', 'gudang_cabang_mutasi.no_mutasi');
        $query->leftJoin(
            DB::raw("(
                SELECT no_surat_jalan,tanggal as tanggal_transit_in
                FROM gudang_cabang_mutasi
                WHERE jenis_mutasi ='TI'
            ) transitin"),
            function ($join) {
                $join->on('gudang_jadi_mutasi.no_mutasi', '=', 'transitin.no_surat_jalan');
            }
        );
        $query->where('gudang_jadi_mutasi.jenis_mutasi', 'SJ');

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('gudang_jadi_mutasi.tanggal', [$request->dari, $request->sampai]);
        } else {
            $query->whereBetween('gudang_jadi_mutasi.tanggal', [$start_date, $end_date]);
        }
        $query->orderBy('gudang_jadi_mutasi.tanggal', 'desc');
        $query->orderBy('gudang_jadi_mutasi.created_at', 'desc');
        $surat_jalan = $query->paginate(15);
        $surat_jalan->appends($request->all());
        $data['surat_jalan'] = $surat_jalan;
        return view('gudangjadi.suratjalan.index', $data);
    }

    public function create($no_permintaan)
    {
        $no_permintaan = Crypt::decrypt($no_permintaan);
        $data['tujuan_angkutan'] = Tujuanangkutan::orderBy('kode_tujuan')->get();
        $data['angkutan'] = Angkutan::orderBy('kode_angkutan')->get();
        $data['pk'] = Permintaankiriman::where('no_permintaan', $no_permintaan)
            ->join('cabang', 'marketing_permintaan_kiriman.kode_cabang', '=', 'cabang.kode_cabang')
            ->leftJoin('salesman', 'marketing_permintaan_kiriman.kode_salesman', '=', 'salesman.kode_salesman')
            ->first();
        $data['detail'] = Detailpermintaankiriman::select('marketing_permintaan_kiriman_detail.kode_produk', 'nama_produk', 'jumlah')
            ->join('produk', 'marketing_permintaan_kiriman_detail.kode_produk', '=', 'produk.kode_produk')
            ->where('no_permintaan', $no_permintaan)
            ->orderBy('marketing_permintaan_kiriman_detail.kode_produk')
            ->get();
        $data['produk'] = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        return view('gudangjadi.suratjalan.create', $data);
    }


    public function store($no_permintaan, Request $request)
    {
        $no_permintaan = Crypt::decrypt($no_permintaan);
        $kode_produk = $request->kode_produk;
        $jml = $request->jml;


        DB::beginTransaction();
        try {
            //Buat No. Surat Jalan
            $pk = Permintaankiriman::where('no_permintaan', $no_permintaan)->first();
            $kode = strlen($pk->kode_cabang);
            $jmlkarakter_no_surat_jalan = $kode + 4;
            $last_surat_jalan = Mutasigudangjadi::select(
                DB::raw('LEFT(no_mutasi,' . $jmlkarakter_no_surat_jalan . ') as no_surat_jalan')
            )
                ->whereRaw('MID(no_mutasi,3,' . $kode . ')="' . $pk->kode_cabang . '"')
                ->where('tanggal', $request->tanggal)
                ->where('jenis_mutasi', 'SJ')
                ->orderByRaw('LEFT(no_mutasi,' . $jmlkarakter_no_surat_jalan . ') desc')
                ->first();



            $hari = date('d', strtotime($request->tanggal));
            $bulan = date('m', strtotime($request->tanggal));
            $tahun = date('Y', strtotime($request->tanggal));

            $format = "." . $hari . "." . $bulan . "." . $tahun;
            $last_no_surat_jalan  = $last_surat_jalan != null ? $last_surat_jalan->no_surat_jalan : '';
            $no_surat_jalan = buatkode($last_no_surat_jalan, "SJ" . $pk->kode_cabang, 2) . $format;

            $cektutuplaporan = cektutupLaporan($request->tanggal, "gudangjadi");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            if (empty($kode_produk)) {
                return Redirect::back()->with(messageError('Data Detail Produk Masih Kosong !'));
            }

            $cek_dokumen = Mutasigudangjadi::where('no_dok', $request->no_dok)->count();
            if ($cek_dokumen > 0) {
                return Redirect::back()->with(messageError('No. Dokumen Sudah Ada !'));
            }

            $cek_surat_jalan = Mutasigudangjadi::where('no_mutasi', $no_surat_jalan)->count();
            if ($cek_surat_jalan > 0) {
                return Redirect::back()->with(messageError('Data Surat Jalan Sudah Ada !'));
            }

            for ($i = 0; $i < count($kode_produk); $i++) {
                $detail[] = [
                    'no_mutasi' => $no_surat_jalan,
                    'kode_produk' => $kode_produk[$i],
                    'jumlah' => toNumber($jml[$i])
                ];
            }

            $timestamp = Carbon::now();

            foreach ($detail as &$record) {
                $record['created_at'] = $timestamp;
                $record['updated_at'] = $timestamp;
            }

            //Simpan Data Surat Jalan
            Mutasigudangjadi::create([
                'no_mutasi' => $no_surat_jalan,
                'tanggal' => $request->tanggal,
                'no_dok' => textUpperCase($request->no_dok),
                'no_permintaan' => $no_permintaan,
                'jenis_mutasi' => 'SJ',
                'in_out' => 'O',
                'status_surat_jalan' => 0,
                'id_user' => auth()->user()->id,
            ]);

            //Simpan Detail Surat Jalan

            $chunks_buffer = array_chunk($detail, 5);
            foreach ($chunks_buffer as $chunk_buffer) {
                Detailmutasigudangjadi::insert($chunk_buffer);
            }

            //Update Status Permintaan Kiriman
            Permintaankiriman::where('no_permintaan', $no_permintaan)->update([
                'status' => 1,
            ]);

            //Simpan Data Surat Jalan Angkutan Jika Angkutan disii

            if (isset($request->kode_angkutan)) {
                Suratjalanangkutan::create([
                    'no_dok' => textUpperCase($request->no_dok),
                    'no_polisi' => $request->no_polisi,
                    'tarif' => toNumber($request->tarif),
                    'tepung' => toNumber($request->tepung),
                    'bs' => toNumber($request->bs),

                ]);
            }


            DB::commit();
            return redirect()->back()->with('success', 'Data Surat Jalan Berhasil Disimpan !');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function destroy($no_mutasi)
    {
        $no_mutasi = Crypt::decrypt($no_mutasi);
        $surat_jalan = Mutasigudangjadi::where('no_mutasi', $no_mutasi)->first();
        try {
            $cektutuplaporan = cektutupLaporan($surat_jalan->tanggal, "gudangjadi");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            //Hapus Surat Jalan
            Mutasigudangjadi::where('no_mutasi', $no_mutasi)->delete();
            //Update Status Permintaan Pengiriman
            Permintaankiriman::where('no_permintaan', $surat_jalan->no_permintaan)->update([
                'status' => 0,
            ]);
            //Hapus Surat Jalan Angkutan
            Suratjalanangkutan::where('no_dok', $surat_jalan->no_dok)->delete();

            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
