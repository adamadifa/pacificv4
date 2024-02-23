<?php

namespace App\Http\Controllers;

use App\Models\Bufferstok;
use App\Models\Cabang;
use App\Models\Detailbufferstok;
use App\Models\Detailmaxstok;
use App\Models\Maxstok;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class BufferstokController extends Controller
{
    public function index(Request $request)
    {
        $cabang = Cabang::orderBy('kode_cabang')->get();
        return view('datamaster.bufferstok.index', compact('cabang'));
    }


    public function getbufferstok($kode_cabang)
    {

        if (auth()->user()->kode_cabang != 'PST') {
            $kode_cabang = auth()->user()->kode_cabang;
        }
        $query = Produk::query();
        $query->orderBy('produk.kode_produk');
        $query->select('produk.kode_produk', 'nama_produk', 'jumlah_buffer', 'jumlah_max');
        $query->leftJoin(
            DB::raw("(
            SELECT
                kode_produk,
                jumlah as jumlah_buffer
            FROM
                buffer_stok_detail
            INNER JOIN buffer_stok ON buffer_stok_detail.kode_buffer_stok = buffer_stok.kode_buffer_stok
            WHERE kode_cabang = '$kode_cabang'
        ) bufferstok"),
            function ($join) {
                $join->on('produk.kode_produk', '=', 'bufferstok.kode_produk');
            }
        );

        $query->leftJoin(
            DB::raw("(
            SELECT
                kode_produk,
                jumlah as jumlah_max
            FROM
                max_stok_detail
            INNER JOIN max_stok ON max_stok_detail.kode_max_stok = max_stok.kode_max_stok
            WHERE kode_cabang = '$kode_cabang'
        ) maxstok"),
            function ($join) {
                $join->on('produk.kode_produk', '=', 'maxstok.kode_produk');
            }
        );
        $query->where('status_aktif_produk', 1);
        $detailbufferstok = $query->get();
        return view('datamaster.bufferstok.getbufferstok', compact('detailbufferstok'));
    }


    public function update(Request $request)
    {

        if (auth()->user()->kode_cabang == 'PST') {
            $kode_cabang = $request->kode_cabang;
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
        }


        $kode_produk = $request->kode_produk;
        $buffer_stok = $request->jumlah_buffer;
        $max_stok = $request->jumlah_max;
        $kode_buffer_stok = "BF" . $kode_cabang;
        $kode_max_stok = "MX" . $kode_cabang;
        $detail_buffer = [];
        $detail_max = [];
        for ($i = 0; $i < count($kode_produk); $i++) {
            $jumlah_buffer = !empty($buffer_stok[$i]) ? $buffer_stok[$i] : 0;
            $jumlah_max = !empty($max_stok[$i]) ? $max_stok[$i] : 0;

            //echo $bufferstok . "<br>";
            if (!empty($jumlah_buffer)) {
                $detail_buffer[]   = [
                    'kode_buffer_stok' => $kode_buffer_stok,
                    'kode_produk' => $kode_produk[$i],
                    'jumlah' => $jumlah_buffer
                ];
            }

            if (!empty($jumlah_max)) {
                $detail_max[]   = [
                    'kode_max_stok' => $kode_max_stok,
                    'kode_produk' => $kode_produk[$i],
                    'jumlah' => $jumlah_max
                ];
            }
        }


        DB::beginTransaction();
        try {

            if (!empty($detail_buffer)) {
                Bufferstok::where('kode_buffer_stok', $kode_buffer_stok)->delete();
                Bufferstok::create([
                    'kode_buffer_stok' => $kode_buffer_stok,
                    'kode_cabang' => $kode_cabang,
                ]);

                $chunks_buffer = array_chunk($detail_buffer, 5);
                foreach ($chunks_buffer as $chunk_buffer) {
                    Detailbufferstok::insert($chunk_buffer);
                }
            }

            if (!empty($detail_max)) {
                Maxstok::where('kode_max_stok', $kode_max_stok)->delete();
                Maxstok::create([
                    'kode_max_stok' => $kode_max_stok,
                    'kode_cabang' => $kode_cabang,
                ]);

                $chunks_max = array_chunk($detail_max, 5);
                foreach ($chunks_max as $chunks_max) {
                    Detailmaxstok::insert($chunks_max);
                }
            }


            DB::commit();

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
