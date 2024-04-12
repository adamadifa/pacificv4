<?php

namespace App\Http\Controllers;

use App\Models\Detailmutasiproduksi;
use App\Models\Detailpermintaanproduksi;
use App\Models\Oman;
use App\Models\Permintaanproduksi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PermintaanproduksiController extends Controller
{
    public function index(Request $request)
    {
        $start_year = config('global.start_year');
        $start_date = config('global.start_date');
        $end_date = config('global.end_date');

        $query = Permintaanproduksi::query();
        if (!empty($request->tahun_search)) {
            $query->where('tahun', $request->tahun_search);
        } else {
            $query->where('tahun', date('Y'));
        }
        $query->whereBetween('tanggal', [$start_date, $end_date]);
        $query->join('marketing_oman', 'produksi_permintaan.kode_oman', '=', 'marketing_oman.kode_oman');
        $pp = $query->get();

        return view('produksi.permintaanproduksi.index', compact('pp', 'start_year'));
    }

    public function create()
    {
        $oman = Oman::where('status_oman', 0)->get();
        return view('produksi.permintaanproduksi.create', compact('oman'));
    }

    public function store(Request $request)
    {
        $kode_oman = Crypt::decrypt($request->kode_oman);
        $oman = Oman::where('kode_oman', $kode_oman)->first();
        $bulan = $oman->bulan;
        $bln = $bulan < 10 ? "0" . $bulan : $bulan;
        $tahun = $oman->tahun;
        $tanggal = $tahun . "-" . $bln . "-01";
        $kode_produk = $request->kode_produk;
        $oman_marketing = $request->oman_marketing;
        $stok_gudang = $request->stok_gudang;
        $buffer_stok = $request->buffer_stok;

        $request->validate([
            'kode_oman' => 'required'
        ]);

        $cektutuplaporan = cektutupLaporan($tanggal, "produksi");
        if ($cektutuplaporan > 0) {
            return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
        }

        DB::beginTransaction();
        try {
            $no_permintaan = "PP" . $bln . substr($tahun, 2, 2);

            for ($i = 0; $i < count($kode_produk); $i++) {
                $detail[] = [
                    'no_permintaan' => $no_permintaan,
                    'kode_produk' => $kode_produk[$i],
                    'oman_marketing' => $oman_marketing[$i],
                    'stok_gudang' => $stok_gudang[$i],
                    'buffer_stok' => toNumber($buffer_stok[$i] != NULL ? $buffer_stok[$i] : 0),
                ];
                $timestamp = Carbon::now();
                foreach ($detail as &$record) {
                    $record['created_at'] = $timestamp;
                    $record['updated_at'] = $timestamp;
                }
            }

            Permintaanproduksi::create([
                'no_permintaan' => $no_permintaan,
                'tanggal_permintaan' => $tanggal,
                'status' => 0,
                'kode_oman' => $kode_oman
            ]);

            $chunks_buffer = array_chunk($detail, 5);
            foreach ($chunks_buffer as $chunk_buffer) {
                Detailpermintaanproduksi::insert($chunk_buffer);
            }

            Oman::where('kode_oman', $kode_oman)->update([
                'status_oman' => 1
            ]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function show($no_permintaan)
    {
        $no_permintaan = Crypt::decrypt($no_permintaan);
        $pp = Permintaanproduksi::join('marketing_oman', 'produksi_permintaan.kode_oman', '=', 'marketing_oman.kode_oman')
            ->where('no_permintaan', $no_permintaan)->first();
        $detail = Detailpermintaanproduksi::join('produk', 'produksi_permintaan_detail.kode_produk', '=', 'produk.kode_produk')
            ->where('no_permintaan', $no_permintaan)->get();
        return view('produksi.permintaanproduksi.show', compact('pp', 'detail'));
    }


    public function destroy($no_permintaan)
    {
        $no_permintaan = Crypt::decrypt($no_permintaan);
        $pp = Permintaanproduksi::where('no_permintaan', $no_permintaan)->first();
        try {
            $cektutuplaporan = cektutupLaporan($pp->tanggal_permintaan, "produksi");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            Oman::where('kode_oman', $pp->kode_oman)->update([
                'status_oman' => 0
            ]);
            Permintaanproduksi::where('no_permintaan', $no_permintaan)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    //AJAX REQUEST

    public function getrealisasi(Request $request)
    {
        $pp = Permintaanproduksi::join('marketing_oman', 'produksi_permintaan.kode_oman', '=', 'marketing_oman.kode_oman')
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->where('status', 1)
            ->first();

        if (empty($pp)) {
            return '<div class="alert alert-warning d-flex align-items-center" role="alert">
                <span class="alert-icon text-warning me-2">
                  <i class="ti ti-info-circle ti-xs"></i>
                </span>
                Data Belum Tersedia !
              </div>';
        } else {
            $dari = $pp->tahun . "-" . $pp->bulan . "-01";
            $sampai = date('Y-m-t', strtotime($dari));
            $detail = Detailpermintaanproduksi::select('produksi_permintaan_detail.*', 'jml_realisasi')
                ->leftJoin(
                    DB::raw("(
                    SELECT kode_produk, SUM(jumlah) as jml_realisasi FROM produksi_mutasi_detail
                    INNER JOIN produksi_mutasi  ON produksi_mutasi_detail.no_mutasi = produksi_mutasi.no_mutasi
                    WHERE jenis_mutasi = 'BPBJ'
                    AND tanggal_mutasi BETWEEN '$dari' AND '$sampai'
                    GROUP BY kode_produk
                ) mutasiproduksi"),
                    function ($join) {
                        $join->on('produksi_permintaan_detail.kode_produk', '=', 'mutasiproduksi.kode_produk');
                    }
                )
                ->where('no_permintaan', $pp->no_permintaan)->get();
            return view('produksi.permintaanproduksi.getrealisasi', compact('pp', 'detail'));
        }
    }
}
