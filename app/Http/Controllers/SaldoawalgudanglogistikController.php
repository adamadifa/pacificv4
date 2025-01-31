<?php

namespace App\Http\Controllers;

use App\Models\Barangpembelian;
use App\Models\Detailsaldoawalgudanglogistik;
use App\Models\Kategoribarangpembelian;
use App\Models\Saldoawalgudanglogistik;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class SaldoawalgudanglogistikController extends Controller
{
    public function index(Request $request)
    {

        $list_bulan = config('global.list_bulan');
        $nama_bulan = config('global.nama_bulan');
        $start_year = config('global.start_year');
        $query = Saldoawalgudanglogistik::query();
        if (!empty($request->bulan)) {
            $query->where('bulan', $request->bulan);
        }
        if (!empty($request->tahun)) {
            $query->where('tahun', $request->tahun);
        } else {
            $query->where('tahun', date('Y'));
        }

        if (!empty($request->kode_kategori)) {
            $query->where('gudang_logistik_saldoawal.kode_kategori', $request->kode_kategori);
        }
        $query->join('pembelian_barang_kategori', 'gudang_logistik_saldoawal.kode_kategori', '=', 'pembelian_barang_kategori.kode_kategori');
        $query->orderBy('tahun', 'desc');
        $query->orderBy('bulan');
        $saldo_awal = $query->get();
        $kategori = Kategoribarangpembelian::where('kode_group', 'GDL')->orderBy('kode_kategori')->get();
        return view('gudanglogistik.saldoawal.index', compact(
            'list_bulan',
            'start_year',
            'saldo_awal',
            'nama_bulan',
            'kategori'
        ));
    }

    public function create()
    {
        $list_bulan = config('global.list_bulan');
        $start_year = config('global.start_year');
        $kategori = Kategoribarangpembelian::where('kode_group', 'GDL')->orderBy('kode_kategori')->get();
        return view('gudanglogistik.saldoawal.create', compact('list_bulan', 'start_year', 'kategori'));
    }

    public function show($kode_saldo_awal)
    {
        $kode_saldo_awal = Crypt::decrypt($kode_saldo_awal);
        $saldo_awal = Saldoawalgudanglogistik::where('kode_saldo_awal', $kode_saldo_awal)
            ->join('pembelian_barang_kategori', 'gudang_logistik_saldoawal.kode_kategori', '=', 'pembelian_barang_kategori.kode_kategori')
            ->first();
        $detail = Detailsaldoawalgudanglogistik::where('kode_saldo_awal', $kode_saldo_awal)
            ->join('pembelian_barang', 'gudang_logistik_saldoawal_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->get();
        $nama_bulan = config('global.nama_bulan');
        return view('gudanglogistik.saldoawal.show', compact('saldo_awal', 'nama_bulan', 'detail'));
    }

    public function store(Request $request)
    {
        $bulan = $request->bulan;
        $bln = $bulan < 10 ? "0" . $bulan : $bulan;
        $tahun = $request->tahun;
        $tanggal = $tahun . "-" . $bln . "-01";
        $kode_barang = $request->kode_barang;
        $jumlah = $request->jumlah;
        $harga = $request->harga;
        //SAMP = Saldo Awal Mutasi Produksi
        $kode_saldo_awal = "SAGL" . $bln . substr($tahun, 2, 2) . $request->kode_kategori;


        $bulanberikutnya = getbulandantahunberikutnya($bulan, $tahun, "bulan");
        $tahunberikutnya = getbulandantahunberikutnya($bulan, $tahun, "tahun");

        $cektutuplaporan = cektutupLaporan($tanggal, "gudanglogistik");
        if ($cektutuplaporan > 0) {
            return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
        } else if (empty($kode_barang)) {
            return Redirect::back()->with(messageError('Silahkan Get Saldo Terlebih Dahulu !'));
        }
        DB::beginTransaction();
        try {
            // Cek Saldo Bulan Berikutnya
            $ceksaldobulanberikutnya = Saldoawalgudanglogistik::where('bulan', $bulanberikutnya)->where('tahun', $tahunberikutnya)
                ->where('kode_kategori', $request->kode_kategori)
                ->count();

            //Cek Saldo Bulan Ini
            $ceksaldobulanini = Saldoawalgudanglogistik::where('bulan', $bulan)->where('tahun', $tahun)
                ->where('kode_kategori', $request->kode_kategori)
                ->count();

            for ($i = 0; $i < count($kode_barang); $i++) {
                $detail_saldo[] = [
                    'kode_saldo_awal' => $kode_saldo_awal,
                    'kode_barang' => $kode_barang[$i],
                    'jumlah' => toNumber($jumlah[$i]),
                    'harga' => toNumber($harga[$i])
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
                Saldoawalgudanglogistik::where('kode_saldo_awal', $kode_saldo_awal)->delete();
            }

            Saldoawalgudanglogistik::create([
                'kode_saldo_awal' => $kode_saldo_awal,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'tanggal'  => $tahun . "-" . $bulan . "-01",
                'kode_kategori' => $request->kode_kategori
            ]);

            $chunks_buffer = array_chunk($detail_saldo, 5);
            foreach ($chunks_buffer as $chunk_buffer) {
                Detailsaldoawalgudanglogistik::insert($chunk_buffer);
            }


            DB::commit();
            return redirect(route('sagudanglogistik.index'))->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect(route('sagudanglogistik.index'))->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_saldo_awal)
    {
        $kode_saldo_awal = Crypt::decrypt($kode_saldo_awal);
        $saldo_awal = Saldoawalgudanglogistik::where('kode_saldo_awal', $kode_saldo_awal)->first();
        try {
            $cektutuplaporan = cektutupLaporan($saldo_awal->tanggal, "gudanglogistik");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            Saldoawalgudanglogistik::where('kode_saldo_awal', $kode_saldo_awal)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    //AJAX REQEUST

    public function getdetailsaldo(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $bulanlalu = getbulandantahunlalu($bulan, $tahun, "bulan");
        $tahunlalu = getbulandantahunlalu($bulan, $tahun, "tahun");

        $tgl_dari_bulanlalu = $tahunlalu . "-" . $bulanlalu . "-01";
        $tgl_sampai_bulanlalu = date('Y-m-t', strtotime($tgl_dari_bulanlalu));

        //Cek Apakah Sudah Ada Saldo Atau Belum
        $ceksaldo = Saldoawalgudanglogistik::where('kode_kategori', $request->kode_kategori)->where('tahun', $request->tahun)->count();
        // Cek Saldo Bulan Lalu
        $ceksaldobulanlalu = Saldoawalgudanglogistik::where('bulan', $bulanlalu)->where('tahun', $tahunlalu)
            ->where('kode_kategori', $request->kode_kategori)
            ->count();

        //Cek Saldo Bulan Ini
        $ceksaldobulanini = Saldoawalgudanglogistik::where('bulan', $bulan)->where('tahun', $tahun)
            ->where('kode_kategori', $request->kode_kategori)
            ->count();
        //Get Produk

        //Jika Saldo BUlan Lalu Kosong dan Saldo Bulan Ini Ada Maka Di Ambil Saldo BUlan Ini
        if (empty($ceksaldobulanlalu) && !empty($ceksaldobulanini)) {
            $barang = Barangpembelian::select(
                'pembelian_barang.kode_barang',
                'nama_barang',
                'saldo_awal_jumlah',
                'saldo_awal_harga'
            )
                ->leftJoin(
                    DB::raw("(
                    SELECT
                        kode_barang,
                        jumlah as saldo_awal_jumlah,
                        harga as saldo_awal_harga
                    FROM
                        gudang_logistik_saldoawal_detail
                    INNER JOIN gudang_logistik_saldoawal ON gudang_logistik_saldoawal_detail.kode_saldo_awal = gudang_logistik_saldoawal.kode_saldo_awal
                    WHERE bulan = '$bulan' AND tahun='$tahun'
                ) saldo_awal"),
                    function ($join) {
                        $join->on('pembelian_barang.kode_barang', '=', 'saldo_awal.kode_barang');
                    }
                )
                ->where('status', 1)
                ->where('pembelian_barang.kode_group', 'GDL')
                ->where('pembelian_barang.kode_kategori', $request->kode_kategori)
                ->get();
        } else {

            //Jika Saldo Bulan Lalu Ada Maka Hitung Saldo Awal Bulan Lalu - Mutasi Bulan Lalu
            $barang = Barangpembelian::select(
                'pembelian_barang.kode_barang',
                'nama_barang',
                'saldo_awal_jumlah',
                'saldo_awal_harga',
                'saldo_awal_totalharga',

                'bm_jumlah',
                'bm_harga',
                'bm_totalharga',

                'bk_jumlah'
            )


                ->leftJoin(
                    DB::raw("(
                    SELECT
                        kode_barang,
                        jumlah as saldo_awal_jumlah,
                        harga as saldo_awal_harga,
                        IFNULL(jumlah,0) * IFNULL(harga,0) as saldo_awal_totalharga
                    FROM
                        gudang_logistik_saldoawal_detail
                    INNER JOIN gudang_logistik_saldoawal ON gudang_logistik_saldoawal_detail.kode_saldo_awal = gudang_logistik_saldoawal.kode_saldo_awal
                    WHERE bulan = '$bulanlalu' AND tahun='$tahunlalu'
                ) saldo_awal"),
                    function ($join) {
                        $join->on('pembelian_barang.kode_barang', '=', 'saldo_awal.kode_barang');
                    }
                )

                ->leftJoin(
                    DB::raw("(
                        SELECT
                        gudang_logistik_barang_masuk_detail.kode_barang,
                        SUM(jumlah) as bm_jumlah,
                        SUM(harga) as bm_harga,
                        SUM(IFNULL(jumlah,0)*IFNULL(harga,0)) as bm_totalharga
                        FROM
                        gudang_logistik_barang_masuk_detail
                        INNER JOIN gudang_logistik_barang_masuk ON gudang_logistik_barang_masuk_detail.no_bukti = gudang_logistik_barang_masuk.no_bukti
                        WHERE tanggal BETWEEN '$tgl_dari_bulanlalu' AND '$tgl_sampai_bulanlalu'
                        GROUP BY gudang_logistik_barang_masuk_detail.kode_barang
                    ) barang_masuk"),
                    function ($join) {
                        $join->on('pembelian_barang.kode_barang', '=', 'barang_masuk.kode_barang');
                    }
                )

                ->leftJoin(
                    DB::raw("(
                        SELECT
                        gudang_logistik_barang_keluar_detail.kode_barang,
                        SUM(jumlah) as bk_jumlah
                        FROM
                        gudang_logistik_barang_keluar_detail
                        INNER JOIN gudang_logistik_barang_keluar ON gudang_logistik_barang_keluar_detail.no_bukti = gudang_logistik_barang_keluar.no_bukti
                        WHERE tanggal BETWEEN '$tgl_dari_bulanlalu' AND '$tgl_sampai_bulanlalu'
                        GROUP BY gudang_logistik_barang_keluar_detail.kode_barang
                    ) barang_keluar"),
                    function ($join) {
                        $join->on('pembelian_barang.kode_barang', '=', 'barang_keluar.kode_barang');
                    }
                )
                ->where('status', 1)
                ->where('pembelian_barang.kode_group', 'GDL')
                ->where('pembelian_barang.kode_kategori', $request->kode_kategori)
                ->orderBy('pembelian_barang.nama_barang')
                ->get();
        }



        $data = ['barang', 'readonly'];
        //dd($request->kode_kategori);
        if (empty($ceksaldo) && empty($ceksaldobulanlalu)) {
            $readonly = false;
            return view('gudanglogistik.saldoawal.getdetailsaldo', compact($data));
        } else {
            if (empty($ceksaldobulanlalu) && empty($ceksaldobulanini)) {
                return 1;
            } else {
                $readonly = true;
                return view('gudanglogistik.saldoawal.getdetailsaldo', compact($data));
            }
        }
    }
}
