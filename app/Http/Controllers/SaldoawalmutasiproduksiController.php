<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailsaldoawalmutasiproduksi;
use App\Models\Produk;
use App\Models\Saldoawalmutasiproduksi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class SaldoawalmutasiproduksiController extends Controller
{
    public function index()
    {

        $nama_bulan = config('global.nama_bulan');
        $start_year = config('global.start_year');
        return view('produksi.saldoawalmutasiproduksi.index', compact('nama_bulan', 'start_year'));
    }

    public function create()
    {
        $nama_bulan = config('global.nama_bulan');
        $start_year = config('global.start_year');
        return view('produksi.saldoawalmutasiproduksi.create', compact('nama_bulan', 'start_year'));
    }



    public function getdetailsaldo(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $bulanlalu = getbulandantahunlalu($bulan, $tahun, "bulan");
        $tahunlalu = getbulandantahunlalu($bulan, $tahun, "tahun");

        //Cek Apakah Sudah Ada Saldo Atau Belum
        $ceksaldo = Saldoawalmutasiproduksi::count();
        // Cek Saldo Bulan Lalu
        $ceksaldobulanlalu = Saldoawalmutasiproduksi::where('bulan', $bulanlalu)->where('tahun', $tahunlalu)->count();

        //Cek Saldo Bulan Ini
        $ceksaldobulanini = Saldoawalmutasiproduksi::where('bulan', $bulan)->where('tahun', $tahun)->count();
        //Get Produk
        $produk = Produk::select('produk.kode_produk', 'nama_produk', 'jumlah')
            ->where('status_aktif_produk', 1)
            ->leftJoin(
                DB::raw("(
                SELECT
                    kode_produk,
                    jumlah
                FROM
                    produksi_mutasi_saldoawal_detail
                INNER JOIN produksi_mutasi_saldoawal ON produksi_mutasi_saldoawal_detail.kode_saldo_awal = produksi_mutasi_saldoawal.kode_saldo_awal
                WHERE bulan = '$bulan' AND tahun='$tahun'
            ) saldo_awal"),
                function ($join) {
                    $join->on('produk.kode_produk', '=', 'saldo_awal.kode_produk');
                }
            )
            ->orderBy('kode_produk')->get();

        $data = ['produk', 'readonly'];

        if (empty($ceksaldo)) {
            $readonly = false;
            return view('produksi.saldoawalmutasiproduksi.getdetailsaldo', compact($data));
        } else {
            if (empty($ceksaldobulanlalu) && empty($ceksaldobulanini)) {
                return 1;
            } else {
                $readonly = true;
                return view('produksi.saldoawalmutasiproduksi.getdetailsaldo', compact($data));
            }
        }
    }


    public function store(Request $request)
    {
        $bulan = $request->bulan;


        $bln = $bulan < 10 ? "0" . $bulan : $bulan;
        $tahun = $request->tahun;
        $kode_produk = $request->kode_produk;
        $jumlah = $request->jumlah;
        $kode_saldo_awal = "SAMP" . $bln . substr($tahun, 2, 2);


        $bulanberikutnya = getbulandantahunberikutnya($bulan, $tahun, "bulan");
        $tahunberikutnya = getbulandantahunberikutnya($bulan, $tahun, "tahun");

        DB::beginTransaction();
        try {
            // Cek Saldo Bulan Berikutnya
            $ceksaldobulanberikutnya = Saldoawalmutasiproduksi::where('bulan', $bulanberikutnya)->where('tahun', $tahunberikutnya)->count();

            //Cek Saldo Bulan Ini
            $ceksaldobulanini = Saldoawalmutasiproduksi::where('bulan', $bulan)->where('tahun', $tahun)->count();

            for ($i = 1; $i < count($kode_produk); $i++) {
                $detail_saldo[] = [
                    'kode_saldo_awal' => $kode_saldo_awal,
                    'kode_produk' => $kode_produk[$i],
                    'jumlah' => toNumber($jumlah[$i])
                ];
            }

            $timestamp = Carbon::now();

            foreach ($detail_saldo as &$record) {
                $record['created_at'] = $timestamp;
                $record['updated_at'] = $timestamp;
            }



            if (!empty($ceksaldobulanberikutnya)) {
                return Redirect::back()->with(messageError('Tidak Bisa Update Saldo, Dikarenakan Saldo Berikutnya sudah di Set'));
            } elseif (empty($ceksaldobulanberikutnya) && !empty($ceksaldobulanini)) {
                Saldoawalmutasiproduksi::where('kode_saldo_awal', $kode_saldo_awal)->delete();
            }
            if (!empty($detail_saldo)) {

                Saldoawalmutasiproduksi::create([
                    'kode_saldo_awal' => $kode_saldo_awal,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'tanggal'  => $tahun . "-" . $bulan . "-01"
                ]);

                $chunks_buffer = array_chunk($detail_saldo, 5);
                foreach ($chunks_buffer as $chunk_buffer) {
                    Detailsaldoawalmutasiproduksi::insert($chunk_buffer);
                }
            }

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
