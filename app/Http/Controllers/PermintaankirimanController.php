<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailpermintaankiriman;
use App\Models\Detailpermintaankirimantemp;
use App\Models\Permintaankiriman;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PermintaankirimanController extends Controller
{
    public function index(Request $request)
    {
        if (!empty($request->dari)) {
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
        $pk = $query->paginate(15);
        $pk->appends(request()->all());

        $data['pk'] = $pk;
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('marketing.permintaankiriman.index', $data);
    }


    public function create()
    {
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        $data['produk'] = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        return view('marketing.permintaankiriman.create', $data);
    }

    public function store(Request $request)
    {
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
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
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
                return 1;
            }
            Detailpermintaankirimantemp::create([
                'kode_produk' => $request->kode_produk,
                'jumlah' => toNumber($request->jumlah),
                'id_user' => auth()->user()->id
            ]);

            return 0;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function deletetemp(Request $request)
    {
        try {
            Detailpermintaankirimantemp::where('id', $request->id)->delete();
            return 0;
        } catch (\Exception $e) {
            return $e;
        }
    }
}
