<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailmutasigudangjadi;
use App\Models\Detailpermintaankiriman;
use App\Models\Detailpermintaankirimantemp;
use App\Models\Mutasigudangjadi;
use App\Models\Permintaankiriman;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PermintaankirimanController extends Controller
{
    public function index(Request $request)
    {

        $start_year = config('global.start_year');
        $start_date = config('global.start_date');
        $end_date = config('global.end_date');


        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }
        $query = Permintaankiriman::query();
        $query->select('marketing_permintaan_kiriman.*', 'nama_salesman', 'no_mutasi', 'no_dok', 'gudang_jadi_mutasi.tanggal as tanggal_surat_jalan', 'status_surat_jalan');
        $query->leftJoin('salesman', 'marketing_permintaan_kiriman.kode_salesman', '=', 'salesman.kode_salesman');
        $query->leftJoin('gudang_jadi_mutasi', 'marketing_permintaan_kiriman.no_permintaan', '=', 'gudang_jadi_mutasi.no_permintaan');
        $query->orderBy('status', 'asc');
        $query->orderBy('tanggal', 'desc');
        $query->orderBy('marketing_permintaan_kiriman.no_permintaan', 'desc');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('marketing_permintaan_kiriman.tanggal', [$request->dari, $request->sampai]);
        } else {
            $query->whereBetween('marketing_permintaan_kiriman.tanggal', [$start_date, $end_date]);
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('marketing_permintaan_kiriman.kode_cabang', $request->kode_cabang_search);
        }

        if (!empty($request->status_search)) {
            $status = explode("|", $request->status_search);
            if ($status[1] == "pk") {
                $query->where('marketing_permintaan_kiriman.status', $status[0]);
            } else {
                $query->where('gudang_jadi_mutasi.status_surat_jalan', $status[0]);
            }
        }
        $pk = $query->paginate(15);
        $pk->appends(request()->all());

        $data['pk'] = $pk;
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        return view('marketing.permintaankiriman.index', $data);
    }


    public function create()
    {
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['produk'] = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        return view('marketing.permintaankiriman.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required',
            'kode_cabang' => 'required',
            'keterangan' => 'required'
        ]);
        DB::beginTransaction();
        try {
            //Buat No Permintaan
            $tanggal = $request->tanggal;
            $tgl = explode("-", $tanggal);
            $format = $tgl[02] . "." . $tgl[1] . "." . $tgl[0];

            $kode_cabang = $request->kode_cabang;
            $kode = strlen($kode_cabang);
            $no_permintaan  = $kode + 4;

            $pk = Permintaankiriman::select(
                DB::raw("LEFT(no_permintaan,$no_permintaan) as no_permintaan")
            )
                ->whereRaw('MID(no_permintaan,3,' . $kode . ')="' . $kode_cabang . '"')
                ->whereRaw('RIGHT(no_permintaan,10)="' . $format . '"')
                ->orderByRaw('LEFT(no_permintaan,' . $no_permintaan . ') DESC')
                ->first();


            if ($pk != null) {
                $last_no_permintaan = $pk->no_permintaan;
            } else {
                $last_no_permintaan = "";
            }

            $no_permintaan = buatkode($last_no_permintaan, "OR" . $kode_cabang, 2) . "." . $format;
            $kode_salesman = isset($request->kode_salesman) ? $request->kode_salesman : NULL;

            $cektutuplaporan = cektutupLaporan($request->tanggal, "gudangjadi");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            $temp = Detailpermintaankirimantemp::where('id_user', auth()->user()->id);


            $cekdetailtemp = $temp->count();

            if (empty($cekdetailtemp)) {
                return Redirect::back()->with(messageError('Data Detail Produk Masih Kosong !'));
            }

            $cekpermintaankiriman = Permintaankiriman::where('no_permintaan', $no_permintaan)->count();
            if ($cekpermintaankiriman > 0) {
                return Redirect::back()->with(messageError('Data Sudah Ada !'));
            }

            $detailtemp = $temp->get();
            foreach ($detailtemp as $d) {
                $detail[] = [
                    'no_permintaan' => $no_permintaan,
                    'kode_produk' => $d->kode_produk,
                    'jumlah' => $d->jumlah
                ];
            }
            Permintaankiriman::create([
                'no_permintaan' => $no_permintaan,
                'tanggal' => $tanggal,
                'kode_cabang' => $kode_cabang,
                'keterangan' => $request->keterangan,
                'status' => 0,
                'kode_salesman' => $kode_salesman,
                'id_user' => auth()->user()->id
            ]);

            Detailpermintaankiriman::insert($detail);

            Detailpermintaankirimantemp::where('id_user', auth()->user()->id)->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($no_permintaan)
    {
        $no_permintaan = Crypt::decrypt($no_permintaan);
        $data['pk'] = Permintaankiriman::where('no_permintaan', $no_permintaan)
            ->select('marketing_permintaan_kiriman.*', 'nama_cabang', 'nama_salesman')
            ->join('cabang', 'marketing_permintaan_kiriman.kode_cabang', '=', 'cabang.kode_cabang')
            ->leftJoin('salesman', 'marketing_permintaan_kiriman.kode_salesman', '=', 'salesman.kode_salesman')
            ->first();
        $data['detail'] = Detailpermintaankiriman::select('marketing_permintaan_kiriman_detail.kode_produk', 'nama_produk', 'jumlah')
            ->join('produk', 'marketing_permintaan_kiriman_detail.kode_produk', '=', 'produk.kode_produk')
            ->where('no_permintaan', $no_permintaan)
            ->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['produk'] = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        return view('marketing.permintaankiriman.edit', $data);
    }

    public function update($no_permintaan_old, Request $request)
    {
        $no_permintaan_old = Crypt::decrypt($no_permintaan_old);
        $kode_produk = $request->kode_produk;
        $jml = $request->jml;

        //dd(empty($kode_produk));
        DB::beginTransaction();
        try {
            //Buat No Permintaan
            $tanggal = $request->tanggal;
            $tgl = explode("-", $tanggal);
            $format = $tgl[02] . "." . $tgl[1] . "." . $tgl[0];

            $kode_cabang = $request->kode_cabang;
            $kode = strlen($kode_cabang);
            $no_permintaan  = $kode + 4;

            $pk = Permintaankiriman::select(
                DB::raw("LEFT(no_permintaan,$no_permintaan) as no_permintaan")
            )
                ->whereRaw('MID(no_permintaan,3,' . $kode . ')="' . $kode_cabang . '"')
                ->whereRaw('RIGHT(no_permintaan,10)="' . $format . '"')
                ->orderByRaw('LEFT(no_permintaan,' . $no_permintaan . ') DESC')
                ->first();


            if ($pk != null) {
                $last_no_permintaan = $pk->no_permintaan;
            } else {
                $last_no_permintaan = "";
            }

            $no_permintaan = buatkode($last_no_permintaan, "OR" . $kode_cabang, 2) . "." . $format;
            $kode_salesman = isset($request->kode_salesman) ? $request->kode_salesman : NULL;

            $cektutuplaporan = cektutupLaporan($request->tanggal, "gudangjadi");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            if (empty($kode_produk)) {
                return Redirect::back()->with(messageError('Data Detail Produk Masih Kosong !'));
            }

            $cekpermintaankiriman = Permintaankiriman::where('no_permintaan', $no_permintaan)->count();
            if ($cekpermintaankiriman > 0) {
                return Redirect::back()->with(messageError('Data Sudah Ada !'));
            }

            for ($i = 0; $i < count($kode_produk); $i++) {
                $detail[] = [
                    'no_permintaan' => $no_permintaan,
                    'kode_produk' => $kode_produk[$i],
                    'jumlah' => toNumber($jml[$i])
                ];
            }

            $timestamp = Carbon::now();

            foreach ($detail as &$record) {
                $record['created_at'] = $timestamp;
                $record['updated_at'] = $timestamp;
            }


            //Hapus Detail Permintaan Kiriman Lama
            Detailpermintaankiriman::where('no_permintaan', $no_permintaan_old)->delete();

            //Update Data Permintaan
            Permintaankiriman::where('no_permintaan', $no_permintaan_old)->update([
                'no_permintaan' => $no_permintaan,
                'tanggal' => $tanggal,
                'kode_cabang' => $kode_cabang,
                'keterangan' => $request->keterangan,
                'status' => 0,
                'kode_salesman' => $kode_salesman,
                'id_user' => auth()->user()->id
            ]);

            //Insert Data Detail Permintaan Kiriman Baru
            $chunks_buffer = array_chunk($detail, 5);
            foreach ($chunks_buffer as $chunk_buffer) {
                Detailpermintaankiriman::insert($chunk_buffer);
            }

            DB::commit();

            return Redirect::back()->with(messageSuccess('Data Berhasil Di Update'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function show($no_permintaan)
    {
        $no_permintaan = Crypt::decrypt($no_permintaan);
        $data['pk'] = Permintaankiriman::where('no_permintaan', $no_permintaan)
            ->join('cabang', 'marketing_permintaan_kiriman.kode_cabang', '=', 'cabang.kode_cabang')
            ->leftJoin('salesman', 'marketing_permintaan_kiriman.kode_salesman', '=', 'salesman.kode_salesman')
            ->first();
        $data['detail'] = Detailpermintaankiriman::select('marketing_permintaan_kiriman_detail.kode_produk', 'nama_produk', 'jumlah')
            ->join('produk', 'marketing_permintaan_kiriman_detail.kode_produk', '=', 'produk.kode_produk')
            ->where('no_permintaan', $no_permintaan)
            ->orderBy('marketing_permintaan_kiriman_detail.kode_produk')
            ->get();

        $suratjalan = Mutasigudangjadi::where('no_permintaan', $no_permintaan)->first();
        $data['suratjalan'] = $suratjalan;
        if ($suratjalan != null) {
            $data['detailsuratjalan'] = Detailmutasigudangjadi::join('produk', 'gudang_jadi_mutasi_detail.kode_produk', '=', 'produk.kode_produk')
                ->where('no_mutasi', $suratjalan->no_mutasi)
                ->orderBy('gudang_jadi_mutasi_detail.kode_produk')
                ->get();
        }

        return view('marketing.permintaankiriman.show', $data);
    }


    public function destroy($no_permintaan)
    {
        $no_permintaan = Crypt::decrypt($no_permintaan);
        $pk = Permintaankiriman::where('no_permintaan', $no_permintaan)->first();
        try {
            $cektutuplaporan = cektutupLaporan($pk->tanggal, "produksi");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            Permintaankiriman::where('no_permintaan', $no_permintaan)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    //AJAX REQUEST
    public function cekdetailtemp()
    {
        $cek = Detailpermintaankirimantemp::where('id_user', auth()->user()->id)->count();
        return $cek;
    }

    public function getdetailtemp()
    {
        $data['detailtemp'] = Detailpermintaankirimantemp::join('produk', 'marketing_permintaan_kiriman_detail_temp.kode_produk', '=', 'produk.kode_produk')
            ->where('id_user', auth()->user()->id)
            ->orderBy('marketing_permintaan_kiriman_detail_temp.kode_produk')
            ->get();
        return view('marketing.permintaankiriman.getdetailtemp', $data);
    }

    public function storedetailtemp(Request $request)
    {
        try {

            $cek = Detailpermintaankirimantemp::where('id_user', auth()->user()->id)
                ->where('kode_produk', $request->kode_produk)
                ->count();
            if ($cek > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Sudah Ada',
                ]);
            }
            Detailpermintaankirimantemp::create([
                'kode_produk' => $request->kode_produk,
                'jumlah' => toNumber($request->jumlah),
                'id_user' => auth()->user()->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Ditambahkan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function deletetemp(Request $request)
    {
        try {
            Detailpermintaankirimantemp::where('id', $request->id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
