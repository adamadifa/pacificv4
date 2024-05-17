<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailsaldoawalgudangcabang;
use App\Models\Produk;
use App\Models\Saldoawalgudangcabang;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class SaldoawalgudangcabangController extends Controller
{
    public function index(Request $request)
    {
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);
        $data['list_bulan'] = config('global.list_bulan');
        $data['nama_bulan'] = config('global.nama_bulan');
        $data['start_year'] = config('global.start_year');
        $query = Saldoawalgudangcabang::query();
        if (!empty($request->bulan)) {
            $query->where('bulan', $request->bulan);
        }
        if (!empty($request->tahun)) {
            $query->where('tahun', $request->tahun);
        } else {
            $query->where('tahun', date('Y'));
        }

        if (!empty($request->kondisi)) {
            $query->where('gudang_cabang_saldoawal.kondisi', $request->kondisi);
        }

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('gudang_cabang_saldoawal.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('gudang_cabang_saldoawal.kode_cabang', $request->kode_cabang_search);
        }
        $query->join('cabang', 'gudang_cabang_saldoawal.kode_cabang', '=', 'cabang.kode_cabang');
        $query->orderBy('tahun', 'desc');
        $query->orderBy('bulan');
        $saldo_awal = $query->paginate(15);
        $saldo_awal->appends(request()->all());
        $data['saldo_awal'] = $saldo_awal;
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('gudangcabang.saldoawal.index', $data);
    }


    public function create()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('gudangcabang.saldoawal.create', $data);
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

        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');

        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang;
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
        }

        //Cek Apakah Sudah Ada Saldo Atau Belum
        $ceksaldo = Saldoawalgudangcabang::count();
        // Cek Saldo Bulan Lalu
        $ceksaldobulanlalu = Saldoawalgudangcabang::where('bulan', $bulanlalu)->where('tahun', $tahunlalu)
            ->where('kode_cabang', $kode_cabang)
            ->where('kondisi', $request->kondisi)
            ->count();

        //Cek Saldo Bulan Ini
        $ceksaldobulanini = Saldoawalgudangcabang::where('bulan', $bulan)->where('tahun', $tahun)
            ->where('kode_cabang', $kode_cabang)
            ->where('kondisi', $request->kondisi)
            ->count();
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
                        gudang_cabang_saldoawal_detail
                    INNER JOIN gudang_cabang_saldoawal ON gudang_cabang_saldoawal_detail.kode_saldo_awal = gudang_cabang_saldoawal.kode_saldo_awal
                    WHERE bulan = '$bulan' AND tahun='$tahun' AND kondisi = '$request->kondisi' AND kode_cabang = '$kode_cabang'
                ) saldo_awal"),
                    function ($join) {
                        $join->on('produk.kode_produk', '=', 'saldo_awal.kode_produk');
                    }
                )
                ->orderBy('kode_produk')->get();
        } else {

            //Jika Saldo Bulan Lalu Ada Maka Hitung Saldo Awal Bulan Lalu - Mutasi Bulan Lalu
            $query = Produk::query();
            $query->select(
                'produk.kode_produk',
                'nama_produk',
                'isi_pcs_dus',
                'isi_pcs_pack',
                DB::raw('IFNULL(saldo_awal,0) + IFNULL(sisamutasi,0) as saldo_akhir')
            );
            $query->where('status_aktif_produk', 1);
            $query->leftJoin(
                DB::raw("(
                    SELECT
                        kode_produk,
                        jumlah as saldo_awal
                    FROM
                        gudang_cabang_saldoawal_detail
                    INNER JOIN gudang_cabang_saldoawal ON gudang_cabang_saldoawal_detail.kode_saldo_awal = gudang_cabang_saldoawal.kode_saldo_awal
                    WHERE bulan = '$bulanlalu' AND tahun='$tahunlalu' AND kondisi='$request->kondisi' AND kode_cabang = '$kode_cabang'
                ) saldo_awal"),
                function ($join) {
                    $join->on('produk.kode_produk', '=', 'saldo_awal.kode_produk');
                }
            );

            if ($request->kondisi == "GS") {
                $query->leftJoin(
                    DB::raw("(
                            SELECT kode_produk,
                            SUM(IF( in_out_good = 'I', jumlah, 0)) - SUM(IF( in_out_good = 'O', jumlah, 0)) as sisamutasi
                            FROM gudang_cabang_mutasi_detail
                            INNER JOIN gudang_cabang_mutasi
                            ON gudang_cabang_mutasi_detail.no_mutasi = gudang_cabang_mutasi.no_mutasi
                            WHERE tanggal BETWEEN '$tgl_dari_bulanlalu' AND '$tgl_sampai_bulanlalu'  AND kode_cabang = '$kode_cabang'  GROUP BY kode_produk
                        ) mutasi"),
                    function ($join) {
                        $join->on('produk.kode_produk', '=', 'mutasi.kode_produk');
                    }
                );
            } else {
                $query->leftJoin(
                    DB::raw("(
                            SELECT kode_produk,
                            SUM(IF( in_out_bad = 'I', jumlah, 0)) - SUM(IF( in_out_bad = 'O', jumlah, 0)) as sisamutasi
                            FROM gudang_cabang_mutasi_detail
                            INNER JOIN gudang_cabang_mutasi
                            ON gudang_cabang_mutasi_detail.no_mutasi = gudang_cabang_mutasi.no_mutasi
                            WHERE tanggal BETWEEN '$tgl_dari_bulanlalu' AND '$tgl_sampai_bulanlalu'  AND kode_cabang = '$kode_cabang'  GROUP BY kode_produk
                        ) mutasi"),
                    function ($join) {
                        $join->on('produk.kode_produk', '=', 'mutasi.kode_produk');
                    }
                );
            }

            $query->orderBy('kode_produk')->get();
            $produk = $query->get();
        }



        $data = ['produk', 'readonly'];

        if (empty($ceksaldo)) {
            $readonly = false;
            return view('gudangcabang.saldoawal.getdetailsaldo', compact($data));
        } else {
            if (empty($ceksaldobulanlalu) && empty($ceksaldobulanini)) {
                return 1;
            } else {
                $readonly = true;
                return view('gudangcabang.saldoawal.getdetailsaldo', compact($data));
            }
        }
    }


    public function store(Request $request)
    {
        $bulan = $request->bulan;
        $bln = $bulan < 10 ? "0" . $bulan : $bulan;
        $tahun = $request->tahun;
        $tanggal = $tahun . "-" . $bln . "-01";
        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');
        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang;
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
        }

        $kode_produk = $request->kode_produk;
        $jml_dus = $request->jml_dus;
        $jml_pack = $request->jml_pack;
        $jml_pcs = $request->jml_pcs;
        $isi_pcs_dus = $request->isi_pcs_dus;
        $isi_pcs_pack = $request->isi_pcs_pack;



        $kode_saldo_awal = $request->kondisi . $kode_cabang . $bln . substr($tahun, 2, 2);


        $bulanberikutnya = getbulandantahunberikutnya($bulan, $tahun, "bulan");
        $tahunberikutnya = getbulandantahunberikutnya($bulan, $tahun, "tahun");





        $cektutuplaporan = cektutupLaporan($tanggal, "gudangcabang");
        if ($cektutuplaporan > 0) {
            return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
        } else if (empty($kode_produk)) {
            return Redirect::back()->with(messageError('Silahkan Get Saldo Terlebih Dahulu !'));
        }
        DB::beginTransaction();
        try {
            // Cek Saldo Bulan Berikutnya
            $ceksaldobulanberikutnya = Saldoawalgudangcabang::where('bulan', $bulanberikutnya)->where('tahun', $tahunberikutnya)
                ->where('kode_cabang', $kode_cabang)
                ->where('kondisi', $request->kondisi)
                ->count();

            //Cek Saldo Bulan Ini
            $ceksaldobulanini = Saldoawalgudangcabang::where('bulan', $bulan)->where('tahun', $tahun)
                ->where('kode_cabang', $kode_cabang)
                ->where('kondisi', $request->kondisi)
                ->count();

            for ($i = 0; $i < count($kode_produk); $i++) {
                $dus = toNumber(!empty($jml_dus[$i]) ? $jml_dus[$i] : 0);
                $pack = toNumber(!empty($jml_pack[$i]) ? $jml_pack[$i] : 0);
                $pcs = toNumber(!empty($jml_pcs[$i]) ? $jml_pcs[$i] : 0);
                $jumlah = ($dus * $isi_pcs_dus[$i]) + ($pack * $isi_pcs_pack[$i]) + $pcs;
                echo $dus . "<br>";
                $detail_saldo[] = [
                    'kode_saldo_awal' => $kode_saldo_awal,
                    'kode_produk' => $kode_produk[$i],
                    'jumlah' => toNumber($jumlah)
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
                Saldoawalgudangcabang::where('kode_saldo_awal', $kode_saldo_awal)->delete();
            }
            if (!empty($detail_saldo)) {

                Saldoawalgudangcabang::create([
                    'kode_saldo_awal' => $kode_saldo_awal,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'kondisi' => $request->kondisi,
                    'kode_cabang' => $kode_cabang,
                    'tanggal'  => $tahun . "-" . $bulan . "-01"
                ]);

                $chunks_buffer = array_chunk($detail_saldo, 5);
                foreach ($chunks_buffer as $chunk_buffer) {
                    Detailsaldoawalgudangcabang::insert($chunk_buffer);
                }
            } else {
                DB::rollBack();
                return Redirect::back()->with(messageError('Detail Saldo Kosong'));
            }


            DB::commit();
            return redirect(route('sagudangcabang.index'))->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect(route('sagudangcabang.index'))->with(messageError($e->getMessage()));
        }
    }

    public function show($kode_saldo_awal)
    {
        $kode_saldo_awal = Crypt::decrypt($kode_saldo_awal);
        $saldo_awal = Saldoawalgudangcabang::join('cabang', 'gudang_cabang_saldoawal.kode_cabang', '=', 'cabang.kode_cabang')
            ->where('kode_saldo_awal', $kode_saldo_awal)->first();
        $detail = Detailsaldoawalgudangcabang::where('kode_saldo_awal', $kode_saldo_awal)
            ->join('produk', 'gudang_cabang_saldoawal_detail.kode_produk', '=', 'produk.kode_produk')
            ->get();
        $nama_bulan = config('global.nama_bulan');
        return view('gudangcabang.saldoawal.show', compact('saldo_awal', 'nama_bulan', 'detail'));
    }

    public function destroy($kode_saldo_awal)
    {
        $kode_saldo_awal = Crypt::decrypt($kode_saldo_awal);
        $saldo_awal = Saldoawalgudangcabang::where('kode_saldo_awal', $kode_saldo_awal)->first();
        try {
            $cektutuplaporan = cektutupLaporan($saldo_awal->tanggal, "gudangcabang");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            Saldoawalgudangcabang::where('kode_saldo_awal', $kode_saldo_awal)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
