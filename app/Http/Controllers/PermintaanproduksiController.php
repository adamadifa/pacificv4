<?php

namespace App\Http\Controllers;

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

        $query = Permintaanproduksi::query();
        if (!empty($request->tahun_search)) {
            $query->where('tahun', $request->tahun_search);
        } else {
            $query->where('tahun', date('Y'));
        }
        $query->join('marketing_oman', 'produksi_permintaan.kode_oman', '=', 'marketing_oman.kode_oman');
        $pp = $query->get();

        return view('produksi.permintaanproduksi.index', compact('pp'));
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
}
