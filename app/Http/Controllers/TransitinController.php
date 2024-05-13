<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailmutasigudangcabang;
use App\Models\Detailmutasigudangjadi;
use App\Models\Mutasigudangcabang;
use App\Models\Mutasigudangjadi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class TransitinController extends Controller
{
    public function index(Request $request)
    {

        $start_date = config('global.start_date');
        $end_date = config('global.end_date');
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);
        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }


        $query = Mutasigudangcabang::query();
        $query->select(
            'gudang_cabang_mutasi.no_mutasi',
            'gudang_cabang_mutasi.no_surat_jalan',
            'gudang_cabang_mutasi.kode_cabang',
            'nama_cabang',
            'gudang_cabang_mutasi.tanggal as tgl_transit_out',
            'tgl_transit_in'
        );
        $query->join('cabang', 'gudang_cabang_mutasi.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftJoin(
            DB::raw("(
            SELECT
            no_surat_jalan,
            tanggal as tgl_transit_in
            FROM
            gudang_cabang_mutasi
            WHERE
            jenis_mutasi = 'TI'
        ) transit_in"),
            function ($join) {
                $join->on('gudang_cabang_mutasi.no_surat_jalan', '=', 'transit_in.no_surat_jalan');
            }
        );
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('gudang_cabang_mutasi.tanggal', [$request->dari, $request->sampai]);
        } else {
            $query->whereBetween('gudang_cabang_mutasi.tanggal', [$start_date, $end_date]);
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('gudang_cabang_mutasi.kode_cabang', $request->kode_cabang_search);
        }

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('gudang_cabang_mutasi.kode_cabang', auth()->user()->kode_cabang);
            }
        }
        $query->where('jenis_mutasi', 'TO');
        $query->orderBy('gudang_cabang_mutasi.tanggal', 'desc');
        $query->orderBy('tgl_transit_in');
        $transit_in = $query->paginate(10);
        $transit_in->appends(request()->all());
        $data['transit_in'] = $transit_in;

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;


        return view('gudangcabang.transitin.index', $data);
    }

    public function create($no_surat_jalan)
    {
        $no_surat_jalan = Crypt::decrypt($no_surat_jalan);
        $data['surat_jalan'] = Mutasigudangjadi::where('no_mutasi', $no_surat_jalan)
            ->select(
                'gudang_jadi_mutasi.no_mutasi',
                'gudang_jadi_mutasi.tanggal',
                'gudang_jadi_mutasi.no_permintaan',
                'marketing_permintaan_kiriman.tanggal as tanggal_permintaan',
                'cabang.nama_cabang',
                'marketing_permintaan_kiriman.keterangan',
                'gudang_jadi_mutasi.status_surat_jalan',
                'no_dok'
            )
            ->join('marketing_permintaan_kiriman', 'gudang_jadi_mutasi.no_permintaan', '=', 'marketing_permintaan_kiriman.no_permintaan')
            ->join('cabang', 'marketing_permintaan_kiriman.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();

        //Detail Surat Jalan
        $data['detail'] = Detailmutasigudangjadi::where('no_mutasi', $no_surat_jalan)
            ->join('produk', 'gudang_jadi_mutasi_detail.kode_produk', '=', 'produk.kode_produk')
            ->get();
        return view('gudangcabang.transitin.create', $data);
    }


    public function store($no_surat_jalan, Request $request)
    {
        $no_surat_jalan = Crypt::decrypt($no_surat_jalan);
        $request->validate([
            'tanggal' => 'required'
        ]);


        DB::beginTransaction();
        try {

            $cektutuplaporan = cektutupLaporan($request->tanggal, "gudangcabang");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            $tahun = date('Y', strtotime($request->tanggal));
            $transit_out = Mutasigudangcabang::where('no_surat_jalan', $no_surat_jalan)
                ->select('no_mutasi', 'kode_cabang', 'tanggal')
                ->where('jenis_mutasi', 'TO')
                ->first();
            $last_transit_in = Mutasigudangcabang::select('no_mutasi as no_transit_in')
                ->where('kode_cabang', $transit_out->kode_cabang)
                ->where('jenis_mutasi', 'TI')
                ->whereRaw('YEAR(tanggal)="' .  $tahun . '"')
                ->orderBy('no_mutasi', 'desc')
                ->first();
            $last_no_transit_in = $last_transit_in != null ? $last_transit_in->no_transit_in : '';
            $no_transit_in = buatkode($last_no_transit_in, 'TN' . $transit_out->kode_cabang . substr($tahun, 2, 2), 2);
            $detail = Detailmutasigudangcabang::where('no_mutasi', $transit_out->no_mutasi)->get();

            Mutasigudangcabang::create([
                'no_mutasi' => $no_transit_in,
                'tanggal' => $request->tanggal,
                'tanggal_kirim' => $transit_out->tanggal,
                'no_surat_jalan' => $no_surat_jalan,
                'kode_cabang' => $transit_out->kode_cabang,
                'kondisi' => 'G',
                'in_out_good' => 'I',
                'jenis_mutasi' => 'TI',
                'id_user' => auth()->user()->id
            ]);
            foreach ($detail as $d) {
                $data_detail[] = [
                    'no_mutasi'   => $no_transit_in,
                    'kode_produk'  => $d->kode_produk,
                    'jumlah' => $d->jumlah
                ];
            }

            Detailmutasigudangcabang::insert($data_detail);
            Mutasigudangjadi::where('no_mutasi', $no_surat_jalan)->update(['status_surat_jalan' => 1]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function destroy($no_surat_jalan)
    {
        $no_surat_jalan = Crypt::decrypt($no_surat_jalan);
        $sj = Mutasigudangcabang::where('no_surat_jalan', $no_surat_jalan)->where('jenis_mutasi', 'TI')->first();
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($sj->tanggal, "gudangcabang");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            Mutasigudangcabang::where('no_surat_jalan', $no_surat_jalan)->where('jenis_mutasi', 'TI')->delete();
            Mutasigudangjadi::where('no_mutasi', $no_surat_jalan)->update(['status_surat_jalan' => 2]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus !'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
