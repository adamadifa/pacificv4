<?php

namespace App\Http\Controllers;

use App\Models\Barangproduksi;
use App\Models\Detailsaldoawalbarangproduksi;
use App\Models\Saldoawalbarangproduksi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class SaldoawalbarangproduksiController extends Controller
{
    public function index(Request $request)
    {

        $list_bulan = config('global.list_bulan');
        $nama_bulan = config('global.nama_bulan');
        $start_year = config('global.start_year');
        $query = Saldoawalbarangproduksi::query();
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
        return view('produksi.saldoawalbarangproduksi.index', compact('list_bulan', 'start_year', 'saldo_awal', 'nama_bulan'));
    }

    public function create()
    {
        $list_bulan = config('global.list_bulan');
        $start_year = config('global.start_year');
        return view('produksi.saldoawalbarangproduksi.create', compact('list_bulan', 'start_year'));
    }

    public function show($kode_saldo_awal)
    {
        $kode_saldo_awal = Crypt::decrypt($kode_saldo_awal);
        $saldo_awal = Saldoawalbarangproduksi::where('kode_saldo_awal', $kode_saldo_awal)->first();
        $detail = Detailsaldoawalbarangproduksi::where('kode_saldo_awal', $kode_saldo_awal)
            ->join('produksi_barang', 'produksi_barang_saldoawal_detail.kode_barang_produksi', '=', 'produksi_barang.kode_barang_produksi')
            ->get();
        $nama_bulan = config('global.nama_bulan');
        return view('produksi.saldoawalbarangproduksi.show', compact('saldo_awal', 'nama_bulan', 'detail'));
    }

    public function destroy($kode_saldo_awal)
    {
        $kode_saldo_awal = Crypt::decrypt($kode_saldo_awal);
        $saldo_awal = Saldoawalbarangproduksi::where('kode_saldo_awal', $kode_saldo_awal)->first();
        try {
            $cektutuplaporan = cektutupLaporan($saldo_awal->tanggal, "produksi");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            Saldoawalbarangproduksi::where('kode_saldo_awal', $kode_saldo_awal)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function store(Request $request)
    {
        $bulan = $request->bulan;
        $bln = $bulan < 10 ? "0" . $bulan : $bulan;
        $tahun = $request->tahun;
        $tanggal = $tahun . "-" . $bln . "-01";
        $kode_barang_produksi = $request->kode_barang_produksi;
        $jumlah = $request->jumlah;
        //SAMP = Saldo Awal Mutasi Produksi
        $kode_saldo_awal = "SABP" . $bln . substr($tahun, 2, 2);


        $bulanberikutnya = getbulandantahunberikutnya($bulan, $tahun, "bulan");
        $tahunberikutnya = getbulandantahunberikutnya($bulan, $tahun, "tahun");

        $cektutuplaporan = cektutupLaporan($tanggal, "produksi");
        if ($cektutuplaporan > 0) {
            return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
        } else if (empty($kode_barang_produksi)) {
            return Redirect::back()->with(messageError('Silahkan Get Saldo Terlebih Dahulu !'));
        }
        DB::beginTransaction();
        try {
            // Cek Saldo Bulan Berikutnya
            $ceksaldobulanberikutnya = Saldoawalbarangproduksi::where('bulan', $bulanberikutnya)->where('tahun', $tahunberikutnya)->count();

            //Cek Saldo Bulan Ini
            $ceksaldobulanini = Saldoawalbarangproduksi::where('bulan', $bulan)->where('tahun', $tahun)->count();

            for ($i = 0; $i < count($kode_barang_produksi); $i++) {
                $detail_saldo[] = [
                    'kode_saldo_awal' => $kode_saldo_awal,
                    'kode_barang_produksi' => $kode_barang_produksi[$i],
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
                Saldoawalbarangproduksi::where('kode_saldo_awal', $kode_saldo_awal)->delete();
            }
            if (!empty($detail_saldo)) {

                Saldoawalbarangproduksi::create([
                    'kode_saldo_awal' => $kode_saldo_awal,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'tanggal'  => $tahun . "-" . $bulan . "-01"
                ]);

                $chunks_buffer = array_chunk($detail_saldo, 5);
                foreach ($chunks_buffer as $chunk_buffer) {
                    Detailsaldoawalbarangproduksi::insert($chunk_buffer);
                }
            } else {
                DB::rollBack();
                return Redirect::back()->with(messageError('Detail Saldo Kosong'));
            }

            DB::commit();
            return redirect(route('sabarangproduksi.index'))->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect(route('sabarangproduksi.index'))->with(messageError($e->getMessage()));
        }
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
        $ceksaldo = Saldoawalbarangproduksi::count();
        // Cek Saldo Bulan Lalu
        $ceksaldobulanlalu = Saldoawalbarangproduksi::where('bulan', $bulanlalu)->where('tahun', $tahunlalu)->count();

        //Cek Saldo Bulan Ini
        $ceksaldobulanini = Saldoawalbarangproduksi::where('bulan', $bulan)->where('tahun', $tahun)->count();
        //Get Produk

        //Jika Saldo BUlan Lalu Kosong dan Saldo Bulan Ini Ada Maka Di Ambil Saldo BUlan Ini
        if (empty($ceksaldobulanlalu) && !empty($ceksaldobulanini)) {
            // echo 1;
            // die;
            $barangproduksi = Barangproduksi::selectRaw(
                'produksi_barang.kode_barang_produksi,
                nama_barang,
                saldo_awal as saldo_akhir'
            )
                ->where('status_aktif_barang', 1)
                ->leftJoin(
                    DB::raw("(
                    SELECT
                        kode_barang_produksi,
                        jumlah as saldo_awal
                    FROM
                        produksi_barang_saldoawal_detail
                    INNER JOIN produksi_barang_saldoawal ON produksi_barang_saldoawal_detail.kode_saldo_awal = produksi_barang_saldoawal.kode_saldo_awal
                    WHERE bulan = '$bulan' AND tahun='$tahun'
                ) saldo_awal"),
                    function ($join) {
                        $join->on('produksi_barang.kode_barang_produksi', '=', 'saldo_awal.kode_barang_produksi');
                    }
                )
                ->orderBy('kode_produk')->get();
        } else {
            // echo 2;
            // die;
            //Jika Saldo Bulan Lalu Ada Maka Hitung Saldo Awal Bulan Lalu - Mutasi Bulan Lalu
            $barangproduksi = Barangproduksi::selectRaw("
            produksi_barang.kode_barang_produksi,
            nama_barang,
            jml_saldoawal,
            jml_pemasukan,
            jml_pengeluaran,
            IFNULL(jml_saldoawal,0) + IFNULL(jml_pemasukan,0) - IFNULL(jml_pengeluaran,0) as saldo_akhir
            ")
                ->leftJoin(
                    DB::raw("(
                    SELECT kode_barang_produksi,SUM( jumlah ) AS jml_saldoawal
                    FROM produksi_barang_saldoawal_detail
                    INNER JOIN produksi_barang_saldoawal ON produksi_barang_saldoawal_detail.kode_saldo_awal=produksi_barang_saldoawal.kode_saldo_awal
                    WHERE bulan = '$bulanlalu' AND tahun = '$tahunlalu'
                    GROUP BY kode_barang_produksi
                ) saldo_awal"),
                    function ($join) {
                        $join->on('produksi_barang.kode_barang_produksi', '=', 'saldo_awal.kode_barang_produksi');
                    }
                )
                ->leftJoin(
                    DB::raw("(
                        SELECT kode_barang_produksi,
                        SUM( jumlah ) AS jml_pemasukan
                        FROM produksi_barang_masuk_detail
                        INNER JOIN produksi_barang_masuk ON produksi_barang_masuk_detail.no_bukti = produksi_barang_masuk.no_bukti
                        WHERE MONTH(tanggal) = '$bulanlalu' AND YEAR(tanggal) = '$tahunlalu'
                        GROUP BY produksi_barang_masuk_detail.kode_barang_produksi
                    ) pemasukan"),
                    function ($join) {
                        $join->on('produksi_barang.kode_barang_produksi', '=', 'pemasukan.kode_barang_produksi');
                    }
                )

                ->leftJoin(
                    DB::raw("(
                        SELECT kode_barang_produksi,
                        SUM( jumlah ) AS jml_pengeluaran FROM produksi_barang_keluar_detail
                        INNER JOIN produksi_barang_keluar ON produksi_barang_keluar_detail.no_bukti = produksi_barang_keluar.no_bukti
                        WHERE MONTH(tanggal) = '$bulanlalu' AND YEAR(tanggal) = '$tahunlalu'
                        GROUP BY produksi_barang_keluar_detail.kode_barang_produksi
                    ) pengeluaran"),
                    function ($join) {
                        $join->on('produksi_barang.kode_barang_produksi', '=', 'pengeluaran.kode_barang_produksi');
                    }
                )

                ->where('produksi_barang.status_aktif_barang', '1')
                ->get();
        }



        $data = ['barangproduksi', 'readonly'];

        if (empty($ceksaldo)) {
            $readonly = false;
            return view('produksi.saldoawalbarangproduksi.getdetailsaldo', compact($data));
        } else {
            if (empty($ceksaldobulanlalu) && empty($ceksaldobulanini)) {
                return 1;
            } else {
                $readonly = true;
                return view('produksi.saldoawalbarangproduksi.getdetailsaldo', compact($data));
            }
        }
    }
}
