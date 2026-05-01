<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailsaldoawalpiutangsalesman;
use App\Models\Historibayarpenjualan;
use App\Models\Penjualan;
use App\Models\Detailpenjualan;
use App\Models\Detailretur;
use App\Models\Movefaktur;
use App\Models\Saldoawalpiutangsalesman;
use App\Models\Salesman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class SaldoawalpiutangsalesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:sapiutangsales.index', ['only' => ['index', 'show']]);
        $this->middleware('permission:sapiutangsales.create', ['only' => ['create', 'store', 'getdetailsaldo']]);
        $this->middleware('permission:sapiutangsales.delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $list_bulan = config('global.list_bulan');
        $nama_bulan = config('global.nama_bulan');
        $start_year = config('global.start_year');
        $query = Saldoawalpiutangsalesman::query();
        $query->select('marketing_sa_piutangsales.*', 'nama_cabang');
        $query->join('cabang', 'marketing_sa_piutangsales.kode_cabang', '=', 'cabang.kode_cabang');
        if (!empty($request->bulan)) {
            $query->where('bulan', $request->bulan);
        }
        if (!empty($request->tahun)) {
            $query->where('tahun', $request->tahun);
        } else {
            $query->where('tahun', date('Y'));
        }
        $query->orderBy('tahun', 'desc');
        $query->orderBy('bulan', 'desc');
        $saldo_awal = $query->get();
        return view('marketing.sapiutangsales.index', compact('list_bulan', 'start_year', 'saldo_awal', 'nama_bulan'));
    }

    public function create()
    {
        $list_bulan = config('global.list_bulan');
        $start_year = config('global.start_year');
        $cabang = Cabang::orderBy('kode_cabang')->get();
        return view('marketing.sapiutangsales.create', compact('list_bulan', 'start_year', 'cabang'));
    }

    public function store(Request $request)
    {
        $bulan = $request->bulan;
        $bln = $bulan < 10 ? "0" . $bulan : $bulan;
        $tahun = $request->tahun;
        $kode_cabang = $request->kode_cabang;
        $tanggal = $tahun . "-" . $bln . "-01";
        $data_saldo = json_decode($request->data_saldo);
        $kode_saldo_awal = "SAPS" . $kode_cabang . $bln . substr($tahun, 2, 2);

        $cektutuplaporan = cektutupLaporan($tanggal, "marketing");
        if ($cektutuplaporan > 0) {
            return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
        } else if (empty($data_saldo)) {
            return Redirect::back()->with(messageError('Silahkan Get Saldo Terlebih Dahulu !'));
        }

        DB::beginTransaction();
        try {
            // Cek Saldo Bulan Berikutnya
            $bulanberikutnya = getbulandantahunberikutnya($bulan, $tahun, "bulan");
            $tahunberikutnya = getbulandantahunberikutnya($bulan, $tahun, "tahun");
            $ceksaldobulanberikutnya = Saldoawalpiutangsalesman::where('bulan', $bulanberikutnya)
                ->where('tahun', $tahunberikutnya)
                ->where('kode_cabang', $kode_cabang)
                ->count();

            if ($ceksaldobulanberikutnya > 0) {
                return Redirect::back()->with(messageError('Tidak Bisa Update Saldo, Dikarenakan Saldo Berikutnya sudah di Set'));
            }

            // Hapus saldo lama jika ada
            Saldoawalpiutangsalesman::where('kode_saldo_awal', $kode_saldo_awal)->delete();

            // Simpan Master Saldo Awal
            Saldoawalpiutangsalesman::create([
                'kode_saldo_awal' => $kode_saldo_awal,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'tanggal' => $tanggal,
                'kode_cabang' => $kode_cabang
            ]);

            // Simpan Detail
            $detail_saldo = [];
            $timestamp = Carbon::now();
            foreach ($data_saldo as $d) {
                if ($d->saldo_akhir != 0) {
                    $detail_saldo[] = [
                        'kode_saldo_awal' => $kode_saldo_awal,
                        'kode_salesman' => $d->kode_salesman,
                        'jumlah' => !empty($d->saldo_akhir) ? $d->saldo_akhir : 0,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp
                    ];
                }
            }

            if (!empty($detail_saldo)) {
                $chunks = array_chunk($detail_saldo, 100);
                foreach ($chunks as $chunk) {
                    Detailsaldoawalpiutangsalesman::insert($chunk);
                }
            }

            DB::commit();
            return redirect(route('sapiutangsales.index'))->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect(route('sapiutangsales.index'))->with(messageError($e->getMessage()));
        }
    }

    public function show($kode_saldo_awal)
    {
        $kode_saldo_awal = Crypt::decrypt($kode_saldo_awal);
        $saldo_awal = Saldoawalpiutangsalesman::where('kode_saldo_awal', $kode_saldo_awal)
            ->join('cabang', 'marketing_sa_piutangsales.kode_cabang', '=', 'cabang.kode_cabang')
            ->select('marketing_sa_piutangsales.*', 'nama_cabang')
            ->first();
        $detail = Detailsaldoawalpiutangsalesman::where('kode_saldo_awal', $kode_saldo_awal)
            ->join('salesman', 'marketing_sa_piutangsales_detail.kode_salesman', '=', 'salesman.kode_salesman')
            ->select('marketing_sa_piutangsales_detail.*', 'nama_salesman')
            ->get();
        $nama_bulan = config('global.nama_bulan');
        return view('marketing.sapiutangsales.show', compact('saldo_awal', 'nama_bulan', 'detail'));
    }

    public function destroy($kode_saldo_awal)
    {
        $kode_saldo_awal = Crypt::decrypt($kode_saldo_awal);
        $saldo_awal = Saldoawalpiutangsalesman::where('kode_saldo_awal', $kode_saldo_awal)->first();
        try {
            $cektutuplaporan = cektutupLaporan($saldo_awal->tanggal, "marketing");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            Saldoawalpiutangsalesman::where('kode_saldo_awal', $kode_saldo_awal)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function getdetailsaldo(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $kode_cabang = $request->kode_cabang;

        // Ambil bulan dan tahun lalu
        $bulan_lalu = getbulandantahunlalu($bulan, $tahun, "bulan");
        $tahun_lalu = getbulandantahunlalu($bulan, $tahun, "tahun");

        $dari = $tahun_lalu . "-" . ($bulan_lalu < 10 ? "0" . $bulan_lalu : $bulan_lalu) . "-01";
        $sampai = date('Y-m-t', strtotime($dari));

        // Logic from LaporanmarketingController::rekappenjualan_cetak
        // Adapted to calculate only necessary totals for balance

        $querydetail = Detailpenjualan::query();
        $querydetail->select(
            'marketing_penjualan.kode_salesman',
            'nama_salesman',
            'salesman.kode_cabang',
            DB::raw('SUM(0) as potongan'),
            DB::raw('SUM(0) as potongan_istimewa'),
            DB::raw('SUM(0) as ppn'),
            DB::raw('SUM(0) as penyesuaian'),
            DB::raw('SUM(subtotal) as bruto'),
            DB::raw('SUM(0) as retur'),
            DB::raw('SUM(0) as totalbayarpiutang'),
            DB::raw('SUM(0) as saldoawalpiutang'),
            DB::raw('SUM(0) as saldopiutangpindahan'),
            DB::raw('SUM(0) as saldopiutangpindahkesaleslain')
        );
        $querydetail->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $querydetail->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');
        $querydetail->whereBetween('marketing_penjualan.tanggal', [$dari, $sampai]);
        $querydetail->where('status_batal', 0);
        $querydetail->where('salesman.kode_cabang', $kode_cabang);
        $querydetail->groupBy('marketing_penjualan.kode_salesman', 'salesman.kode_cabang', 'nama_salesman');

        $querypenjualan = Penjualan::query();
        $querypenjualan->select(
            'marketing_penjualan.kode_salesman',
            'nama_salesman',
            'salesman.kode_cabang',
            DB::raw('SUM(potongan) as potongan'),
            DB::raw('SUM(potongan_istimewa) as potongan_istimewa'),
            DB::raw('SUM(ppn) as ppn'),
            DB::raw('SUM(penyesuaian) as penyesuaian'),
            DB::raw('SUM(0) as bruto'),
            DB::raw('SUM(0) as retur'),
            DB::raw('SUM(0) as totalbayarpiutang'),
            DB::raw('SUM(0) as saldoawalpiutang'),
            DB::raw('SUM(0) as saldopiutangpindahan'),
            DB::raw('SUM(0) as saldopiutangpindahkesaleslain')
        );
        $querypenjualan->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');
        $querypenjualan->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $querypenjualan->whereBetween('marketing_penjualan.tanggal', [$dari, $sampai]);
        $querypenjualan->where('status_batal', 0);
        $querypenjualan->where('salesman.kode_cabang', $kode_cabang);
        $querypenjualan->groupBy('marketing_penjualan.kode_salesman', 'salesman.kode_cabang', 'nama_salesman');

        $queryretur = Detailretur::query();
        $queryretur->select(
            'salesman.kode_salesman',
            'salesman.nama_salesman',
            'salesman.kode_cabang',
            DB::raw('SUM(0) as potongan'),
            DB::raw('SUM(0) as potongan_istimewa'),
            DB::raw('SUM(0) as ppn'),
            DB::raw('SUM(0) as penyesuaian'),
            DB::raw('SUM(0) as bruto'),
            DB::raw('SUM(subtotal) as retur'),
            DB::raw('SUM(0) as totalbayarpiutang'),
            DB::raw('SUM(0) as saldoawalpiutang'),
            DB::raw('SUM(0) as saldopiutangpindahan'),
            DB::raw('SUM(0) as saldopiutangpindahkesaleslain')
        );
        $queryretur->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur');
        $queryretur->leftJoin(
            DB::raw("(
                SELECT
                    marketing_penjualan.no_faktur,
                    IF( salesbaru IS NULL, marketing_penjualan.kode_salesman, salesbaru ) AS kode_salesman_baru,
                    IF( cabangbaru IS NULL, salesman.kode_cabang, cabangbaru ) AS kode_cabang_baru
                FROM
                    marketing_penjualan
                INNER JOIN salesman ON marketing_penjualan.kode_salesman = salesman.kode_salesman
                LEFT JOIN (
                SELECT
                    no_faktur,
                    marketing_penjualan_movefaktur.kode_salesman_baru AS salesbaru,
                    salesman.kode_cabang AS cabangbaru
                FROM
                    marketing_penjualan_movefaktur
                    INNER JOIN salesman ON marketing_penjualan_movefaktur.kode_salesman_baru = salesman.kode_salesman
                WHERE id IN (SELECT MAX(id) as id FROM marketing_penjualan_movefaktur WHERE tanggal <= '$dari'  GROUP BY no_faktur)
                ) movefaktur ON ( marketing_penjualan.no_faktur = movefaktur.no_faktur)
            ) pindahfaktur"),
            function ($join) {
                $join->on('marketing_retur.no_faktur', '=', 'pindahfaktur.no_faktur');
            }
        );
        $queryretur->join('salesman', 'pindahfaktur.kode_salesman_baru', '=', 'salesman.kode_salesman');
        $queryretur->whereBetween('marketing_retur.tanggal', [$dari, $sampai]);
        $queryretur->where('jenis_retur', 'PF');
        $queryretur->where('salesman.kode_cabang', $kode_cabang);
        $queryretur->groupBy('salesman.kode_salesman', 'salesman.kode_cabang', 'nama_salesman');

        $querysaldoawalpiutang = Detailsaldoawalpiutangsalesman::query();
        $querysaldoawalpiutang->select(
            'salesman.kode_salesman',
            'salesman.nama_salesman',
            'salesman.kode_cabang',
            DB::raw('SUM(0) as potongan'),
            DB::raw('SUM(0) as potongan_istimewa'),
            DB::raw('SUM(0) as ppn'),
            DB::raw('SUM(0) as penyesuaian'),
            DB::raw('SUM(0) as bruto'),
            DB::raw('SUM(0) as retur'),
            DB::raw('SUM(0) as totalbayarpiutang'),
            DB::raw('SUM(jumlah) as saldoawalpiutang'),
            DB::raw('SUM(0) as saldopiutangpindahan'),
            DB::raw('SUM(0) as saldopiutangpindahkesaleslain')
        );
        $querysaldoawalpiutang->join('salesman', 'marketing_sa_piutangsales_detail.kode_salesman', '=', 'salesman.kode_salesman');
        $querysaldoawalpiutang->join('marketing_sa_piutangsales', 'marketing_sa_piutangsales_detail.kode_saldo_awal', '=', 'marketing_sa_piutangsales.kode_saldo_awal');
        $querysaldoawalpiutang->where('bulan', $bulan_lalu);
        $querysaldoawalpiutang->where('tahun', $tahun_lalu);
        $querysaldoawalpiutang->where('salesman.kode_cabang', $kode_cabang);
        $querysaldoawalpiutang->groupBy('salesman.kode_salesman', 'salesman.kode_cabang', 'nama_salesman');

        $querybayarpiutang = Historibayarpenjualan::query();
        $querybayarpiutang->select(
            'salesman.kode_salesman',
            'salesman.nama_salesman',
            'salesman.kode_cabang',
            DB::raw('SUM(0) as potongan'),
            DB::raw('SUM(0) as potongan_istimewa'),
            DB::raw('SUM(0) as ppn'),
            DB::raw('SUM(0) as penyesuaian'),
            DB::raw('SUM(0) as bruto'),
            DB::raw('SUM(0) as retur'),
            DB::raw('SUM(jumlah) as totalbayarpiutang'),
            DB::raw('SUM(0) as saldoawalpiutang'),
            DB::raw('SUM(0) as saldopiutangpindahan'),
            DB::raw('SUM(0) as saldopiutangpindahkesaleslain')
        );
        $querybayarpiutang->leftJoin(
            DB::raw("(
                  SELECT
                    marketing_penjualan.no_faktur,
                    IF( salesbaru IS NULL, marketing_penjualan.kode_salesman, salesbaru ) AS kode_salesman_baru,
                    IF( cabangbaru IS NULL, salesman.kode_cabang, cabangbaru ) AS kode_cabang_baru
                FROM
                    marketing_penjualan
                INNER JOIN salesman ON marketing_penjualan.kode_salesman = salesman.kode_salesman
                LEFT JOIN (
                SELECT
                    no_faktur,
                    marketing_penjualan_movefaktur.kode_salesman_baru AS salesbaru,
                    salesman.kode_cabang AS cabangbaru
                FROM
                    marketing_penjualan_movefaktur
                    INNER JOIN salesman ON marketing_penjualan_movefaktur.kode_salesman_baru = salesman.kode_salesman
                WHERE id IN (SELECT MAX(id) as id FROM marketing_penjualan_movefaktur WHERE tanggal <= '$dari' GROUP BY no_faktur)
                ) movefaktur ON ( marketing_penjualan.no_faktur = movefaktur.no_faktur)
            ) pindahfaktur"),
            function ($join) {
                $join->on('marketing_penjualan_historibayar.no_faktur', '=', 'pindahfaktur.no_faktur');
            }
        );
        $querybayarpiutang->join('salesman', 'pindahfaktur.kode_salesman_baru', '=', 'salesman.kode_salesman');
        $querybayarpiutang->whereBetween('marketing_penjualan_historibayar.tanggal', [$dari, $sampai]);
        $querybayarpiutang->where('salesman.kode_cabang', $kode_cabang);
        $querybayarpiutang->groupBy('salesman.kode_salesman', 'salesman.kode_cabang', 'nama_salesman');

        $querypiutangpindahan = Movefaktur::query();
        $querypiutangpindahan->select(
            'salesman.kode_salesman',
            'salesman.nama_salesman',
            'salesman.kode_cabang',
            DB::raw('SUM(0) as potongan'),
            DB::raw('SUM(0) as potongan_istimewa'),
            DB::raw('SUM(0) as ppn'),
            DB::raw('SUM(0) as penyesuaian'),
            DB::raw('SUM(0) as bruto'),
            DB::raw('SUM(0) as retur'),
            DB::raw('SUM(0) as totalbayarpiutang'),
            DB::raw('SUM(0) as saldoawalpiutang'),
            DB::raw('SUM(saldopiutangpindahan) as saldopiutangpindahan'),
            DB::raw('SUM(0) as saldopiutangpindahkesaleslain')
        );
        $querypiutangpindahan->leftJoin(
            DB::raw("(
                    SELECT marketing_saldoawal_piutang_detail.no_faktur,
                    jumlah as saldopiutangpindahan
                    FROM
                    marketing_saldoawal_piutang_detail
                    INNER JOIN marketing_saldoawal_piutang ON marketing_saldoawal_piutang_detail.kode_saldo_awal = marketing_saldoawal_piutang.kode_saldo_awal
                    WHERE bulan = '$bulan_lalu' AND tahun = '$tahun_lalu'
            ) saldoawalfaktur"),
            function ($join) {
                $join->on('marketing_penjualan_movefaktur.no_faktur', '=', 'saldoawalfaktur.no_faktur');
            }
        );
        $querypiutangpindahan->join('salesman', 'marketing_penjualan_movefaktur.kode_salesman_baru', '=', 'salesman.kode_salesman');
        $querypiutangpindahan->where('marketing_penjualan_movefaktur.tanggal', $dari);
        $querypiutangpindahan->where('salesman.kode_cabang', $kode_cabang);
        $querypiutangpindahan->groupBy('salesman.kode_salesman', 'salesman.kode_cabang', 'nama_salesman');

        $querypiutangpindahankesaleslain = Movefaktur::query();
        $querypiutangpindahankesaleslain->select(
            'salesman.kode_salesman',
            'salesman.nama_salesman',
            'salesman.kode_cabang',
            DB::raw('SUM(0) as potongan'),
            DB::raw('SUM(0) as potongan_istimewa'),
            DB::raw('SUM(0) as ppn'),
            DB::raw('SUM(0) as penyesuaian'),
            DB::raw('SUM(0) as bruto'),
            DB::raw('SUM(0) as retur'),
            DB::raw('SUM(0) as totalbayarpiutang'),
            DB::raw('SUM(0) as saldoawalpiutang'),
            DB::raw('SUM(0) as saldopiutangpindahan'),
            DB::raw('SUM(saldopiutangpindahankesaleslain) as saldopiutangpindahkesaleslain')
        );
        $querypiutangpindahankesaleslain->leftJoin(
            DB::raw("(
                    SELECT marketing_saldoawal_piutang_detail.no_faktur,
                    jumlah as saldopiutangpindahankesaleslain
                    FROM
                    marketing_saldoawal_piutang_detail
                    INNER JOIN marketing_saldoawal_piutang ON marketing_saldoawal_piutang_detail.kode_saldo_awal = marketing_saldoawal_piutang.kode_saldo_awal
                    WHERE bulan = '$bulan_lalu' AND tahun = '$tahun_lalu'
            ) saldoawalfaktur"),
            function ($join) {
                $join->on('marketing_penjualan_movefaktur.no_faktur', '=', 'saldoawalfaktur.no_faktur');
            }
        );
        $querypiutangpindahankesaleslain->join('salesman', 'marketing_penjualan_movefaktur.kode_salesman_lama', '=', 'salesman.kode_salesman');
        $querypiutangpindahankesaleslain->where('marketing_penjualan_movefaktur.tanggal', $dari);
        $querypiutangpindahankesaleslain->where('salesman.kode_cabang', $kode_cabang);
        $querypiutangpindahankesaleslain->groupBy('salesman.kode_salesman', 'salesman.kode_cabang', 'nama_salesman');

        $query_rekappenjualan = $querydetail->unionAll($querypenjualan)->unionAll($queryretur)
            ->unionAll($querysaldoawalpiutang)
            ->unionAll($querybayarpiutang)
            ->unionAll($querypiutangpindahan)
            ->unionAll($querypiutangpindahankesaleslain)
            ->get();

        $rekappenjualan = $query_rekappenjualan->groupBy('kode_salesman', 'kode_cabang', 'nama_salesman')
            ->map(function ($item) {
                $saldo_awal_piutang = $item->sum('saldoawalpiutang') + $item->sum('saldopiutangpindahan') - $item->sum('saldopiutangpindahkesaleslain');
                $netto = $item->sum('bruto') - $item->sum('potongan') - $item->sum('penyesuaian') - $item->sum('potongan_istimewa') + $item->sum('ppn') - $item->sum('retur');
                $saldo_akhir_piutang = $saldo_awal_piutang + $netto - $item->sum('totalbayarpiutang');
                
                return [
                    'kode_salesman' => $item->first()->kode_salesman,
                    'nama_salesman' => $item->first()->nama_salesman,
                    'saldo_akhir' => $saldo_akhir_piutang
                ];
            })
            ->sortBy('kode_salesman')
            ->values()
            ->all();

        return view('marketing.sapiutangsales.getdetailsaldo', compact('rekappenjualan'));
    }
}
