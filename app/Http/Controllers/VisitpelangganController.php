<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Penjualan;
use App\Models\User;
use App\Models\Visitpelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class VisitpelangganController extends Controller
{

    public function index(Request $request)
    {

        $start_date = config('global.start_date');
        $end_date = config('global.end_date');
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }

        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();


        $query = Visitpelanggan::query();
        $query->select(
            'worksheetom_visitpelanggan.*',
            'nama_pelanggan',
            'nama_salesman',
            'salesman.kode_cabang',
            'marketing_penjualan.tanggal as tanggal_faktur',
            DB::raw('(SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) - potongan - potongan_istimewa - penyesuaian + ppn as total_netto'),
        );
        $query->join('marketing_penjualan', 'worksheetom_visitpelanggan.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $query->leftJoin(
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
                WHERE id IN (SELECT MAX(id) as id FROM marketing_penjualan_movefaktur GROUP BY no_faktur)
                ) movefaktur ON ( marketing_penjualan.no_faktur = movefaktur.no_faktur)
            ) pindahfaktur"),
            function ($join) {
                $join->on('marketing_penjualan.no_faktur', '=', 'pindahfaktur.no_faktur');
            }
        );
        $query->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $query->join('salesman', 'pindahfaktur.kode_salesman_baru', '=', 'salesman.kode_salesman');
        $query->orderBy('worksheetom_visitpelanggan.tanggal', 'desc');
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('kode_cabang_baru', auth()->user()->kode_cabang);
            }
        }

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('marketing_penjualan.tanggal', [$request->dari, $request->sampai]);
        } else {
            $query->whereBetween('marketing_penjualan.tanggal', [$start_date, $end_date]);
        }

        if (!empty($request->no_faktur_search)) {
            $query->where('marketing_penjualan.no_faktur', $request->no_faktur_search);
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('kode_cabang_baru', $request->kode_cabang_search);
        }

        if (!empty($request->kode_salesman_search)) {
            $query->where('kode_salesman_baru', $request->kode_salesman_search);
        }

        if (!empty($request->kode_pelanggan_search)) {
            $query->where('marketing_penjualan.kode_pelanggan', $request->kode_pelanggan_search);
        }


        if (!empty($request->nama_pelanggan_search)) {
            $query->where('nama_pelanggan', 'like', '%' . $request->nama_pelanggan_search . '%');
        }
        $visit = $query->paginate(15);
        $visit->appends($request->all());
        $data['visit'] = $visit;

        return view('worksheetom.visitpelanggan.index', $data);
    }
    public function create($no_faktur)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        $penjualan = new Penjualan();
        $data['faktur'] = $penjualan->getFaktur($no_faktur);
        return view('worksheetom.visitpelanggan.create', $data);
    }

    public function store(Request $request, $no_faktur)
    {

        $no_faktur = Crypt::decrypt($no_faktur);
        $penjualan = new Penjualan();
        $faktur = $penjualan->getFaktur($no_faktur);


        $request->validate([
            'tanggal' => 'required',
            'hasil_konfirmasi' => 'required',
            'note' => 'required',
            'saran' => 'required',
            'act_om' => 'required',
        ]);

        $lastvisit = Visitpelanggan::select('kode_visit')
            ->join('marketing_penjualan', 'worksheetom_visitpelanggan.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->where('salesman.kode_cabang', $faktur->kode_cabang)
            ->whereRaw('YEAR(worksheetom_visitpelanggan.tanggal) = "' . date('Y', strtotime($request->tanggal)) . '"')
            ->orderBy('kode_visit', 'desc')->first();

        $last_kode_visit = $lastvisit != null ? $lastvisit->kode_visit : '';
        $kode_visit = buatkode($last_kode_visit, 'VST' . $faktur->kode_cabang . substr(date('Y', strtotime($request->tanggal)), 2, 2), 5);
        try {
            Visitpelanggan::create([
                'kode_visit' => $kode_visit,
                'no_faktur' => $no_faktur,
                'tanggal' => $request->tanggal,
                'hasil_konfirmasi' => $request->hasil_konfirmasi,
                'note' => $request->note,
                'saran' => $request->saran,
                'act_om' => $request->act_om,
            ]);
            return Redirect::back()->with(messageSuccess('Visit pelanggan berhasil ditambahkan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($kode_visit)
    {
        $kode_visit = Crypt::decrypt($kode_visit);
        $visit = Visitpelanggan::where('kode_visit', $kode_visit)->first();
        $penjualan = new Penjualan();
        $data['faktur'] = $penjualan->getFaktur($visit->no_faktur);
        $data['visit'] = $visit;
        return view('worksheetom.visitpelanggan.edit', $data);
    }


    public function update(Request $request, $kode_visit)
    {
        $kode_visit = Crypt::decrypt($kode_visit);
        $request->validate([
            'tanggal' => 'required',
            'hasil_konfirmasi' => 'required',
            'note' => 'required',
            'saran' => 'required',
            'act_om' => 'required',
        ]);
        try {
            Visitpelanggan::where('kode_visit', $kode_visit)->update([
                'tanggal' => $request->tanggal,
                'hasil_konfirmasi' => $request->hasil_konfirmasi,
                'note' => $request->note,
                'saran' => $request->saran,
                'act_om' => $request->act_om,
            ]);
            return Redirect::back()->with(messageSuccess('Visit pelanggan berhasil diupdate'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function show($kode_visit)
    {
        $kode_visit = Crypt::decrypt($kode_visit);
        $visit = Visitpelanggan::where('kode_visit', $kode_visit)->first();
        $penjualan = new Penjualan();
        $data['faktur'] = $penjualan->getFaktur($visit->no_faktur);
        $data['visit'] = $visit;
        return view('worksheetom.visitpelanggan.show', $data);
    }

    public function destroy($kode_visit)
    {
        $kode_visit = Crypt::decrypt($kode_visit);
        try {
            Visitpelanggan::where('kode_visit', $kode_visit)->delete();
            return Redirect::back()->with(messageSuccess('Visit pelanggan berhasil dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function cetak(Request $request)
    {

        $start_date = config('global.start_date');
        $end_date = config('global.end_date');
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang;
        }

        $query = Visitpelanggan::query();
        $query->select(
            'worksheetom_visitpelanggan.*',
            'marketing_penjualan.kode_pelanggan',
            'nama_pelanggan',
            'alamat_pelanggan',
            'nama_salesman',
            'marketing_penjualan.tanggal as tanggal_faktur',
            DB::raw('(SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) - potongan - potongan_istimewa - penyesuaian + ppn as total_netto'),
        );
        $query->join('marketing_penjualan', 'worksheetom_visitpelanggan.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $query->leftJoin(
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
                WHERE id IN (SELECT MAX(id) as id FROM marketing_penjualan_movefaktur GROUP BY no_faktur)
                ) movefaktur ON ( marketing_penjualan.no_faktur = movefaktur.no_faktur)
            ) pindahfaktur"),
            function ($join) {
                $join->on('marketing_penjualan.no_faktur', '=', 'pindahfaktur.no_faktur');
            }
        );
        $query->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $query->join('salesman', 'pindahfaktur.kode_salesman_baru', '=', 'salesman.kode_salesman');
        $query->orderBy('worksheetom_visitpelanggan.tanggal', 'desc');
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('kode_cabang_baru', auth()->user()->kode_cabang);
            }
        }

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('marketing_penjualan.tanggal', [$request->dari, $request->sampai]);
        } else {
            $query->whereBetween('marketing_penjualan.tanggal', [$start_date, $end_date]);
        }

        if (!empty($request->no_faktur)) {
            $query->where('marketing_penjualan.no_faktur', $request->no_faktur);
        }

        if (!empty($request->kode_cabang)) {
            $query->where('kode_cabang_baru', $request->kode_cabang);
        }

        if (!empty($request->kode_salesman)) {
            $query->where('kode_salesman_baru', $request->kode_salesman);
        }

        if (!empty($request->kode_pelanggan)) {
            $query->where('marketing_penjualan.kode_pelanggan', $request->kode_pelanggan);
        }


        if (!empty($request->nama_pelanggan)) {
            $query->where('nama_pelanggan', 'like', '%' . $request->nama_pelanggan . '%');
        }
        $visit = $query->get();
        $data['visit'] = $visit;

        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;

        if (isset($_GET['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Data Visit Pelanggan $request->dari-$request->sampai.xls");
        }
        return view('worksheetom.visitpelanggan.cetak', $data);
    }
}
