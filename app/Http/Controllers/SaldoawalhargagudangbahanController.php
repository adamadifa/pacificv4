<?php

namespace App\Http\Controllers;

use App\Models\Barangpembelian;
use App\Models\Detailsaldoawalhargagudangbahan;
use App\Models\Saldoawalhargagudangbahan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class SaldoawalhargagudangbahanController extends Controller
{
    public function index(Request $request)
    {
        $list_bulan = config('global.list_bulan');
        $nama_bulan = config('global.nama_bulan');
        $start_year = config('global.start_year');
        $query = Saldoawalhargagudangbahan::query();
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

        return view('gudangbahan.saldoawalharga.index', compact('list_bulan', 'start_year', 'saldo_awal', 'nama_bulan'));
    }

    public function show($kode_saldo_awal)
    {
        $kode_saldo_awal = Crypt::decrypt($kode_saldo_awal);
        $saldo_awal = Saldoawalhargagudangbahan::where('kode_saldo_awal', $kode_saldo_awal)->first();
        $detail = Detailsaldoawalhargagudangbahan::where('kode_saldo_awal', $kode_saldo_awal)
            ->join('pembelian_barang', 'gudang_bahan_saldoawal_harga_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->join('pembelian_barang_kategori', 'pembelian_barang.kode_kategori', '=', 'pembelian_barang_kategori.kode_kategori')
            ->orderBy('pembelian_barang.kode_jenis_barang')
            ->orderByRaw('cast(substr(gudang_bahan_saldoawal_harga_detail.kode_barang from 4) AS UNSIGNED)')
            ->get();
        $nama_bulan = config('global.nama_bulan');
        return view('gudangbahan.saldoawalharga.show', compact('saldo_awal', 'nama_bulan', 'detail'));
    }


    public function create()
    {
        $list_bulan = config('global.list_bulan');
        $start_year = config('global.start_year');
        return view('gudangbahan.saldoawalharga.create', compact('list_bulan', 'start_year'));
    }

    public function store(Request $request)
    {
        $bulan = $request->bulan;
        $bln = $bulan < 10 ? "0" . $bulan : $bulan;
        $tahun = $request->tahun;
        $tanggal = $tahun . "-" . $bln . "-01";
        $kode_barang = $request->kode_barang;
        $harga = $request->harga;
        $kode_saldo_awal = "SA" . $bln . substr($tahun, 2, 2);


        $bulanberikutnya = getbulandantahunberikutnya($bulan, $tahun, "bulan");
        $tahunberikutnya = getbulandantahunberikutnya($bulan, $tahun, "tahun");

        $cektutuplaporan = cektutupLaporan($tanggal, "gudangbahan");
        if ($cektutuplaporan > 0) {
            return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
        } else if (empty($kode_barang)) {
            return Redirect::back()->with(messageError('Silahkan Get Saldo Terlebih Dahulu !'));
        }
        DB::beginTransaction();
        try {
            // Cek Saldo Bulan Berikutnya
            $ceksaldobulanberikutnya = Saldoawalhargagudangbahan::where('bulan', $bulanberikutnya)->where('tahun', $tahunberikutnya)->count();

            //Cek Saldo Bulan Ini
            $ceksaldobulanini = Saldoawalhargagudangbahan::where('bulan', $bulan)->where('tahun', $tahun)->count();

            for ($i = 0; $i < count($kode_barang); $i++) {
                $detail_saldo[] = [
                    'kode_saldo_awal' => $kode_saldo_awal,
                    'kode_barang' => $kode_barang[$i],
                    'harga' => !empty($harga[$i]) ? toNumber($harga[$i]) : 0,
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
                Saldoawalhargagudangbahan::where('kode_saldo_awal', $kode_saldo_awal)->delete();
            }

            Saldoawalhargagudangbahan::create([
                'kode_saldo_awal' => $kode_saldo_awal,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'tanggal'  => $tahun . "-" . $bulan . "-01"
            ]);

            $chunks_buffer = array_chunk($detail_saldo, 5);
            foreach ($chunks_buffer as $chunk_buffer) {
                Detailsaldoawalhargagudangbahan::insert($chunk_buffer);
            }


            DB::commit();
            return redirect(route('sahargagb.index'))->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect(route('sahargagb.index'))->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_saldo_awal)
    {
        $kode_saldo_awal = Crypt::decrypt($kode_saldo_awal);
        $saldo_awal = Saldoawalhargagudangbahan::where('kode_saldo_awal', $kode_saldo_awal)->first();
        try {
            $cektutuplaporan = cektutupLaporan($saldo_awal->tanggal, "gudangbahan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            Saldoawalhargagudangbahan::where('kode_saldo_awal', $kode_saldo_awal)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
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
        $ceksaldo = Saldoawalhargagudangbahan::count();
        // Cek Saldo Bulan Lalu
        $ceksaldobulanlalu = Saldoawalhargagudangbahan::where('bulan', $bulanlalu)->where('tahun', $tahunlalu)->count();

        //Cek Saldo Bulan Ini
        $ceksaldobulanini = Saldoawalhargagudangbahan::where('bulan', $bulan)->where('tahun', $tahun)->count();
        //Get Produk

        //Jika Saldo BUlan Lalu Kosong dan Saldo Bulan Ini Ada Maka Di Ambil Saldo BUlan Ini
        if (empty($ceksaldobulanlalu) && !empty($ceksaldobulanini)) {
            $barang = Barangpembelian::select(
                'pembelian_barang.kode_barang',
                'nama_barang',
                'nama_kategori',
                'harga'
            )
                ->join('pembelian_barang_kategori', 'pembelian_barang.kode_kategori', '=', 'pembelian_barang_kategori.kode_kategori')

                ->leftJoin(
                    DB::raw("(
                    SELECT
                        kode_barang,
                        harga
                    FROM
                        gudang_bahan_saldoawal_harga_detail
                    INNER JOIN gudang_bahan_saldoawal_harga ON gudang_bahan_saldoawal_harga_detail.kode_saldo_awal = gudang_bahan_saldoawal_harga.kode_saldo_awal
                    WHERE bulan = '$bulan' AND tahun='$tahun'
                ) saldo_awal"),
                    function ($join) {
                        $join->on('pembelian_barang.kode_barang', '=', 'saldo_awal.kode_barang');
                    }
                )
                ->where('status', 1)
                ->where('pembelian_barang.kode_group', 'GDB')
                ->where('pembelian_barang.kode_kategori', '!=', 'K002')
                ->orderBy('pembelian_barang.kode_jenis_barang')
                ->orderByRaw('cast(substr(pembelian_barang.kode_barang FROM 4) AS UNSIGNED)')
                ->get();
        } else {

            //Jika Saldo Bulan Lalu Ada Maka Hitung Saldo Awal Bulan Lalu - Mutasi Bulan Lalu
            $barang = Barangpembelian::select(
                'pembelian_barang.kode_barang',
                'nama_barang',
                'satuan',
                'kode_jenis_barang',

                'saldo_awal_qty_unit',
                'saldo_awal_qty_berat',
                'saldo_awal_harga',

                'opname_qty_unit',
                'opname_qty_berat',

                'bm_qty_unit_pembelian',
                'bm_qty_unit_lainnya',
                'bm_qty_unit_returpengganti',

                'bm_qty_berat_pembelian',
                'bm_qty_berat_lainnya',
                'bm_qty_berat_returpengganti',

                'bk_qty_unit_produksi',
                'bk_qty_unit_seasoning',
                'bk_qty_unit_pdqc',
                'bk_qty_unit_susut',
                'bk_qty_unit_lainnya',
                'bk_qty_unit_cabang',

                'bk_qty_berat_produksi',
                'bk_qty_berat_seasoning',
                'bk_qty_berat_pdqc',
                'bk_qty_berat_susut',
                'bk_qty_berat_lainnya',
                'bk_qty_berat_cabang',

                DB::raw('IFNULL(saldo_awal_qty_unit,0) + IFNULL(bm_qty_unit,0) - IFNULL(bk_qty_unit,0) as saldo_akhir_unit'),
                DB::raw('IFNULL(saldo_awal_qty_berat,0) + IFNULL(bm_qty_berat,0) - IFNULL(bk_qty_berat,0) as saldo_akhir_berat'),
                'total_harga'
            )

                //Saldo Awal
                ->leftJoin(
                    DB::raw("(
                    SELECT gudang_bahan_saldoawal_detail.kode_barang,
                    qty_unit AS saldo_awal_qty_unit,
                    qty_berat AS saldo_awal_qty_berat
                    FROM gudang_bahan_saldoawal_detail
                    INNER JOIN gudang_bahan_saldoawal ON gudang_bahan_saldoawal_detail.kode_saldo_awal=gudang_bahan_saldoawal.kode_saldo_awal
                    WHERE bulan = '$bulanlalu' AND tahun = '$tahunlalu'
                ) saldo_awal"),
                    function ($join) {
                        $join->on('pembelian_barang.kode_barang', '=', 'saldo_awal.kode_barang');
                    }
                )
                //Opname
                ->leftJoin(
                    DB::raw("(
                    SELECT gudang_bahan_opname_detail.kode_barang,
                    qty_unit AS opname_qty_unit,
                    qty_berat AS opname_qty_berat
                    FROM gudang_bahan_opname_detail
                    INNER JOIN gudang_bahan_opname ON gudang_bahan_opname_detail.kode_opname=gudang_bahan_opname.kode_opname
                    WHERE bulan = '$bulanlalu' AND tahun = '$tahunlalu'
                ) opname"),
                    function ($join) {
                        $join->on('pembelian_barang.kode_barang', '=', 'opname.kode_barang');
                    }
                )

                //Pembelian
                ->leftJoin(
                    DB::raw("(
                    SELECT kode_barang,SUM((jumlah*harga)+penyesuaian) as total_harga
                    FROM pembelian_detail
                    INNER JOIN pembelian ON pembelian_detail.no_bukti = pembelian.no_bukti
                    WHERE tanggal BETWEEN '$tgl_dari_bulanlalu' AND '$tgl_sampai_bulanlalu'
                    GROUP BY kode_barang
                ) pembelian"),
                    function ($join) {
                        $join->on('pembelian_barang.kode_barang', '=', 'pembelian.kode_barang');
                    }
                )


                //Saldo Awal Harga

                ->leftJoin(
                    DB::raw("(
                    SELECT kode_barang,harga as saldo_awal_harga
                    FROM gudang_bahan_saldoawal_harga_detail
                    INNER JOIN gudang_bahan_saldoawal_harga ON gudang_bahan_saldoawal_harga_detail.kode_saldo_awal = gudang_bahan_saldoawal_harga.kode_saldo_awal
                    WHERE bulan = '$bulanlalu' AND tahun = '$tahunlalu'
                ) saldo_awal_harga"),
                    function ($join) {
                        $join->on('pembelian_barang.kode_barang', '=', 'saldo_awal_harga.kode_barang');
                    }
                )

                ->leftJoin(
                    DB::raw("(
                    SELECT
                    gudang_bahan_barang_masuk_detail.kode_barang,
                    SUM( IF( kode_asal_barang = 'PMB',qty_unit ,0 )) AS bm_qty_unit_pembelian,
                    SUM( IF( kode_asal_barang = 'LNY',qty_unit ,0 )) AS bm_qty_unit_lainnya,
                    SUM( IF( kode_asal_barang = 'RTP',qty_unit ,0 )) AS bm_qty_unit_returpengganti,

                    SUM( IF( kode_asal_barang = 'PMB',qty_berat ,0 )) AS bm_qty_berat_pembelian,
                    SUM( IF( kode_asal_barang = 'LNY',qty_berat ,0 )) AS bm_qty_berat_lainnya,
                    SUM( IF( kode_asal_barang = 'RTP',qty_berat ,0 )) AS bm_qty_berat_returpengganti,

                    SUM(qty_unit) as bm_qty_unit,
                    SUM(qty_berat) as bm_qty_berat
                    FROM
                    gudang_bahan_barang_masuk_detail
                    INNER JOIN gudang_bahan_barang_masuk ON gudang_bahan_barang_masuk_detail.no_bukti = gudang_bahan_barang_masuk.no_bukti
                    WHERE tanggal BETWEEN '$tgl_dari_bulanlalu' AND '$tgl_sampai_bulanlalu'
                    GROUP BY gudang_bahan_barang_masuk_detail.kode_barang
                ) barangmasuk"),
                    function ($join) {
                        $join->on('pembelian_barang.kode_barang', '=', 'barangmasuk.kode_barang');
                    }
                )

                ->leftJoin(
                    DB::raw("(
                    SELECT
                    gudang_bahan_barang_keluar_detail.kode_barang,
                    SUM( IF( gudang_bahan_barang_keluar.kode_jenis_pengeluaran = 'PRD' , qty_unit ,0 )) AS bk_qty_unit_produksi,
                    SUM( IF( gudang_bahan_barang_keluar.kode_jenis_pengeluaran = 'SSN' , qty_unit ,0 )) AS bk_qty_unit_seasoning,
                    SUM( IF( gudang_bahan_barang_keluar.kode_jenis_pengeluaran = 'PDQ' , qty_unit ,0 )) AS bk_qty_unit_pdqc,
                    SUM( IF( gudang_bahan_barang_keluar.kode_jenis_pengeluaran = 'SST' , qty_unit ,0 )) AS bk_qty_unit_susut,
                    SUM( IF( gudang_bahan_barang_keluar.kode_jenis_pengeluaran = 'LNY' , qty_unit ,0 )) AS bk_qty_unit_lainnya,
                    SUM( IF( gudang_bahan_barang_keluar.kode_jenis_pengeluaran = 'CBG' , qty_unit ,0 )) AS bk_qty_unit_cabang,


                    SUM( IF( gudang_bahan_barang_keluar.kode_jenis_pengeluaran = 'PRD' , qty_berat ,0 )) AS bk_qty_berat_produksi,
                    SUM( IF( gudang_bahan_barang_keluar.kode_jenis_pengeluaran = 'SSN' , qty_berat ,0 )) AS bk_qty_berat_seasoning,
                    SUM( IF( gudang_bahan_barang_keluar.kode_jenis_pengeluaran = 'PDQ' , qty_berat ,0 )) AS bk_qty_berat_pdqc,
                    SUM( IF( gudang_bahan_barang_keluar.kode_jenis_pengeluaran = 'SST' , qty_berat ,0 )) AS bk_qty_berat_susut,
                    SUM( IF( gudang_bahan_barang_keluar.kode_jenis_pengeluaran = 'LNY' , qty_berat ,0 )) AS bk_qty_berat_lainnya,
                    SUM( IF( gudang_bahan_barang_keluar.kode_jenis_pengeluaran = 'CBG' , qty_berat ,0 )) AS bk_qty_berat_cabang,

                    SUM(qty_unit) as bk_qty_unit,
                    SUM(qty_berat) as bk_qty_berat


                    FROM gudang_bahan_barang_keluar_detail
                    INNER JOIN gudang_bahan_barang_keluar ON gudang_bahan_barang_keluar_detail.no_bukti = gudang_bahan_barang_keluar.no_bukti
                    WHERE tanggal BETWEEN '$tgl_dari_bulanlalu' AND '$tgl_sampai_bulanlalu'
                    GROUP BY gudang_bahan_barang_keluar_detail.kode_barang
                ) barangkeluar"),
                    function ($join) {
                        $join->on('pembelian_barang.kode_barang', '=', 'barangkeluar.kode_barang');
                    }
                )
                ->where('pembelian_barang.kode_group', 'GDB')
                ->orderBy('kode_jenis_barang')
                ->orderByRaw('cast(substr(pembelian_barang.kode_barang from 4) AS UNSIGNED)')
                ->orderBy('nama_barang')
                ->get();
        }


        $dari = $tgl_dari_bulanlalu;
        $data = ['barang', 'readonly', 'dari'];

        if (empty($ceksaldo)) {
            $readonly = false;
            return view('gudangbahan.saldoawalharga.getdetailsaldo', compact($data));
        } else {
            if (empty($ceksaldobulanlalu) && empty($ceksaldobulanini)) {
                return 1;
            } else {
                $readonly = true;
                return view('gudangbahan.saldoawalharga.getdetailsaldo', compact($data));
            }
        }
    }
}
