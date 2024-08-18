<?php

namespace App\Http\Controllers;

use App\Models\Barangpembelian;
use App\Models\Detailopnamegudanglogistik;
use App\Models\Kategoribarangpembelian;
use App\Models\Opnamegudanglogistik;
use App\Models\Saldoawalgudanglogistik;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class OpnamegudanglogistikController extends Controller
{
    public function index(Request $request)
    {

        $list_bulan = config('global.list_bulan');
        $nama_bulan = config('global.nama_bulan');
        $start_year = config('global.start_year');
        $query = Opnamegudanglogistik::query();
        if (!empty($request->bulan)) {
            $query->where('bulan', $request->bulan);
        }
        if (!empty($request->tahun)) {
            $query->where('tahun', $request->tahun);
        } else {
            $query->where('tahun', date('Y'));
        }
        if (!empty($request->kode_kategori)) {
            $query->where('gudang_logistik_opname.kode_kategori', $request->kode_kategori);
        }
        $query->join('pembelian_barang_kategori', 'gudang_logistik_opname.kode_kategori', '=', 'pembelian_barang_kategori.kode_kategori');
        $query->orderBy('tahun', 'desc');
        $query->orderBy('bulan');
        $opname = $query->get();
        $kategori = Kategoribarangpembelian::where('kode_group', 'GDL')->orderBy('kode_kategori')->get();
        return view('gudanglogistik.opname.index', compact('list_bulan', 'start_year', 'opname', 'nama_bulan', 'kategori'));
    }

    public function create()
    {
        $list_bulan = config('global.list_bulan');
        $start_year = config('global.start_year');
        $kategori = Kategoribarangpembelian::where('kode_group', 'GDL')->orderBy('kode_kategori')->get();
        return view('gudanglogistik.opname.create', compact('list_bulan', 'start_year', 'kategori'));
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
        $jumlah = $request->jumlah;

        //SAMP = Saldo Awal Mutasi Produksi
        $kode_opname = "OPGL" . $bln . substr($tahun, 2, 2) . $request->kode_kategori;

        $cektutuplaporan = cektutupLaporan($tanggal, "gudanglogistik");
        if ($cektutuplaporan > 0) {
            return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
        } else if (empty($kode_barang)) {
            return Redirect::back()->with(messageError('Silahkan Get Saldo Terlebih Dahulu !'));
        }
        DB::beginTransaction();
        try {
            // Cek Saldo Bulan Berikutnya

            //Cek Saldo Bulan Ini
            $ceksaldobulanini = Saldoawalgudanglogistik::where('bulan', $bulan)->where('tahun', $tahun)
                ->where('kode_kategori', $request->kode_kategori)
                ->count();

            for ($i = 0; $i < count($kode_barang); $i++) {
                $detail_saldo[] = [
                    'kode_opname' => $kode_opname,
                    'kode_barang' => $kode_barang[$i],
                    'jumlah' => toNumber($jumlah[$i]),
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
                Opnamegudanglogistik::where('kode_opname', $kode_opname)->delete();
            }

            Opnamegudanglogistik::create([
                'kode_opname' => $kode_opname,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'kode_kategori' => $request->kode_kategori,
                'tanggal'  => $tanggal
            ]);

            $chunks_buffer = array_chunk($detail_saldo, 5);
            foreach ($chunks_buffer as $chunk_buffer) {
                Detailopnamegudanglogistik::insert($chunk_buffer);
            }


            DB::commit();
            return redirect(route('opgudanglogistik.index'))->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect(route('opgudanglogistik.index'))->with(messageError($e->getMessage()));
        }
    }

    public function edit($kode_opname)
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $kode_opname = Crypt::decrypt($kode_opname);
        $data['opname'] = Opnamegudanglogistik::where('kode_opname', $kode_opname)->first();
        $data['detail'] = Detailopnamegudanglogistik::where('kode_opname', $kode_opname)
            ->join('pembelian_barang', 'gudang_logistik_opname_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->join('pembelian_barang_kategori', 'pembelian_barang.kode_kategori', '=', 'pembelian_barang_kategori.kode_kategori')
            ->orderBy('pembelian_barang.nama_barang')
            ->get();
        $data['kategori'] = Kategoribarangpembelian::where('kode_group', 'GDL')->orderBy('kode_kategori')->get();
        return view('gudanglogistik.opname.edit', $data);
    }

    public function show($kode_opname)
    {
        $kode_opname = Crypt::decrypt($kode_opname);
        $data['opname'] = Opnamegudanglogistik::where('kode_opname', $kode_opname)->first();
        $data['detail'] = Detailopnamegudanglogistik::where('kode_opname', $kode_opname)
            ->join('pembelian_barang', 'gudang_logistik_opname_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->join('pembelian_barang_kategori', 'pembelian_barang.kode_kategori', '=', 'pembelian_barang_kategori.kode_kategori')
            ->orderBy('pembelian_barang.kode_jenis_barang')
            ->orderBy('pembelian_barang.nama_barang')
            ->get();
        $data['nama_bulan'] = config('global.nama_bulan');
        return view('gudanglogistik.opname.show', $data);
    }

    public function destroy($kode_opname)
    {
        $kode_opname = Crypt::decrypt($kode_opname);
        $opname = Opnamegudanglogistik::where('kode_opname', $kode_opname)->first();
        try {
            $cektutuplaporan = cektutupLaporan($opname->tanggal, "gudanglogistik");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            Opnamegudanglogistik::where('kode_opname', $kode_opname)->delete();
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

        // Cek Saldo Awal Bulan Ini
        $ceksaldobulanini = Saldoawalgudanglogistik::where('bulan', $bulan)->where('tahun', $tahun)
            ->where('kode_kategori', $request->kode_kategori)
            ->count();
        if (empty($ceksaldobulanini)) {
            return 1;
        } else {
            $barang = Barangpembelian::select(
                'pembelian_barang.kode_barang',
                'nama_barang',
                'saldo_awal_jumlah',
                'bm_jumlah',
                'bk_jumlah'
            )


                ->leftJoin(
                    DB::raw("(
                    SELECT
                        kode_barang,
                        jumlah as saldo_awal_jumlah
                    FROM
                        gudang_logistik_saldoawal_detail
                    INNER JOIN gudang_logistik_saldoawal ON gudang_logistik_saldoawal_detail.kode_saldo_awal = gudang_logistik_saldoawal.kode_saldo_awal
                    WHERE bulan = '$bulan' AND tahun='$tahun'
                ) saldo_awal"),
                    function ($join) {
                        $join->on('pembelian_barang.kode_barang', '=', 'saldo_awal.kode_barang');
                    }
                )

                ->leftJoin(
                    DB::raw("(
                        SELECT
                        gudang_logistik_barang_masuk_detail.kode_barang,
                        SUM(jumlah) as bm_jumlah
                        FROM
                        gudang_logistik_barang_masuk_detail
                        INNER JOIN gudang_logistik_barang_masuk ON gudang_logistik_barang_masuk_detail.no_bukti = gudang_logistik_barang_masuk.no_bukti
                        WHERE tanggal BETWEEN '$dari' AND '$sampai'
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
                        WHERE tanggal BETWEEN '$dari' AND '$sampai'
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
        if (empty($ceksaldo)) {
            $readonly = false;
            return view('gudanglogistik.opname.getdetailsaldo', compact($data));
        } else {
            if (empty($ceksaldobulanlalu) && empty($ceksaldobulanini)) {
                return 1;
            } else {
                $readonly = true;
                return view('gudanglogistik.opname.getdetailsaldo', compact($data));
            }
        }
    }
}
