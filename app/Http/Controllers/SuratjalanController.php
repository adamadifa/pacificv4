<?php

namespace App\Http\Controllers;

use App\Models\Angkutan;
use App\Models\Cabang;
use App\Models\Detailmutasigudangcabang;
use App\Models\Detailmutasigudangjadi;
use App\Models\Detailpermintaankiriman;
use App\Models\Mutasigudangcabang;
use App\Models\Mutasigudangjadi;
use App\Models\Permintaankiriman;
use App\Models\Produk;
use App\Models\Suratjalanangkutan;
use App\Models\Tujuanangkutan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class SuratjalanController extends Controller
{


    public function getsuratjalan($request)
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

        $query = Mutasigudangjadi::query();
        $query->select(
            'gudang_jadi_mutasi.no_mutasi',
            'gudang_jadi_mutasi.tanggal',
            'no_dok',
            'status_surat_jalan',
            'nama_cabang',
            'gudang_cabang_mutasi.tanggal as tanggal_mutasi_cabang',
            'tanggal_transit_in'
        );
        $query->join('marketing_permintaan_kiriman', 'gudang_jadi_mutasi.no_permintaan', '=', 'marketing_permintaan_kiriman.no_permintaan');
        $query->join('cabang', 'marketing_permintaan_kiriman.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftJoin('gudang_cabang_mutasi', 'gudang_jadi_mutasi.no_mutasi', '=', 'gudang_cabang_mutasi.no_mutasi');
        $query->leftJoin(
            DB::raw("(
                SELECT no_surat_jalan,tanggal as tanggal_transit_in
                FROM gudang_cabang_mutasi
                WHERE jenis_mutasi ='TI'
            ) transitin"),
            function ($join) {
                $join->on('gudang_jadi_mutasi.no_mutasi', '=', 'transitin.no_surat_jalan');
            }
        );
        $query->where('gudang_jadi_mutasi.jenis_mutasi', 'SJ');

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('gudang_jadi_mutasi.tanggal', [$request->dari, $request->sampai]);
        } else {
            $query->whereBetween('gudang_jadi_mutasi.tanggal', [$start_date, $end_date]);
        }

        if (!empty($request->no_dok_search)) {
            $query->where('gudang_jadi_mutasi.no_dok', $request->no_dok_search);
        }
        if (!empty($request->kode_cabang_search)) {
            $query->where('cabang.kode_cabang', $request->kode_cabang_search);
        }

        if ($request->status_search != '') {
            $query->where('gudang_jadi_mutasi.status_surat_jalan', $request->status_search);
        }
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('marketing_permintaan_kiriman.kode_cabang', auth()->user()->kode_cabang);
            }
        }
        //$query->where('gudang_jadi_mutasi.no_mutasi', 'SJBDG01.13.04.2024');
        $query->orderBy('gudang_jadi_mutasi.tanggal', 'desc');
        $query->orderBy('gudang_jadi_mutasi.created_at', 'desc');
        return $query;
    }
    public function index(Request $request)
    {
        $sj =  $this->getsuratjalan($request);
        $surat_jalan = $sj->paginate(15);
        $surat_jalan->appends($request->all());
        $data['surat_jalan'] = $surat_jalan;
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('gudangjadi.suratjalan.index_gudangjadi', $data);
    }

    public function index_gudangcabang(Request $request)
    {
        $sj =  $this->getsuratjalan($request);
        $surat_jalan = $sj->paginate(15);
        $surat_jalan->appends($request->all());
        $data['surat_jalan'] = $surat_jalan;
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('gudangjadi.suratjalan.index_gudangcabang', $data);
    }

    public function create($no_permintaan)
    {
        $no_permintaan = Crypt::decrypt($no_permintaan);
        $data['tujuan_angkutan'] = Tujuanangkutan::orderBy('kode_tujuan')->where('status', 1)->get();
        $data['angkutan'] = Angkutan::orderBy('kode_angkutan')->get();
        $data['pk'] = Permintaankiriman::where('no_permintaan', $no_permintaan)
            ->join('cabang', 'marketing_permintaan_kiriman.kode_cabang', '=', 'cabang.kode_cabang')
            ->leftJoin('salesman', 'marketing_permintaan_kiriman.kode_salesman', '=', 'salesman.kode_salesman')
            ->first();
        $data['detail'] = Detailpermintaankiriman::select('marketing_permintaan_kiriman_detail.kode_produk', 'nama_produk', 'jumlah')
            ->join('produk', 'marketing_permintaan_kiriman_detail.kode_produk', '=', 'produk.kode_produk')
            ->where('no_permintaan', $no_permintaan)
            ->orderBy('marketing_permintaan_kiriman_detail.kode_produk')
            ->get();
        $data['produk'] = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        return view('gudangjadi.suratjalan.create', $data);
    }


    public function store($no_permintaan, Request $request)
    {
        $no_permintaan = Crypt::decrypt($no_permintaan);
        $kode_produk = $request->kode_produk;
        $jml = $request->jml;


        DB::beginTransaction();
        try {
            //Buat No. Surat Jalan
            $pk = Permintaankiriman::where('no_permintaan', $no_permintaan)->first();
            $kode = strlen($pk->kode_cabang);
            $jmlkarakter_no_surat_jalan = $kode + 4;
            $last_surat_jalan = Mutasigudangjadi::select(
                DB::raw('LEFT(no_mutasi,' . $jmlkarakter_no_surat_jalan . ') as no_surat_jalan')
            )
                ->whereRaw('MID(no_mutasi,3,' . $kode . ')="' . $pk->kode_cabang . '"')
                ->where('tanggal', $request->tanggal)
                ->where('jenis_mutasi', 'SJ')
                ->orderByRaw('LEFT(no_mutasi,' . $jmlkarakter_no_surat_jalan . ') desc')
                ->first();



            $hari = date('d', strtotime($request->tanggal));
            $bulan = date('m', strtotime($request->tanggal));
            $tahun = date('Y', strtotime($request->tanggal));

            $format = "." . $hari . "." . $bulan . "." . $tahun;
            $last_no_surat_jalan  = $last_surat_jalan != null ? $last_surat_jalan->no_surat_jalan : '';
            $no_surat_jalan = buatkode($last_no_surat_jalan, "SJ" . $pk->kode_cabang, 2) . $format;

            $cektutuplaporan = cektutupLaporan($request->tanggal, "gudangjadi");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            if (empty($kode_produk)) {
                return Redirect::back()->with(messageError('Data Detail Produk Masih Kosong !'));
            }

            $cek_dokumen = Mutasigudangjadi::where('no_dok', $request->no_dok)->count();
            if ($cek_dokumen > 0) {
                return Redirect::back()->with(messageError('No. Dokumen Sudah Ada !'));
            }

            $cek_surat_jalan = Mutasigudangjadi::where('no_mutasi', $no_surat_jalan)->count();
            if ($cek_surat_jalan > 0) {
                return Redirect::back()->with(messageError('Data Surat Jalan Sudah Ada !'));
            }

            for ($i = 0; $i < count($kode_produk); $i++) {
                $detail[] = [
                    'no_mutasi' => $no_surat_jalan,
                    'kode_produk' => $kode_produk[$i],
                    'jumlah' => toNumber($jml[$i])
                ];
            }

            $timestamp = Carbon::now();

            foreach ($detail as &$record) {
                $record['created_at'] = $timestamp;
                $record['updated_at'] = $timestamp;
            }

            //Simpan Data Surat Jalan
            Mutasigudangjadi::create([
                'no_mutasi' => $no_surat_jalan,
                'tanggal' => $request->tanggal,
                'no_dok' => textUpperCase($request->no_dok),
                'no_permintaan' => $no_permintaan,
                'jenis_mutasi' => 'SJ',
                'in_out' => 'O',
                'status_surat_jalan' => 0,
                'id_user' => auth()->user()->id,
            ]);

            //Simpan Detail Surat Jalan

            $chunks_buffer = array_chunk($detail, 5);
            foreach ($chunks_buffer as $chunk_buffer) {
                Detailmutasigudangjadi::insert($chunk_buffer);
            }

            //Update Status Permintaan Kiriman
            Permintaankiriman::where('no_permintaan', $no_permintaan)->update([
                'status' => 1,
            ]);

            //Simpan Data Surat Jalan Angkutan Jika Angkutan disii

            if (isset($request->kode_angkutan)) {
                Suratjalanangkutan::create([
                    'no_dok' => textUpperCase($request->no_dok),
                    'no_polisi' => $request->no_polisi,
                    'kode_angkutan' => $request->kode_angkutan,
                    'kode_tujuan' => $request->kode_tujuan,
                    'tarif' => toNumber($request->tarif),
                    'tepung' => toNumber($request->tepung),
                    'bs' => toNumber($request->bs),

                ]);
            }


            DB::commit();
            return redirect()->back()->with('success', 'Data Surat Jalan Berhasil Disimpan !');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show($no_mutasi)
    {
        $no_mutasi = Crypt::decrypt($no_mutasi);
        $data['surat_jalan'] = Mutasigudangjadi::where('no_mutasi', $no_mutasi)
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
        $data['detail'] = Detailmutasigudangjadi::where('no_mutasi', $no_mutasi)
            ->join('produk', 'gudang_jadi_mutasi_detail.kode_produk', '=', 'produk.kode_produk')
            ->get();
        return view('gudangjadi.suratjalan.show', $data);
    }

    public function edit($no_mutasi)
    {
        $no_mutasi = Crypt::decrypt($no_mutasi);
        $data['tujuan_angkutan'] = Tujuanangkutan::orderBy('kode_tujuan')->get();
        $data['angkutan'] = Angkutan::orderBy('kode_angkutan')->get();
        $data['surat_jalan'] = Mutasigudangjadi::where('no_mutasi', $no_mutasi)
            ->select(
                'gudang_jadi_mutasi.no_mutasi',
                'gudang_jadi_mutasi.tanggal',
                'gudang_jadi_mutasi.no_permintaan',
                'marketing_permintaan_kiriman.tanggal as tanggal_permintaan',
                'cabang.nama_cabang',
                'marketing_permintaan_kiriman.keterangan as keterangan_permintaan',
                'gudang_jadi_mutasi.status_surat_jalan',
                'gudang_jadi_angkutan_suratjalan.no_dok',
                'gudang_jadi_angkutan_suratjalan.kode_tujuan',
                'gudang_jadi_angkutan_suratjalan.kode_angkutan',
                'gudang_jadi_angkutan_suratjalan.no_polisi',
                'gudang_jadi_angkutan_suratjalan.tarif',
                'gudang_jadi_angkutan_suratjalan.tepung',
                'gudang_jadi_angkutan_suratjalan.bs'
            )
            ->join('marketing_permintaan_kiriman', 'gudang_jadi_mutasi.no_permintaan', '=', 'marketing_permintaan_kiriman.no_permintaan')
            ->join('cabang', 'marketing_permintaan_kiriman.kode_cabang', '=', 'cabang.kode_cabang')
            ->leftjoin('gudang_jadi_angkutan_suratjalan', 'gudang_jadi_mutasi.no_dok', '=', 'gudang_jadi_angkutan_suratjalan.no_dok')
            ->first();
        $data['produk'] = Produk::orderBy('kode_produk')->where('status_aktif_produk', 1)->get();
        $data['detail'] = Detailmutasigudangjadi::where('no_mutasi', $no_mutasi)
            ->where('status_aktif_produk', 1)
            ->join('produk', 'gudang_jadi_mutasi_detail.kode_produk', '=', 'produk.kode_produk')
            ->get();
        return view('gudangjadi.suratjalan.edit', $data);
    }



    public function update($no_mutasi, Request $request)
    {
        $no_mutasi = Crypt::decrypt($no_mutasi);
        $kode_produk = $request->kode_produk;
        $jml = $request->jml;


        DB::beginTransaction();
        try {
            //Buat No. Surat Jalan
            $surat_jalan = Mutasigudangjadi::where('no_mutasi', $no_mutasi)->first();
            $pk = Permintaankiriman::where('no_permintaan', $surat_jalan->no_permintaan)->first();
            $kode = strlen($pk->kode_cabang);
            $jmlkarakter_no_surat_jalan = $kode + 4;
            $last_surat_jalan = Mutasigudangjadi::select(
                DB::raw('LEFT(no_mutasi,' . $jmlkarakter_no_surat_jalan . ') as no_surat_jalan')
            )
                ->where('no_mutasi', '!=', $no_mutasi)
                ->whereRaw('MID(no_mutasi,3,' . $kode . ')="' . $pk->kode_cabang . '"')
                ->where('tanggal', $request->tanggal)
                ->where('jenis_mutasi', 'SJ')
                ->orderByRaw('LEFT(no_mutasi,' . $jmlkarakter_no_surat_jalan . ') desc')
                ->first();



            $hari = date('d', strtotime($request->tanggal));
            $bulan = date('m', strtotime($request->tanggal));
            $tahun = date('Y', strtotime($request->tanggal));

            $format = "." . $hari . "." . $bulan . "." . $tahun;
            $last_no_surat_jalan  = $last_surat_jalan != null ? $last_surat_jalan->no_surat_jalan : '';
            $no_surat_jalan = buatkode($last_no_surat_jalan, "SJ" . $pk->kode_cabang, 2) . $format;

            $cektutuplaporan = cektutupLaporan($request->tanggal, "gudangjadi");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            if (empty($kode_produk)) {
                return Redirect::back()->with(messageError('Data Detail Produk Masih Kosong !'));
            }



            for ($i = 0; $i < count($kode_produk); $i++) {
                $detail[] = [
                    'no_mutasi' => $no_surat_jalan,
                    'kode_produk' => $kode_produk[$i],
                    'jumlah' => toNumber($jml[$i])
                ];
            }

            $timestamp = Carbon::now();

            foreach ($detail as &$record) {
                $record['created_at'] = $timestamp;
                $record['updated_at'] = $timestamp;
            }

            //Hapus Detail Surat Jalan
            Detailmutasigudangjadi::where('no_mutasi', $no_mutasi)->delete();

            //Simpan Data Surat Jalan
            Mutasigudangjadi::where('no_mutasi', $no_mutasi)->update([
                'no_mutasi' => $no_surat_jalan,
                'tanggal' => $request->tanggal,
                'no_dok' => textUpperCase($request->no_dok),
                'no_permintaan' => $surat_jalan->no_permintaan,
                'jenis_mutasi' => 'SJ',
                'in_out' => 'O',
                'status_surat_jalan' => 0,
                'id_user' => auth()->user()->id,
            ]);


            //Simpan Detail Surat Jalan
            $chunks_buffer = array_chunk($detail, 5);
            foreach ($chunks_buffer as $chunk_buffer) {
                Detailmutasigudangjadi::insert($chunk_buffer);
            }


            //Hapus Angkutan

            Suratjalanangkutan::where('no_dok', $surat_jalan->no_dok)->delete();
            //Simpan Data Surat Jalan Angkutan Jika Angkutan disii

            if (isset($request->kode_angkutan)) {
                Suratjalanangkutan::create([
                    'no_dok' => textUpperCase($request->no_dok),
                    'no_polisi' => $request->no_polisi,
                    'kode_angkutan' => $request->kode_angkutan,
                    'kode_tujuan' => $request->kode_tujuan,
                    'tarif' => toNumber($request->tarif),
                    'tepung' => toNumber($request->tepung),
                    'bs' => toNumber($request->bs),

                ]);
            }


            DB::commit();
            return redirect()->back()->with('success', 'Data  Berhasil Update !');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function approveform($no_mutasi)
    {
        $no_mutasi = Crypt::decrypt($no_mutasi);
        $data['surat_jalan'] = Mutasigudangjadi::where('no_mutasi', $no_mutasi)
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
        $data['detail'] = Detailmutasigudangjadi::where('no_mutasi', $no_mutasi)
            ->join('produk', 'gudang_jadi_mutasi_detail.kode_produk', '=', 'produk.kode_produk')
            ->get();
        return view('gudangjadi.suratjalan.approveform', $data);
    }

    public function approve($no_mutasi, Request $request)
    {
        $no_mutasi = Crypt::decrypt($no_mutasi);
        $request->validate([
            'status' => 'required',
            'tanggal' => 'required'
        ]);
        $surat_jalan = Mutasigudangjadi::where('no_mutasi', $no_mutasi)
            ->select(
                'gudang_jadi_mutasi.no_mutasi',
                'gudang_jadi_mutasi.tanggal',
                'gudang_jadi_mutasi.no_permintaan',
                'marketing_permintaan_kiriman.tanggal as tanggal_permintaan',
                'marketing_permintaan_kiriman.kode_cabang',
                'cabang.nama_cabang',
                'marketing_permintaan_kiriman.keterangan',
                'gudang_jadi_mutasi.status_surat_jalan',
                'no_dok'
            )
            ->join('marketing_permintaan_kiriman', 'gudang_jadi_mutasi.no_permintaan', '=', 'marketing_permintaan_kiriman.no_permintaan')
            ->join('cabang', 'marketing_permintaan_kiriman.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();

        DB::beginTransaction();
        try {

            //Cheking
            $cektutuplaporan = cektutupLaporan($request->tanggal, "gudangcabang");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            $ceksuratjalan = Mutasigudangcabang::where('no_mutasi', $no_mutasi)->count();
            if ($ceksuratjalan > 0) {
                return Redirect::back()->with(messageError('Data Sudah Ada !'));
            }


            // Insert Mutasi Gudang Cabang
            Mutasigudangcabang::create([
                'no_mutasi' => $no_mutasi,
                'tanggal' => $request->tanggal,
                'tanggal_kirim' => $surat_jalan->tanggal,
                'kode_cabang' => $surat_jalan->kode_cabang,
                'kondisi' => 'G',
                'in_out_good' => 'I',
                'jenis_mutasi' => 'SJ',
                'id_user' => auth()->user()->id

            ]);
            //Update Mutasi Gudang Jadi
            Mutasigudangjadi::where('no_mutasi', $no_mutasi)->update(['status_surat_jalan' => '1']);


            //Insert Detail
            $detail = Detailmutasigudangjadi::where('no_mutasi', $no_mutasi)
                ->join('produk', 'gudang_jadi_mutasi_detail.kode_produk', '=', 'produk.kode_produk')
                ->get();
            foreach ($detail as $d) {
                $detail_surat_jalan[] = [
                    'no_mutasi' => $no_mutasi,
                    'kode_produk' => $d->kode_produk,
                    'jumlah' => $d->jumlah * $d->isi_pcs_dus
                ];
            }
            Detailmutasigudangcabang::insert($detail_surat_jalan);


            if ($request->status == "2") {
                //BUat No. Transit Out
                $tahun = substr(date('Y'), 2, 2);
                $last_transit_out = Mutasigudangcabang::select('no_mutasi as no_transit_out')
                    ->where('kode_cabang', $surat_jalan->kode_cabang)
                    ->where('jenis_mutasi', 'TO')
                    ->whereRaw('MID(no_mutasi,6,2) =' . $tahun)
                    ->orderBy('no_mutasi', 'desc')
                    ->first();
                $last_no_transit_out = $last_transit_out != null ? $last_transit_out->no_transit_out : '';
                $no_transit_out = buatkode($last_no_transit_out, 'TO' . $surat_jalan->kode_cabang . $tahun, 2);

                // Insert Mutasi Gudang Cabang
                Mutasigudangcabang::create([
                    'no_mutasi' => $no_transit_out,
                    'tanggal' => $request->tanggal,
                    'tanggal_kirim' => $surat_jalan->tanggal,
                    'no_surat_jalan' => $no_mutasi,
                    'kode_cabang' => $surat_jalan->kode_cabang,
                    'kondisi' => 'G',
                    'in_out_good' => 'O',
                    'jenis_mutasi' => 'TO',
                    'id_user' => auth()->user()->id

                ]);
                //Update Mutasi Gudang Jadi
                Mutasigudangjadi::where('no_mutasi', $no_mutasi)->update(['status_surat_jalan' => '2']);


                //Insert Detail
                $detail_transit_out = Detailmutasigudangjadi::where('no_mutasi', $no_mutasi)
                    ->join('produk', 'gudang_jadi_mutasi_detail.kode_produk', '=', 'produk.kode_produk')
                    ->get();
                foreach ($detail_transit_out as $d) {
                    $detail_to[] = [
                        'no_mutasi' => $no_transit_out,
                        'kode_produk' => $d->kode_produk,
                        'jumlah' => $d->jumlah * $d->isi_pcs_dus
                    ];
                }
                Detailmutasigudangcabang::insert($detail_to);
            }

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Di Terima'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function cancel($no_mutasi)
    {
        $no_mutasi = Crypt::decrypt($no_mutasi);
        $surat_jalan = Mutasigudangcabang::where('no_mutasi', $no_mutasi)->first();
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($surat_jalan->tanggal, "gudangcabang");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            //Hapus Surat Jalan Mutasi Cabang
            Mutasigudangcabang::where('no_mutasi', $no_mutasi)->delete();
            Mutasigudangcabang::where('no_surat_jalan', $no_mutasi)->delete();
            Mutasigudangjadi::where('no_mutasi', $no_mutasi)->update(['status_surat_jalan' => 0]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Di Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
    public function destroy($no_mutasi)
    {
        $no_mutasi = Crypt::decrypt($no_mutasi);
        $surat_jalan = Mutasigudangjadi::where('no_mutasi', $no_mutasi)->first();
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($surat_jalan->tanggal, "gudangjadi");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            //Hapus Surat Jalan
            Mutasigudangjadi::where('no_mutasi', $no_mutasi)->delete();
            //Update Status Permintaan Pengiriman
            Permintaankiriman::where('no_permintaan', $surat_jalan->no_permintaan)->update([
                'status' => 0,
            ]);
            //Hapus Surat Jalan Angkutan
            Suratjalanangkutan::where('no_dok', $surat_jalan->no_dok)->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
