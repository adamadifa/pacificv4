<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailbadstokga;
use App\Models\Detailservicekendaraan;
use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporangeneralaffairController extends Controller
{
    public function index()
    {
        $data['kendaraan'] = Kendaraan::where('status_aktif_kendaraan', '1')
            ->where('no_polisi', '!=', 'ZL')
            ->orderBy('no_polisi', 'asc')
            ->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('generalaffair.laporan.index', $data);
    }

    public function cetakservicekendaraan(Request $request)
    {
        $query = Detailservicekendaraan::query();
        $query->select(
            'ga_kendaraan_service_detail.*',
            'ga_kendaraan_service.no_invoice',
            'tanggal',
            'ga_kendaraan_service.kode_kendaraan',
            'merek',
            'tipe',
            'tipe_kendaraan',
            'no_polisi',
            'ga_kendaraan_service.kode_bengkel',
            'nama_bengkel',
            'ga_kendaraan_service.kode_cabang',
            'nama_item',
            'jumlah',
            'harga',
            'total'
        );

        $query->join('ga_kendaraan_service', 'ga_kendaraan_service_detail.kode_service', '=', 'ga_kendaraan_service.kode_service');
        $query->join('kendaraan', 'ga_kendaraan_service.kode_kendaraan', '=', 'kendaraan.kode_kendaraan');
        $query->join('ga_bengkel', 'ga_kendaraan_service.kode_bengkel', '=', 'ga_bengkel.kode_bengkel');
        $query->join('ga_kendaraan_service_item', 'ga_kendaraan_service_detail.kode_item', '=', 'ga_kendaraan_service_item.kode_item');
        $query->leftJoin(
            DB::raw("(
            SELECT kode_service, SUM(jumlah*harga) as total FROM ga_kendaraan_service_detail GROUP BY kode_service
            ) detailservice"),
            function ($join) {
                $join->on('ga_kendaraan_service_detail.kode_service', '=', 'detailservice.kode_service');
            }
        );

        $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
        $query->orderBy('tanggal');
        if (!empty($request->kode_kendaraan)) {
            $query->where('ga_kendaraan_service.kode_kendaraan', $request->kode_kendaraan);
        }
        $data['service'] = $query->get();
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['kendaraan'] = Kendaraan::where('kode_kendaraan', $request->kode_kendaraan)->first();

        return view('generalaffair.laporan.servicekendaraan_cetak', $data);
    }

    public function cetakrekapbadstok(Request $request)
    {
        $dari = $request->tahun . "-" . $request->bulan . "-01";
        $sampai = date('Y-m-t', strtotime($dari));
        $start_date = $dari;
        $selectQty = [];
        $rangeTanggal = [];
        $rangeBulan = [];
        if ($request->formatlaporan == '1') {
            //looping Dari Sampai menggunakan while
            $i = 1;
            while (strtotime($start_date) <= strtotime($sampai)) {
                $rangeTanggal[] = $start_date;
                $selectQty[] = DB::raw("SUM(IF(tanggal = '" . $start_date . "', jumlah, 0)) as tanggal_" . $i);
                $i++;
                $start_date = date('Y-m-d', strtotime("+1 day", strtotime($start_date)));
            }
        } else {
            $b = 1;
            for ($b = 1; $b <= 12; $b++) {
                $rangeBulan[] = $b;
                $selectQty[] = DB::raw("SUM(IF(MONTH(tanggal) = '" . $b . "', jumlah, 0)) as bulan_" . $b);
            }
        }

        $query = Detailbadstokga::query();
        $query->select(
            'ga_badstok_detail.kode_produk',
            'nama_produk',
            ...$selectQty
        );

        $query->join('ga_badstok', 'ga_badstok_detail.kode_bs', '=', 'ga_badstok.kode_bs');
        $query->join('produk', 'ga_badstok_detail.kode_produk', '=', 'produk.kode_produk');
        if ($request->formatlaporan == '1') {
            $query->whereBetween('ga_badstok.tanggal', [$dari, $sampai]);
        } else {
            $query->whereRaw("YEAR(tanggal) = '" . $request->tahun . "'");
        }

        if (!empty($request->kode_asal_bs)) {
            $query->where('ga_badstok.kode_asal_bs', $request->kode_asal_bs);
        }
        $query->groupBy('ga_badstok_detail.kode_produk', 'nama_produk');

        $data['rekapbadstok'] = $query->get();


        if ($request->formatlaporan == '1') {
            $data['dari'] = $dari;
            $data['sampai'] = $sampai;
            $data['rangeTanggal'] = $rangeTanggal;
            $data['bulan'] = $request->bulan;
            $data['tahun'] = $request->tahun;
            return view('generalaffair.laporan.rekapbadstok_cetak', $data);
        } else {
            $data['rangeBulan'] = $rangeBulan;
            $data['bulan'] = $request->bulan;
            $data['tahun'] = $request->tahun;
            return view('generalaffair.laporan.rekapbadstok_pertahun_cetak', $data);
        }
    }
}
