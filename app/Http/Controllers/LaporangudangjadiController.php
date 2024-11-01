<?php

namespace App\Http\Controllers;

use App\Models\Angkutan;
use App\Models\Cabang;
use App\Models\Detailmutasigudangjadi;
use App\Models\Detailomancabang;
use App\Models\Detailpermintaankiriman;
use App\Models\Detailsaldoawalgudangjadi;
use App\Models\Produk;
use App\Models\Saldoawalgudangjadi;
use App\Models\Suratjalanangkutan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class LaporangudangjadiController extends Controller
{
    public function index()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $data['produk'] = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        $data['angkutan'] = Angkutan::orderBy('kode_angkutan')->get();
        return view('gudangjadi.laporan.index', $data);
    }

    public function cetakpersediaan(Request $request)
    {
        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }


        $saldo_awal = Detailsaldoawalgudangjadi::select('kode_produk', 'tanggal', 'jumlah')
            ->join('gudang_jadi_saldoawal', 'gudang_jadi_saldoawal_detail.kode_saldo_awal', '=', 'gudang_jadi_saldoawal.kode_saldo_awal')
            ->where('tanggal', '<=', $request->dari)
            ->where('kode_produk', $request->kode_produk)
            ->orderBy('tanggal', 'desc')
            ->first();

        if ($saldo_awal != null) {
            $tanggal_saldoawal = $saldo_awal->tanggal;
            $mutasi_saldoawal = Detailmutasigudangjadi::select(DB::raw("SUM(IF( in_out = 'I', jumlah, 0))-SUM(IF( in_out = 'O', jumlah, 0)) as jml_mutasi_saldoawal"))
                ->join('gudang_jadi_mutasi', 'gudang_jadi_mutasi_detail.no_mutasi', '=', 'gudang_jadi_mutasi.no_mutasi')
                ->where('tanggal', '>=', $tanggal_saldoawal)
                ->where('tanggal', '<', $request->dari)
                ->where('kode_produk', $request->kode_produk)
                ->first();
            $saldoawal = $saldo_awal->jumlah + $mutasi_saldoawal->jml_mutasi_saldoawal;
        } else {
            $mutasi_saldoawal = Detailmutasigudangjadi::select(DB::raw("SUM(IF( in_out = 'I', jumlah, 0))-SUM(IF( in_out = 'O', jumlah, 0)) as jml_mutasi_saldoawal"))
                ->join('gudang_jadi_mutasi', 'gudang_jadi_mutasi_detail.no_mutasi', '=', 'gudang_jadi_mutasi.no_mutasi')
                ->where('tanggal', '<', $request->dari)
                ->where('kode_produk', $request->kode_produk)
                ->first();
            $saldoawal = $mutasi_saldoawal->jml_mutasi_saldoawal;
        }
        $data['saldoawal'] = $saldoawal;
        $data['mutasi'] = Detailmutasigudangjadi::select(
            'gudang_jadi_mutasi_detail.no_mutasi',
            'gudang_jadi_mutasi.tanggal',
            'jenis_mutasi',
            'gudang_jadi_mutasi.keterangan',
            'gudang_jadi_mutasi_detail.kode_produk',
            'jumlah',
            'nama_cabang',
            'in_out'
        )
            ->join('gudang_jadi_mutasi', 'gudang_jadi_mutasi_detail.no_mutasi', '=', 'gudang_jadi_mutasi.no_mutasi')
            ->join('produk', 'gudang_jadi_mutasi_detail.kode_produk', '=', 'produk.kode_produk')
            ->leftJoin('marketing_permintaan_kiriman', 'gudang_jadi_mutasi.no_permintaan', '=', 'marketing_permintaan_kiriman.no_permintaan')
            ->leftjoin('cabang', 'marketing_permintaan_kiriman.kode_cabang', '=', 'cabang.kode_cabang')
            ->whereBetween('gudang_jadi_mutasi.tanggal', [$request->dari, $request->sampai])
            ->where('gudang_jadi_mutasi_detail.kode_produk', $request->kode_produk)
            ->orderBy('gudang_jadi_mutasi.tanggal')
            ->orderBy('gudang_jadi_mutasi_detail.created_at')
            ->get();

        $data['produk'] = Produk::where('kode_produk', $request->kode_produk)->first();
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $time = date('H:i:s');
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Laporan Persediaan Gudang jadi $request->dari-$request->sampai - $time.xls");
        }
        return view('gudangjadi.laporan.persediaan_cetak', $data);
    }


    public function cetakrekappersediaan(Request $request)
    {
        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $saldo_awal = Saldoawalgudangjadi::select("kode_saldo_awal", "tanggal")
            ->where('tanggal', '<=', $request->dari)
            ->orderBy('tanggal', 'desc')
            ->first();
        if ($saldo_awal != null) {
            $data['rekap'] = Produk::select(
                'produk.kode_produk',
                'nama_produk',
                DB::raw('IFNULL(jml_saldoawal,0) + IFNULL(jml_mutasi_saldoawal,0) as jml_saldo_awal'),
                'jml_fsthp',
                'jml_repack',
                'jml_lainlain_in',
                'jml_surat_jalan',
                'jml_reject',
                'jml_lainlain_out',
                'jml_mutasi',
                DB::raw('IFNULL(jml_saldoawal,0) + IFNULL(jml_mutasi_saldoawal,0) + IFNULL(jml_mutasi,0) as jml_saldo_akhir')
            )
                ->leftJoin(
                    DB::raw("(
                            SELECT
                            kode_produk,jumlah as jml_saldoawal
                            FROM
                            gudang_jadi_saldoawal_detail
                            WHERE kode_saldo_awal = '$saldo_awal->kode_saldo_awal'
                        ) saldo_awal"),
                    function ($join) {
                        $join->on('produk.kode_produk', '=', 'saldo_awal.kode_produk');
                    }
                )
                ->leftJoin(
                    DB::raw("(
                            SELECT
                            kode_produk,
                            SUM(IF( in_out = 'I', jumlah, 0)) -SUM(IF( in_out = 'O', jumlah, 0)) as jml_mutasi_saldoawal
                            FROM
                            gudang_jadi_mutasi_detail
                            INNER JOIN gudang_jadi_mutasi ON gudang_jadi_mutasi_detail.no_mutasi = gudang_jadi_mutasi.no_mutasi
                            WHERE tanggal >= '$saldo_awal->tanggal' AND tanggal < '$request->dari'
                            GROUP BY kode_produk
                        ) mutasi_saldo_awal"),
                    function ($join) {
                        $join->on('produk.kode_produk', '=', 'mutasi_saldo_awal.kode_produk');
                    }
                )

                ->leftJoin(
                    DB::raw("(
                            SELECT
                            kode_produk,
                            SUM(IF(jenis_mutasi = 'FS' ,jumlah,0)) as jml_fsthp,
                            SUM(IF(jenis_mutasi = 'RP',jumlah,0)) as jml_repack,
                            SUM(IF(jenis_mutasi = 'RJ',jumlah,0)) as jml_reject,
                            SUM(IF(jenis_mutasi = 'LN' AND  in_out ='I',jumlah,0)) as jml_lainlain_in,
                            SUM(IF(jenis_mutasi = 'LN' AND  in_out ='O',jumlah,0)) as jml_lainlain_out,
                            SUM(IF(jenis_mutasi = 'SJ',jumlah,0)) as jml_surat_jalan,
                            SUM(IF( in_out = 'I', jumlah, 0)) -SUM(IF( in_out = 'O', jumlah, 0)) as jml_mutasi
                            FROM
                            gudang_jadi_mutasi_detail
                            INNER JOIN gudang_jadi_mutasi ON gudang_jadi_mutasi_detail.no_mutasi = gudang_jadi_mutasi.no_mutasi
                            WHERE tanggal BETWEEN '$request->dari' AND '$request->sampai'
                            GROUP BY kode_produk
                        ) mutasi_gudang_jadi"),
                    function ($join) {
                        $join->on('produk.kode_produk', '=', 'mutasi_gudang_jadi.kode_produk');
                    }
                )
                ->orderBy('produk.kode_produk')
                ->get();
        } else {
            $data['rekap'] = Produk::select(
                'produk.kode_produk',
                'nama_produk',
                'jml_saldo_awal',
                'jml_fsthp',
                'jml_repack',
                'jml_lainlain_in',
                'jml_surat_jalan',
                'jml_reject',
                'jml_lainlain_out',
                'jml_mutasi',
                DB::raw('IFNULL(jml_saldo_awal,0)  + IFNULL(jml_mutasi,0) as jml_saldo_akhir')

            )

                ->leftJoin(
                    DB::raw("(
                            SELECT
                            kode_produk,
                            SUM(IF( in_out = 'I', jumlah, 0)) -SUM(IF( in_out = 'O', jumlah, 0)) as jml_saldo_awal
                            FROM
                            gudang_jadi_mutasi_detail
                            INNER JOIN gudang_jadi_mutasi ON gudang_jadi_mutasi_detail.no_mutasi = gudang_jadi_mutasi.no_mutasi
                            WHERE tanggal  < '$request->dari'
                            GROUP BY kode_produk
                        ) mutasi_saldo_awal"),
                    function ($join) {
                        $join->on('produk.kode_produk', '=', 'mutasi_saldo_awal.kode_produk');
                    }
                )

                ->leftJoin(
                    DB::raw("(
                            SELECT
                            kode_produk,
                            SUM(IF(jenis_mutasi = 'FS' ,jumlah,0)) as jml_fsthp,
                            SUM(IF(jenis_mutasi = 'RP',jumlah,0)) as jml_repack,
                            SUM(IF(jenis_mutasi = 'RJ',jumlah,0)) as jml_reject,
                            SUM(IF(jenis_mutasi = 'LN' AND  in_out ='I',jumlah,0)) as jml_lainlain_in,
                            SUM(IF(jenis_mutasi = 'LN' AND  in_out ='O',jumlah,0)) as jml_lainlain_out,
                            SUM(IF(jenis_mutasi = 'SJ',jumlah,0)) as jml_surat_jalan,
                            SUM(IF( in_out = 'I', jumlah, 0)) -SUM(IF( in_out = 'O', jumlah, 0)) as jml_mutasi
                            FROM
                            gudang_jadi_mutasi_detail
                            INNER JOIN gudang_jadi_mutasi ON gudang_jadi_mutasi_detail.no_mutasi = gudang_jadi_mutasi.no_mutasi
                            WHERE tanggal BETWEEN '$request->dari' AND '$request->sampai'
                            GROUP BY kode_produk
                        ) mutasi_gudang_jadi"),
                    function ($join) {
                        $join->on('produk.kode_produk', '=', 'mutasi_gudang_jadi.kode_produk');
                    }
                )
                ->orderBy('produk.kode_produk')
                ->get();
        }

        $time = date('H:i:s');
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Rekap Persediaan Gudang jadi $request->dari-$request->sampai - $time.xls");
        }
        return view('gudangjadi.laporan.rekappersediaan_cetak', $data);
    }

    public function cetakrekaphasilproduksi(Request $request)
    {
        // $bulan = $request->bulan;
        // $tahun = $request->tahun;
        // $dari = $tahun . "-" . $bulan . "-01";
        // $sampai = date("Y-m-t", strtotime($dari));
        $dari = $request->dari;
        $sampai = $request->sampai;

        $tahun = date('Y', strtotime($dari));
        $bulan = date('m', strtotime($dari));
        if (lockreport($dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }
        $minggu1 = "'" . $tahun . "-" . $bulan . "-01'" . " AND '" . $tahun . "-" . $bulan . "-07'";
        $minggu2 = "'" . $tahun . "-" . $bulan . "-08'" . " AND '" . $tahun . "-" . $bulan . "-14'";
        $minggu3 = "'" . $tahun . "-" . $bulan . "-15'" . " AND '" . $tahun . "-" . $bulan . "-21'";
        $minggu4 = "'" . $tahun . "-" . $bulan . "-22'" . " AND '" . $sampai . "'";


        $data['rekap'] = Produk::select('produk.kode_produk', 'nama_produk', 'minggu_1', 'minggu_2', 'minggu_3', 'minggu_4', 'total_hasilproduksi')
            ->leftJoin(
                DB::raw("(
                SELECT
                kode_produk,
                IFNULL(SUM( IF (tanggal BETWEEN $minggu1, jumlah, 0 ) ),0) as minggu_1,
                IFNULL(SUM( IF (tanggal BETWEEN $minggu2, jumlah, 0 ) ),0) as minggu_2,
                IFNULL(SUM( IF (tanggal BETWEEN $minggu3, jumlah, 0 ) ),0) as minggu_3,
                IFNULL(SUM( IF (tanggal BETWEEN $minggu4, jumlah, 0 ) ),0) as minggu_4,
                IFNULL(SUM(jumlah),0) as total_hasilproduksi
                FROM gudang_jadi_mutasi_detail
                INNER JOIN gudang_jadi_mutasi ON gudang_jadi_mutasi_detail.no_mutasi = gudang_jadi_mutasi.no_mutasi
                WHERE tanggal BETWEEN '$dari' AND '$sampai' AND jenis_mutasi = 'FS'
                GROUP BY kode_produk
            ) hasilproduksi"),
                function ($join) {
                    $join->on('produk.kode_produk', '=', 'hasilproduksi.kode_produk');
                }
            )
            ->get();
        $data['dari'] = $dari;
        $data['sampai'] = $sampai;
        $time = date('H:i:s');
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Rekap Hasil Produksi $dari-$sampai-$time.xls");
        }
        return view('gudangjadi.laporan.rekaphasilproduksi_cetak', $data);
    }


    public function cetakrekappengeluaran(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $dari = $tahun . "-" . $bulan . "-01";
        $sampai = date("Y-m-t", strtotime($dari));
        if (lockreport($dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }
        $minggu1 = "'" . $tahun . "-" . $bulan . "-01'" . " AND '" . $tahun . "-" . $bulan . "-07'";
        $minggu2 = "'" . $tahun . "-" . $bulan . "-08'" . " AND '" . $tahun . "-" . $bulan . "-14'";
        $minggu3 = "'" . $tahun . "-" . $bulan . "-15'" . " AND '" . $tahun . "-" . $bulan . "-21'";
        $minggu4 = "'" . $tahun . "-" . $bulan . "-22'" . " AND '" . $sampai . "'";


        $data['rekap'] = Produk::select('produk.kode_produk', 'nama_produk', 'minggu_1', 'minggu_2', 'minggu_3', 'minggu_4', 'total_pengeluaran')
            ->leftJoin(
                DB::raw("(
                SELECT
                kode_produk,
                IFNULL(SUM( IF (tanggal BETWEEN $minggu1, jumlah, 0 ) ),0) as minggu_1,
                IFNULL(SUM( IF (tanggal BETWEEN $minggu2, jumlah, 0 ) ),0) as minggu_2,
                IFNULL(SUM( IF (tanggal BETWEEN $minggu3, jumlah, 0 ) ),0) as minggu_3,
                IFNULL(SUM( IF (tanggal BETWEEN $minggu4, jumlah, 0 ) ),0) as minggu_4,
                IFNULL(SUM(jumlah),0) as total_pengeluaran
                FROM gudang_jadi_mutasi_detail
                INNER JOIN gudang_jadi_mutasi ON gudang_jadi_mutasi_detail.no_mutasi = gudang_jadi_mutasi.no_mutasi
                WHERE tanggal BETWEEN '$dari' AND '$sampai' AND jenis_mutasi = 'SJ'
                GROUP BY kode_produk
            ) pengeluaran"),
                function ($join) {
                    $join->on('produk.kode_produk', '=', 'pengeluaran.kode_produk');
                }
            )
            ->get();
        $data['dari'] = $dari;
        $data['sampai'] = $sampai;
        $time = date('H:i:s');
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Rekap Hasil Produksi $dari-$sampai-$time.xls");
        }
        return view('gudangjadi.laporan.rekappengeluaran_cetak', $data);
    }

    public function cetakrealisasikiriman(Request $request)
    {
        // $bulan = $request->bulan;
        // $tahun = $request->tahun;
        // $dari = $tahun . "-" . $bulan . "-01";
        // $sampai = date("Y-m-t", strtotime($dari));

        $dari = $request->dari;
        $sampai = $request->sampai;
        if (lockreport($dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $produk = Detailpermintaankiriman::join('marketing_permintaan_kiriman', 'marketing_permintaan_kiriman_detail.no_permintaan', '=', 'marketing_permintaan_kiriman.no_permintaan')
            ->select('marketing_permintaan_kiriman_detail.kode_produk', 'nama_produk')
            ->join('produk', 'marketing_permintaan_kiriman_detail.kode_produk', '=', 'produk.kode_produk')
            ->whereBetween('tanggal', [$dari, $sampai])
            ->orderBy('kode_produk')
            ->groupBY('kode_produk')
            ->get();

        foreach ($produk as $d) {
            $field_produk_permintaan[] = "`permintaan_" . $d->kode_produk . "`";
            $select_produk_permintaan[] = "SUM(IF(kode_produk='$d->kode_produk',jumlah,0)) as `permintaan_" . $d->kode_produk . "`";

            $field_produk_realisasi[] = "`realisasi_" . $d->kode_produk . "`";
            $select_produk_realisasi[] = "SUM(IF(kode_produk='$d->kode_produk',jumlah,0)) as `realisasi_" . $d->kode_produk . "`";
        }

        $f_produk_permintaan = implode(",", $field_produk_permintaan);
        $s_produk_permintaan = implode(",", $select_produk_permintaan);

        $f_produk_realisasi = implode(",", $field_produk_realisasi);
        $s_produk_realisasi = implode(",", $select_produk_realisasi);

        $data['rekap'] = Cabang::select(
            'cabang.kode_cabang',
            'nama_cabang',
            DB::raw("$f_produk_permintaan"),
            DB::raw("$f_produk_realisasi")
        )
            ->leftJoin(
                DB::raw("(
                SELECT
                    kode_cabang,
                    $s_produk_permintaan
                FROM
                marketing_permintaan_kiriman_detail
                INNER JOIN marketing_permintaan_kiriman  ON marketing_permintaan_kiriman_detail.no_permintaan = marketing_permintaan_kiriman.no_permintaan
                WHERE tanggal BETWEEN '$dari' AND '$sampai'
                GROUP BY kode_cabang
            ) permintaan_kiriman"),
                function ($join) {
                    $join->on('cabang.kode_cabang', '=', 'permintaan_kiriman.kode_cabang');
                }
            )

            ->leftJoin(
                DB::raw("(
                SELECT
                    kode_cabang,
                    $s_produk_realisasi
                FROM
                    gudang_jadi_mutasi_detail
                INNER JOIN gudang_jadi_mutasi ON gudang_jadi_mutasi_detail.no_mutasi = gudang_jadi_mutasi.no_mutasi
                INNER JOIN marketing_permintaan_kiriman ON gudang_jadi_mutasi.no_permintaan = marketing_permintaan_kiriman.no_permintaan
                WHERE gudang_jadi_mutasi.tanggal BETWEEN '$dari' AND '$sampai' AND jenis_mutasi = 'SJ'
                GROUP BY kode_cabang
            ) realisasi_kiriman"),
                function ($join) {
                    $join->on('cabang.kode_cabang', '=', 'realisasi_kiriman.kode_cabang');
                }
            )
            ->get();
        $data['dari'] = $dari;
        $data['sampai'] = $sampai;
        $data['produk'] = $produk;
        $time = date('H:i:s');
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Realisasi Kiriman $dari-$sampai-$time.xls");
        }
        return view('gudangjadi.laporan.realisasikiriman_cetak', $data);
    }

    public function cetakrealisasioman(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $dari = $tahun . "-" . $bulan . "-01";
        $sampai = date("Y-m-t", strtotime($dari));
        if (lockreport($dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $produk = Detailomancabang::join('marketing_oman_cabang', 'marketing_oman_cabang_detail.kode_oman', '=', 'marketing_oman_cabang.kode_oman')
            ->select('marketing_oman_cabang_detail.kode_produk', 'nama_produk')
            ->join('produk', 'marketing_oman_cabang_detail.kode_produk', '=', 'produk.kode_produk')
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->orderBy('kode_produk')
            ->groupBY('kode_produk')
            ->get();


        foreach ($produk as $d) {
            $field_produk_oman[] = "`oman_" . $d->kode_produk . "`";
            $select_produk_oman[] = "SUM(IF(kode_produk='$d->kode_produk',jumlah,0)) as `oman_" . $d->kode_produk . "`";

            $field_produk_realisasi[] = "`realisasi_" . $d->kode_produk . "`";
            $select_produk_realisasi[] = "SUM(IF(kode_produk='$d->kode_produk',jumlah,0)) as `realisasi_" . $d->kode_produk . "`";
        }

        $f_produk_oman = implode(",", $field_produk_oman);
        $s_produk_oman = implode(",", $select_produk_oman);

        $f_produk_realisasi = implode(",", $field_produk_realisasi);
        $s_produk_realisasi = implode(",", $select_produk_realisasi);

        $data['rekap'] = Cabang::select(
            'cabang.kode_cabang',
            'nama_cabang',
            DB::raw("$f_produk_oman"),
            DB::raw("$f_produk_realisasi")
        )
            ->leftJoin(
                DB::raw("(
                SELECT
                    kode_cabang,
                    $s_produk_oman
                FROM
                marketing_oman_cabang_detail
                INNER JOIN marketing_oman_cabang  ON marketing_oman_cabang_detail.kode_oman = marketing_oman_cabang.kode_oman
                WHERE bulan = '$request->bulan' AND tahun = '$request->tahun'
                GROUP BY kode_cabang
            ) oman_cabang"),
                function ($join) {
                    $join->on('cabang.kode_cabang', '=', 'oman_cabang.kode_cabang');
                }
            )

            ->leftJoin(
                DB::raw("(
                SELECT
                    kode_cabang,
                    $s_produk_realisasi
                FROM
                    gudang_jadi_mutasi_detail
                INNER JOIN gudang_jadi_mutasi ON gudang_jadi_mutasi_detail.no_mutasi = gudang_jadi_mutasi.no_mutasi
                INNER JOIN marketing_permintaan_kiriman ON gudang_jadi_mutasi.no_permintaan = marketing_permintaan_kiriman.no_permintaan
                WHERE gudang_jadi_mutasi.tanggal BETWEEN '$dari' AND '$sampai' AND jenis_mutasi = 'SJ'
                GROUP BY kode_cabang
            ) realisasi_kiriman"),
                function ($join) {
                    $join->on('cabang.kode_cabang', '=', 'realisasi_kiriman.kode_cabang');
                }
            )
            ->get();
        $data['dari'] = $dari;
        $data['sampai'] = $sampai;
        $data['produk'] = $produk;


        $time = date('H:i:s');
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Realisasi OMAN $dari-$sampai-$time.xls");
        }
        return view('gudangjadi.laporan.realisasioman_cetak', $data);
    }


    public function cetakangkutan(Request $request)
    {
        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $query = Suratjalanangkutan::query();
        $query->select(
            'gudang_jadi_angkutan_suratjalan.*',
            'gudang_jadi_mutasi.tanggal',
            'nama_angkutan',
            'tujuan',
            'gudang_jadi_mutasi.keterangan',
            DB::raw('gudang_jadi_angkutan_suratjalan.tarif+tepung+bs as total_tarif'),
            'gudang_jadi_angkutan_kontrabon.tanggal as tanggal_kontrabon',
            'keuangan_ledger.tanggal as tanggal_bayar',
            'ledgerhutang.tanggal as tanggal_bayar_hutang'
        );
        $query->join('angkutan', 'gudang_jadi_angkutan_suratjalan.kode_angkutan', '=', 'angkutan.kode_angkutan');
        $query->join('angkutan_tujuan', 'gudang_jadi_angkutan_suratjalan.kode_tujuan', '=', 'angkutan_tujuan.kode_tujuan');
        $query->join('gudang_jadi_mutasi', 'gudang_jadi_angkutan_suratjalan.no_dok', '=', 'gudang_jadi_mutasi.no_dok');
        $query->leftJoin('gudang_jadi_angkutan_kontrabon_detail', 'gudang_jadi_angkutan_suratjalan.no_dok', '=', 'gudang_jadi_angkutan_kontrabon_detail.no_dok');
        $query->leftJoin('gudang_jadi_angkutan_kontrabon', 'gudang_jadi_angkutan_kontrabon_detail.no_kontrabon', '=', 'gudang_jadi_angkutan_kontrabon.no_kontrabon');
        $query->leftJoin('keuangan_ledger_kontrabonangkutan', 'gudang_jadi_angkutan_kontrabon.no_kontrabon', '=', 'keuangan_ledger_kontrabonangkutan.no_kontrabon');
        $query->leftJoin('keuangan_ledger', 'keuangan_ledger_kontrabonangkutan.no_bukti', '=', 'keuangan_ledger.no_bukti');
        $query->leftJoin('keuangan_ledger_kontrabonangkutan_hutang', 'gudang_jadi_angkutan_kontrabon.no_kontrabon', '=', 'keuangan_ledger_kontrabonangkutan_hutang.no_kontrabon');
        $query->leftJoin('keuangan_ledger as ledgerhutang', 'keuangan_ledger_kontrabonangkutan_hutang.no_bukti', '=', 'ledgerhutang.no_bukti');

        $query->whereBetween('gudang_jadi_mutasi.tanggal', [$request->dari, $request->sampai]);
        if (!empty($request->kode_angkutan)) {
            $query->where('gudang_jadi_angkutan_suratjalan.kode_angkutan', $request->kode_angkutan);
        }
        $data['suratjalanangkutan'] = $query->get();
        $data['angkutan'] = Angkutan::where('kode_angkutan', $request->kode_angkutan)->first();
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $time = date('H:i:s');
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Realisasi Laporan Angkutan $request->dari-$request->sampai-$time.xls");
        }
        return view('gudangjadi.laporan.angkutan_cetak', $data);
    }
}
