<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailmutasigudangcabang;
use App\Models\Jenismutasigudangcabang;
use App\Models\Mutasigudangcabang;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class RejectController extends Controller
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
            'gudang_cabang_mutasi.tanggal',
            'gudang_cabang_mutasi.kode_cabang',
            'nama_cabang',
            'keterangan',
            'gudang_cabang_jenis_mutasi.jenis_mutasi'
        );
        $query->join('cabang', 'gudang_cabang_mutasi.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('gudang_cabang_jenis_mutasi', 'gudang_cabang_mutasi.jenis_mutasi', '=', 'gudang_cabang_jenis_mutasi.kode_jenis_mutasi');
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

        if (!empty($request->jenis_mutasi_search)) {
            $query->where('gudang_cabang_mutasi.jenis_mutasi', $request->jenis_mutasi_search);
        }
        $query->whereIN('gudang_cabang_mutasi.jenis_mutasi', ['RG', 'RM', 'RP']);
        $query->orderBy('gudang_cabang_mutasi.tanggal', 'desc');
        $query->orderBy('gudang_cabang_mutasi.created_at', 'desc');
        $reject = $query->paginate(10);
        $reject->appends(request()->all());
        $data['reject'] = $reject;

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;


        return view('gudangcabang.reject.index', $data);
    }

    public function create()
    {
        $data['jenis_mutasi'] = Jenismutasigudangcabang::whereIn('kode_jenis_mutasi', ['RG', 'RM', 'RP'])->orderBy('order')->get();
        $data['produk'] = Produk::orderBy('kode_produk')->where('status_aktif_produk', 1)->get();
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('gudangcabang.reject.create', $data);
    }

    public function store(Request $request)
    {
        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');
        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang;
            $request->validate([
                'tanggal' => 'required',
                'kode_cabang' => 'required',
                'jenis_mutasi' => 'required'
            ]);
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
            $request->validate([
                'tanggal' => 'required',
                'jenis_mutasi' => 'required'
            ]);
        }
        $kode_produk = $request->kode_produk;
        $jml_dus = $request->jml_dus;
        $jml_pack = $request->jml_pack;
        $jml_pcs = $request->jml_pcs;
        $isi_pcs_dus = $request->isi_pcs_dus;
        $isi_pcs_pack = $request->isi_pcs_pack;


        DB::beginTransaction();
        try {



            $cektutuplaporan = cektutupLaporan($request->tanggal, "gudangcabang");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            if ($request->jenis_mutasi == "RG") {
                $kode = "RJG";
            } else if ($request->jenis_mutasi == "RP") {
                $kode = "RJP";
            } else if ($request->jenis_mutasi == "RM") {
                $kode = "RJM";
            }

            $tahun = date('Y', strtotime($request->tanggal));
            $lastreject = Mutasigudangcabang::select('no_mutasi')
                ->where('jenis_mutasi', $request->jenis_mutasi)
                ->whereRaw('YEAR(tanggal)="' . $tahun . '"')
                ->whereRaw('LENGTH(no_mutasi)="10"')
                ->orderBy('no_mutasi', 'desc')
                ->first();
            $last_no_reject = $lastreject != null ? $lastreject->no_mutasi : '';
            $no_reject = buatkode($last_no_reject, $kode . substr($tahun, 2, 2), 5);
            $detail = [];
            for ($i = 0; $i < count($kode_produk); $i++) {
                $dus = toNumber(!empty($jml_dus[$i]) ? $jml_dus[$i] : 0);
                $pack = toNumber(!empty($jml_pack[$i]) ? $jml_pack[$i] : 0);
                $pcs = toNumber(!empty($jml_pcs[$i]) ? $jml_pcs[$i] : 0);
                $jumlah = ($dus * $isi_pcs_dus[$i]) + ($pack * $isi_pcs_pack[$i]) + $pcs;
                if (!empty($jumlah)) {
                    $detail[]   = [
                        'no_mutasi' => $no_reject,
                        'kode_produk' => $kode_produk[$i],
                        'jumlah' => $jumlah
                    ];
                }
            }

            if (empty($detail)) {
                return Redirect::back()->with(messageError('Data Produk Masih Kosong'));
            }

            Mutasigudangcabang::create([
                'no_mutasi'  => $no_reject,
                'tanggal' => $request->tanggal,
                'kode_cabang' => $kode_cabang,
                'kondisi' => 'B',
                'in_out_good' => 'O',
                'in_out_bad' => 'I',
                'jenis_mutasi' => $request->jenis_mutasi,
                'keterangan' => $request->keterangan,
                'id_user' => auth()->user()->id
            ]);

            Detailmutasigudangcabang::insert($detail);
            DB::commit();

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($no_mutasi)
    {
        $no_mutasi = Crypt::decrypt($no_mutasi);
        $data['jenis_mutasi'] = Jenismutasigudangcabang::whereIn('kode_jenis_mutasi', ['RG', 'RM', 'RP'])->orderBy('order')->get();
        $data['produk'] = Produk::orderBy('kode_produk')
            ->select('produk.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack', 'jumlah')
            ->leftJoin(
                DB::raw("(
            SELECT
            kode_produk,jumlah
            FROM
            gudang_cabang_mutasi_detail
            WHERE no_mutasi = '$no_mutasi'
        ) mutasi"),
                function ($join) {
                    $join->on('produk.kode_produk', '=', 'mutasi.kode_produk');
                }
            )
            ->where('status_aktif_produk', 1)->get();
        $data['reject'] = Mutasigudangcabang::select(
            'no_mutasi',
            'tanggal',
            'gudang_cabang_mutasi.jenis_mutasi',
            'keterangan',
            'gudang_cabang_mutasi.kode_cabang'
        )
            ->where('no_mutasi', $no_mutasi)
            ->first();
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('gudangcabang.reject.edit', $data);
    }


    public function update($no_mutasi, Request $request)
    {

        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');
        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang;
            $request->validate([
                'tanggal' => 'required',
                'kode_cabang' => 'required',
                'jenis_mutasi' => 'required'
            ]);
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
            $request->validate([
                'tanggal' => 'required',
                'jenis_mutasi' => 'required'
            ]);
        }
        $no_mutasi = Crypt::decrypt($request->no_mutasi);
        $kode_produk = $request->kode_produk;
        $jml_dus = $request->jml_dus;
        $jml_pack = $request->jml_pack;
        $jml_pcs = $request->jml_pcs;
        $isi_pcs_dus = $request->isi_pcs_dus;
        $isi_pcs_pack = $request->isi_pcs_pack;

        DB::beginTransaction();
        try {

            $reject = Mutasigudangcabang::where('no_mutasi', $no_mutasi)->first();

            //Checking
            $cektutuplaporan_reject = cektutupLaporan($reject->tanggal, "gudangcabang");
            if ($cektutuplaporan_reject > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }


            $cektutuplaporan = cektutupLaporan($request->tanggal, "gudangcabang");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            if ($request->jenis_mutasi == "RG") {
                $kode = "RJG";
            } else if ($request->jenis_mutasi == "RP") {
                $kode = "RJP";
            } else if ($request->jenis_mutasi == "RM") {
                $kode = "RJM";
            }

            $tahun = date('Y', strtotime($request->tanggal));


            if ($request->jenis_mutasi != $reject->jenis_mutasi) {
                $lastreject = Mutasigudangcabang::select('no_mutasi')
                    ->where('jenis_mutasi', $request->jenis_mutasi)
                    ->whereRaw('YEAR(tanggal)="' . $tahun . '"')
                    ->whereRaw('LENGTH(no_mutasi)="10"')
                    ->orderBy('no_mutasi', 'desc')
                    ->first();
                $last_no_reject = $lastreject != null ? $lastreject->no_mutasi : '';
                $no_reject = buatkode($last_no_reject, $kode . substr($tahun, 2, 2), 5);
            } else {
                $no_reject = $reject->no_mutasi;
            }


            $detail = [];
            for ($i = 0; $i < count($kode_produk); $i++) {
                $dus = toNumber(!empty($jml_dus[$i]) ? $jml_dus[$i] : 0);
                $pack = toNumber(!empty($jml_pack[$i]) ? $jml_pack[$i] : 0);
                $pcs = toNumber(!empty($jml_pcs[$i]) ? $jml_pcs[$i] : 0);

                $jumlah = ($dus * $isi_pcs_dus[$i]) + ($pack * $isi_pcs_pack[$i]) + $pcs;
                if (!empty($jumlah)) {
                    $detail[]   = [
                        'no_mutasi' => $no_reject,
                        'kode_produk' => $kode_produk[$i],
                        'jumlah' => $jumlah
                    ];
                }
            }


            if (empty($detail)) {
                return Redirect::back()->with(messageError('Data Produk Masih Kosong'));
            }
            Detailmutasigudangcabang::where('no_mutasi', $no_mutasi)->delete();

            Mutasigudangcabang::where('no_mutasi', $no_mutasi)->update([
                'no_mutasi'  => $no_reject,
                'tanggal' => $request->tanggal,
                'kode_cabang' => $kode_cabang,
                'jenis_mutasi' => $request->jenis_mutasi,
                'keterangan' => $request->keterangan,
            ]);

            Detailmutasigudangcabang::insert($detail);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            //dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
    public function show($no_mutasi)
    {
        $no_mutasi = Crypt::decrypt($no_mutasi);
        $data['mutasi'] = Mutasigudangcabang::select('no_mutasi', 'tanggal', 'gudang_cabang_jenis_mutasi.jenis_mutasi', 'keterangan', 'nama_cabang')
            ->join('gudang_cabang_jenis_mutasi', 'gudang_cabang_mutasi.jenis_mutasi', '=', 'gudang_cabang_jenis_mutasi.kode_jenis_mutasi')
            ->join('cabang', 'gudang_cabang_mutasi.kode_cabang', '=', 'cabang.kode_cabang')
            ->where('no_mutasi', $no_mutasi)
            ->first();
        $data['detail'] = Detailmutasigudangcabang::select('gudang_cabang_mutasi_detail.kode_produk', 'nama_produk', 'jumlah', 'isi_pcs_dus', 'isi_pcs_pack')
            ->join('produk', 'gudang_cabang_mutasi_detail.kode_produk', '=', 'produk.kode_produk')
            ->where('no_mutasi', $no_mutasi)
            ->get();
        return view('gudangcabang.reject.show', $data);
    }

    public function destroy($no_mutasi)
    {
        $no_mutasi = Crypt::decrypt($no_mutasi);
        $reject = Mutasigudangcabang::where('no_mutasi', $no_mutasi)->first();
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($reject->tanggal, "gudangcabang");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            //Hapus Surat Jalan
            Mutasigudangcabang::where('no_mutasi', $no_mutasi)->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
