<?php

namespace App\Http\Controllers;

use App\Models\Badstokga;
use App\Models\Cabang;
use App\Models\Detailbadstokga;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class BadstokgaController extends Controller
{
    public function index(Request $request)
    {
        $start_date = config('global.start_date');
        $end_date = config('global.end_date');
        $query = Badstokga::query();
        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            } else {
                $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
            }
        } else {
            $query->whereBetween('tanggal', [$start_date, $end_date]);
        }
        if (!empty($request->kode_asal_bs_search)) {
            $query->where('kode_asal_bs', $request->kode_asal_bs_search);
        }

        $query->orderBy('tanggal', 'desc');
        $badstok = $query->paginate(15);
        $badstok->appends($request->all());
        $data['badstok'] = $badstok;

        $data['asalbadstok'] = Cabang::orderBy('kode_cabang', 'asc')->get();
        return view('generalaffair.badstok.index', $data);
    }

    public function create()
    {
        $data['asalbadstok'] = Cabang::orderBy('kode_cabang', 'asc')->get();
        $data['produk'] = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        return view('generalaffair.badstok.create', $data);
    }

    public function store(Request $request)
    {

        $request->validate([
            'kode_asal_bs' => 'required',
            'kode_produk' => 'required',
            'tanggal' => 'required',
        ]);

        $kode_produk = $request->kode_produk;
        $jumlah = $request->jumlah;
        DB::beginTransaction();
        try {
            $lastbadstok = Badstokga::whereRaw('MONTH(tanggal)="' . date('m', strtotime($request->tanggal)) . '"')
                ->whereRaw('YEAR(tanggal)="' . date('Y', strtotime($request->tanggal)) . '"')
                ->orderBy("kode_bs", "desc")
                ->first();

            $last_kode_bs = $lastbadstok != null ? $lastbadstok->kode_bs : '';
            $kode_bs  = buatkode($last_kode_bs, "BS" . date('my', strtotime($request->tanggal)), 2);

            if ($kode_produk == null) {
                return Redirect::back()->with(messageError('Produk Tidak Boleh Kosong'));
            }
            Badstokga::create([
                'kode_bs' => $kode_bs,
                'kode_asal_bs' => $request->kode_asal_bs,
                'tanggal' => $request->tanggal,
            ]);

            for ($i = 0; $i < count($kode_produk); $i++) {
                Detailbadstokga::create([
                    'kode_bs' => $kode_bs,
                    'kode_produk' => $kode_produk[$i],
                    'jumlah' => toNumber($jumlah[$i]),
                ]);
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function show($kode_bs)
    {
        $kode_bs = Crypt::decrypt($kode_bs);
        $data['badstok'] = Badstokga::where('kode_bs', $kode_bs)->first();
        $data['detail'] = Detailbadstokga::where('kode_bs', $kode_bs)
            ->join('produk', 'ga_badstok_detail.kode_produk', '=', 'produk.kode_produk')
            ->get();

        return view('generalaffair.badstok.show', $data);
    }

    public function destroy($kode_bs)
    {
        $kode_bs = Crypt::decrypt($kode_bs);
        try {
            $badstok = Badstokga::where('kode_bs', $kode_bs)->first();
            $badstok->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
