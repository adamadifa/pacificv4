<?php

namespace App\Http\Controllers;

use App\Models\Detailsaldoawalgudangjadi;
use App\Models\Produk;
use App\Models\Saldoawalgudangjadi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class SaldoawalgudangjadiController extends Controller
{
    public function index(Request $request)
    {

        $list_bulan = config('global.list_bulan');
        $nama_bulan = config('global.nama_bulan');
        $start_year = config('global.start_year');
        $query = Saldoawalgudangjadi::query();
        if (!empty($request->bulan)) {
            $query->where('bulan', $request->bulan);
        }
        if (!empty($request->tahun)) {
            $query->where('tahun', $request->tahun);
        } else {
            $query->where('tahun', date('Y'));
        }
        $query->orderBy('tahun', 'desc');
        $query->orderBy('bulan');
        $saldo_awal = $query->get();
        return view('gudangjadi.saldoawal.index', compact('list_bulan', 'start_year', 'saldo_awal', 'nama_bulan'));
    }

    public function create()
    {
        $list_bulan = config('global.list_bulan');
        $start_year = config('global.start_year');
        return view('gudangjadi.saldoawal.create', compact('list_bulan', 'start_year'));
    }

    public function store(Request $request)
    {
        $bulan = $request->bulan;
        $bln = $bulan < 10 ? "0" . $bulan : $bulan;
        $tahun = $request->tahun;
        $tanggal = $tahun . "-" . $bln . "-01";
        $kode_produk = $request->kode_produk;
        $jumlah = $request->jumlah;
        //SAMP = Saldo Awal Mutasi Produksi
        $kode_saldo_awal = "SAGJ" . $bln . substr($tahun, 2, 2);


        $bulanberikutnya = getbulandantahunberikutnya($bulan, $tahun, "bulan");
        $tahunberikutnya = getbulandantahunberikutnya($bulan, $tahun, "tahun");

        $cektutuplaporan = cektutupLaporan($tanggal, "gudangjadi");
        if ($cektutuplaporan > 0) {
            return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
        } else if (empty($kode_produk)) {
            return Redirect::back()->with(messageError('Silahkan Get Saldo Terlebih Dahulu !'));
        }
        DB::beginTransaction();
        try {
            // Cek Saldo Bulan Berikutnya
            $ceksaldobulanberikutnya = Saldoawalgudangjadi::where('bulan', $bulanberikutnya)->where('tahun', $tahunberikutnya)->count();

            //Cek Saldo Bulan Ini
            $ceksaldobulanini = Saldoawalgudangjadi::where('bulan', $bulan)->where('tahun', $tahun)->count();

            for ($i = 0; $i < count($kode_produk); $i++) {
                $detail_saldo[] = [
                    'kode_saldo_awal' => $kode_saldo_awal,
                    'kode_produk' => $kode_produk[$i],
                    'jumlah' => !empty($jumlah[$i]) ? toNumber($jumlah[$i]) : 0
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
                Saldoawalgudangjadi::where('kode_saldo_awal', $kode_saldo_awal)->delete();
            }
            if (!empty($detail_saldo)) {

                Saldoawalgudangjadi::create([
                    'kode_saldo_awal' => $kode_saldo_awal,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'tanggal'  => $tahun . "-" . $bulan . "-01"
                ]);

                $chunks_buffer = array_chunk($detail_saldo, 5);
                foreach ($chunks_buffer as $chunk_buffer) {
                    Detailsaldoawalgudangjadi::insert($chunk_buffer);
                }
            } else {
                DB::rollBack();
                return Redirect::back()->with(messageError('Detail Saldo Kosong'));
            }


            DB::commit();
            return redirect(route('sagudangjadi.index'))->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect(route('sagudangjadi.index'))->with(messageError($e->getMessage()));
        }
    }

    public function show($kode_saldo_awal)
    {
        $kode_saldo_awal = Crypt::decrypt($kode_saldo_awal);
        $saldo_awal = Saldoawalgudangjadi::where('kode_saldo_awal', $kode_saldo_awal)->first();
        $detail = Detailsaldoawalgudangjadi::where('kode_saldo_awal', $kode_saldo_awal)
            ->join('produk', 'gudang_jadi_saldoawal_detail.kode_produk', '=', 'produk.kode_produk')
            ->get();
        $nama_bulan = config('global.nama_bulan');
        return view('gudangjadi.saldoawal.show', compact('saldo_awal', 'nama_bulan', 'detail'));
    }

    //AJAX REQUEST
    public function getdetailsaldo(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $bulanlalu = getbulandantahunlalu($bulan, $tahun, "bulan");
        $tahunlalu = getbulandantahunlalu($bulan, $tahun, "tahun");

        $tgl_dari_bulanlalu = $tahunlalu . "-" . $bulanlalu . "-01";
        $tgl_sampai_bulanlalu = date('Y-m-t', strtotime($tgl_dari_bulanlalu));

        //Cek Apakah Sudah Ada Saldo Atau Belum
        $ceksaldo = Saldoawalgudangjadi::count();
        // Cek Saldo Bulan Lalu
        $ceksaldobulanlalu = Saldoawalgudangjadi::where('bulan', $bulanlalu)->where('tahun', $tahunlalu)->count();

        //Cek Saldo Bulan Ini
        $ceksaldobulanini = Saldoawalgudangjadi::where('bulan', $bulan)->where('tahun', $tahun)->count();
        //Get Produk

        //Jika Saldo BUlan Lalu Kosong dan Saldo Bulan Ini Ada Maka Di Ambil Saldo BUlan Ini
        if (empty($ceksaldobulanlalu) && !empty($ceksaldobulanini)) {
            $produk = Produk::selectRaw(
                'produk.kode_produk,
                nama_produk,
                saldo_awal as saldo_akhir'
            )
                ->where('status_aktif_produk', 1)
                ->leftJoin(
                    DB::raw("(
                    SELECT
                        kode_produk,
                        jumlah as saldo_awal
                    FROM
                        gudang_jadi_saldoawal_detail
                    INNER JOIN gudang_jadi_saldoawal ON gudang_jadi_saldoawal_detail.kode_saldo_awal = gudang_jadi_saldoawal.kode_saldo_awal
                    WHERE bulan = '$bulan' AND tahun='$tahun'
                ) saldo_awal"),
                    function ($join) {
                        $join->on('produk.kode_produk', '=', 'saldo_awal.kode_produk');
                    }
                )
                ->orderBy('kode_produk')->get();
        } else {

            //Jika Saldo Bulan Lalu Ada Maka Hitung Saldo Awal Bulan Lalu - Mutasi Bulan Lalu
            $produk = Produk::selectRaw(
                'produk.kode_produk,
                nama_produk,
                IFNULL(saldo_awal,0) + IFNULL(sisamutasi,0) as saldo_akhir'
            )
                ->where('status_aktif_produk', 1)
                ->leftJoin(
                    DB::raw("(
                    SELECT
                        kode_produk,
                        jumlah as saldo_awal
                    FROM
                        gudang_jadi_saldoawal_detail
                    INNER JOIN gudang_jadi_saldoawal ON gudang_jadi_saldoawal_detail.kode_saldo_awal = gudang_jadi_saldoawal.kode_saldo_awal
                    WHERE bulan = '$bulanlalu' AND tahun='$tahunlalu'
                ) saldo_awal"),
                    function ($join) {
                        $join->on('produk.kode_produk', '=', 'saldo_awal.kode_produk');
                    }
                )

                ->leftJoin(
                    DB::raw("(
                        SELECT kode_produk,
                        SUM(IF( in_out = 'I', jumlah, 0)) - SUM(IF( in_out = 'O', jumlah, 0)) as sisamutasi
                        FROM gudang_jadi_mutasi_detail
                        INNER JOIN gudang_jadi_mutasi
                        ON gudang_jadi_mutasi_detail.no_mutasi = gudang_jadi_mutasi.no_mutasi
                        WHERE tanggal BETWEEN '$tgl_dari_bulanlalu' AND '$tgl_sampai_bulanlalu'  GROUP BY kode_produk
                    ) mutasi"),
                    function ($join) {
                        $join->on('produk.kode_produk', '=', 'mutasi.kode_produk');
                    }
                )
                ->orderBy('kode_produk')->get();
        }



        $data = ['produk', 'readonly'];

        if (empty($ceksaldo)) {
            $readonly = false;
            return view('gudangjadi.saldoawal.getdetailsaldo', compact($data));
        } else {
            if (empty($ceksaldobulanlalu) && empty($ceksaldobulanini)) {
                return 1;
            } else {
                $readonly = true;
                return view('gudangjadi.saldoawal.getdetailsaldo', compact($data));
            }
        }
    }

    public function destroy($kode_saldo_awal)
    {
        $kode_saldo_awal = Crypt::decrypt($kode_saldo_awal);
        $saldo_awal = Saldoawalgudangjadi::where('kode_saldo_awal', $kode_saldo_awal)->first();
        try {
            $cektutuplaporan = cektutupLaporan($saldo_awal->tanggal, "gudangjadi");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            Saldoawalgudangjadi::where('kode_saldo_awal', $kode_saldo_awal)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
