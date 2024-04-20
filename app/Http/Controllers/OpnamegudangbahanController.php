<?php

namespace App\Http\Controllers;

use App\Models\Barangpembelian;
use App\Models\Detailopnamegudangbahan;
use App\Models\Opnamegudangbahan;
use App\Models\Saldoawalgudangbahan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class OpnamegudangbahanController extends Controller
{
    public function index(Request $request)
    {

        $list_bulan = config('global.list_bulan');
        $nama_bulan = config('global.nama_bulan');
        $start_year = config('global.start_year');
        $query = Opnamegudangbahan::query();
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
        return view('gudangbahan.opname.index', compact('list_bulan', 'start_year', 'saldo_awal', 'nama_bulan'));
    }

    public function create()
    {
        $list_bulan = config('global.list_bulan');
        $start_year = config('global.start_year');
        return view('gudangbahan.opname.create', compact('list_bulan', 'start_year'));
    }

    public function store(Request $request)
    {
        $bulan = $request->bulan;
        $bln = $bulan < 10 ? "0" . $bulan : $bulan;
        $tahun = $request->tahun;
        $dari = $tahun . "-" . $bulan . "-01";
        $sampai = date('Y-m-t', strtotime($dari));
        $tanggal = $sampai;
        $kode_barang = $request->kode_barang;
        $qty_unit = $request->qty_unit;
        $qty_berat = $request->qty_berat;
        //SAMP = Saldo Awal Mutasi Produksi
        $kode_opname = "OPGB" . $bln . substr($tahun, 2, 2);

        $cektutuplaporan = cektutupLaporan($tanggal, "gudangbahan");
        if ($cektutuplaporan > 0) {
            return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
        } else if (empty($kode_barang)) {
            return Redirect::back()->with(messageError('Silahkan Get Saldo Terlebih Dahulu !'));
        }
        DB::beginTransaction();
        try {
            // Cek Saldo Bulan Berikutnya

            //Cek Saldo Bulan Ini
            $ceksaldobulanini = Saldoawalgudangbahan::where('bulan', $bulan)->where('tahun', $tahun)->count();

            for ($i = 0; $i < count($kode_barang); $i++) {
                $detail_saldo[] = [
                    'kode_opname' => $kode_opname,
                    'kode_barang' => $kode_barang[$i],
                    'qty_unit' => !empty($qty_unit[$i]) ? toNumber($qty_unit[$i]) : 0,
                    'qty_berat' => !empty($qty_berat[$i]) ? toNumber($qty_berat[$i]) : 0
                ];
            }

            $timestamp = Carbon::now();

            foreach ($detail_saldo as &$record) {
                $record['created_at'] = $timestamp;
                $record['updated_at'] = $timestamp;
            }



            if (empty($ceksaldobulanini)) {
                return Redirect::back()->with(messageError('Saldo Awal Bulan Ini Belum Di Set'));
            } else {
                Opnamegudangbahan::where('kode_opname', $kode_opname)->delete();
            }

            Opnamegudangbahan::create([
                'kode_opname' => $kode_opname,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'tanggal'  => $tanggal
            ]);

            $chunks_buffer = array_chunk($detail_saldo, 5);
            foreach ($chunks_buffer as $chunk_buffer) {
                Detailopnamegudangbahan::insert($chunk_buffer);
            }


            DB::commit();
            return redirect(route('opgudangbahan.index'))->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect(route('opgudangbahan.index'))->with(messageError($e->getMessage()));
        }
    }


    public function edit($kode_opname)
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $kode_opname = Crypt::decrypt($kode_opname);
        $data['opname'] = Opnamegudangbahan::where('kode_opname', $kode_opname)->first();
        $data['detail'] = Detailopnamegudangbahan::where('kode_opname', $kode_opname)
            ->join('pembelian_barang', 'gudang_bahan_opname_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->join('pembelian_barang_kategori', 'pembelian_barang.kode_kategori', '=', 'pembelian_barang_kategori.kode_kategori')
            ->orderBy('pembelian_barang.kode_jenis_barang')
            ->orderByRaw('cast(substr(gudang_bahan_opname_detail.kode_barang from 4) AS UNSIGNED)')
            ->get();
        return view('gudangbahan.opname.edit', $data);
    }
    public function show($kode_opname)
    {
        $kode_opname = Crypt::decrypt($kode_opname);
        $data['opname'] = Opnamegudangbahan::where('kode_opname', $kode_opname)->first();
        $data['detail'] = Detailopnamegudangbahan::where('kode_opname', $kode_opname)
            ->join('pembelian_barang', 'gudang_bahan_opname_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->join('pembelian_barang_kategori', 'pembelian_barang.kode_kategori', '=', 'pembelian_barang_kategori.kode_kategori')
            ->orderBy('pembelian_barang.kode_jenis_barang')
            ->orderByRaw('cast(substr(gudang_bahan_opname_detail.kode_barang from 4) AS UNSIGNED)')
            ->get();
        $data['nama_bulan'] = config('global.nama_bulan');
        return view('gudangbahan.opname.show', $data);
    }

    public function destroy($kode_opname)
    {
        $kode_opname = Crypt::decrypt($kode_opname);
        $opname = Opnamegudangbahan::where('kode_opname', $kode_opname)->first();
        try {
            $cektutuplaporan = cektutupLaporan($opname->tanggal, "gudangbahan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            Opnamegudangbahan::where('kode_opname', $kode_opname)->delete();
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
        $dari = $tahun . "-" . $bulan . "-01";
        $sampai = date('Y-m-t', strtotime($dari));
        // $bulanlalu = getbulandantahunlalu($bulan, $tahun, "bulan");
        // $tahunlalu = getbulandantahunlalu($bulan, $tahun, "tahun");

        // $tgl_dari_bulanlalu = $tahunlalu . "-" . $bulanlalu . "-01";
        // $tgl_sampai_bulanlalu = date('Y-m-t', strtotime($tgl_dari_bulanlalu));


        // Cek Saldo Awal Bulan Ini
        $ceksaldobulanini = Saldoawalgudangbahan::where('bulan', $bulan)->where('tahun', $tahun)->count();

        if (empty($ceksaldobulanini)) {
            return 1;
        } else {
            //Jika Saldo Bulan Lalu Ada Maka Hitung Saldo Awal Bulan Lalu - Mutasi Bulan Lalu
            $data['barang'] = Barangpembelian::select(
                'pembelian_barang.kode_barang',
                'nama_barang',
                'nama_kategori',
                DB::raw('IFNULL(saldo_awal_unit,0) + IFNULL(bm_qty_unit,0) - IFNULL(bk_qty_unit,0) as saldo_unit'),
                DB::raw('IFNULL(saldo_awal_berat,0) + IFNULL(bm_qty_berat,0) - IFNULL(bk_qty_berat,0) as saldo_berat')
            )

                ->join('pembelian_barang_kategori', 'pembelian_barang.kode_kategori', '=', 'pembelian_barang_kategori.kode_kategori')
                ->leftJoin(
                    DB::raw("(
                    SELECT
                        kode_barang,
                        qty_unit as saldo_awal_unit,
                        qty_berat as saldo_awal_berat
                    FROM
                        gudang_bahan_saldoawal_detail
                    INNER JOIN gudang_bahan_saldoawal ON gudang_bahan_saldoawal_detail.kode_saldo_awal = gudang_bahan_saldoawal.kode_saldo_awal
                    WHERE bulan = '$bulan' AND tahun='$tahun'
                ) saldo_awal"),
                    function ($join) {
                        $join->on('pembelian_barang.kode_barang', '=', 'saldo_awal.kode_barang');
                    }
                )

                ->leftJoin(
                    DB::raw("(
                        SELECT
                        gudang_bahan_barang_masuk_detail.kode_barang,
                        SUM(qty_unit) as bm_qty_unit,
                        SUM(qty_berat) as bm_qty_berat
                        FROM
                        gudang_bahan_barang_masuk_detail
                        INNER JOIN gudang_bahan_barang_masuk ON gudang_bahan_barang_masuk_detail.no_bukti = gudang_bahan_barang_masuk.no_bukti
                        WHERE tanggal BETWEEN '$dari' AND '$sampai'
                        GROUP BY gudang_bahan_barang_masuk_detail.kode_barang
                    ) barang_masuk"),
                    function ($join) {
                        $join->on('pembelian_barang.kode_barang', '=', 'barang_masuk.kode_barang');
                    }
                )

                ->leftJoin(
                    DB::raw("(
                        SELECT
                        gudang_bahan_barang_keluar_detail.kode_barang,
                        SUM(qty_unit) as bk_qty_unit,
                        SUM(qty_berat) as bk_qty_berat
                        FROM
                        gudang_bahan_barang_keluar_detail
                        INNER JOIN gudang_bahan_barang_keluar ON gudang_bahan_barang_keluar_detail.no_bukti = gudang_bahan_barang_keluar.no_bukti
                        WHERE tanggal BETWEEN '$dari' AND '$sampai'
                        GROUP BY gudang_bahan_barang_keluar_detail.kode_barang
                    ) barang_keluar"),
                    function ($join) {
                        $join->on('pembelian_barang.kode_barang', '=', 'barang_keluar.kode_barang');
                    }
                )
                ->where('status', 1)
                ->where('pembelian_barang.kode_group', 'GDB')
                ->where('pembelian_barang.kode_kategori', '!=', 'K002')
                ->orderBy('pembelian_barang.kode_jenis_barang')
                ->orderByRaw('cast(substr(pembelian_barang.kode_barang FROM 4) AS UNSIGNED)')
                ->get();
            return view('gudangbahan.opname.getdetailsaldo', $data);
        }

        //Jika Saldo BUlan Lalu Kosong dan Saldo Bulan Ini Ada Maka Di Ambil Saldo BUlan Ini

    }
}
