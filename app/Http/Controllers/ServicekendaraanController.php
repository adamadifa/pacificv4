<?php

namespace App\Http\Controllers;

use App\Models\Detailservicekendaraan;
use App\Models\Kendaraan;
use App\Models\Servicekendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ServicekendaraanController extends Controller
{
    public function index(Request $request)
    {

        $start_date = config('global.start_date');
        $end_date = config('global.end_date');
        $query = Servicekendaraan::query();
        $query->select('ga_kendaraan_service.*', 'no_polisi', 'merek', 'tipe_kendaraan', 'tipe', 'nama_bengkel', 'nama_cabang');
        $query->join('kendaraan', 'ga_kendaraan_service.kode_kendaraan', 'kendaraan.kode_kendaraan');
        $query->join('ga_bengkel', 'ga_kendaraan_service.kode_bengkel', 'ga_bengkel.kode_bengkel');
        $query->join('cabang', 'ga_kendaraan_service.kode_cabang', 'cabang.kode_cabang');
        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Request::back()->with(messageError('Data Tidak Ditemukan'));
            } else {
                $query->whereBetween('ga_kendaraan_service.tanggal', [$request->dari, $request->sampai]);
            }
        } else {
            $query->whereBetween('ga_kendaraan_service.tanggal', [$start_date, $end_date]);
        }

        if (!empty($request->kode_kendaraan_search)) {
            $query->where('ga_kendaraan_service.kode_kendaraan', $request->kode_kendaraan_search);
        }
        $query->orderBy('ga_kendaraan_service.tanggal', 'desc');
        $query->orderBy('ga_kendaraan_service.kode_service', 'desc');
        $servicekendaraan = $query->paginate(15);
        $servicekendaraan->appends($request->all());
        $data['servicekendaraan'] = $servicekendaraan;

        $data['kendaraan'] =  Kendaraan::orderBy('no_polisi', 'asc')->get();
        return view('generalaffair.servicekendaraan.index', $data);
    }

    public function show($kode_service)
    {
        $kode_service = Crypt::decrypt($kode_service);
        $data['servicekendaraan'] = Servicekendaraan::where('kode_service', $kode_service)
            ->select('ga_kendaraan_service.*', 'no_polisi', 'merek', 'tipe_kendaraan', 'tipe', 'nama_bengkel', 'nama_cabang')
            ->join('kendaraan', 'ga_kendaraan_service.kode_kendaraan', 'kendaraan.kode_kendaraan')
            ->join('ga_bengkel', 'ga_kendaraan_service.kode_bengkel', 'ga_bengkel.kode_bengkel')
            ->join('cabang', 'ga_kendaraan_service.kode_cabang', 'cabang.kode_cabang')
            ->first();

        $data['detail'] = Detailservicekendaraan::where('kode_service', $kode_service)
            ->join('ga_kendaraan_service_item', 'ga_kendaraan_service_detail.kode_item', 'ga_kendaraan_service_item.kode_item')
            ->get();
        return view('generalaffair.servicekendaraan.show', $data);
    }

    public function create()
    {
        $data['kendaraan'] = Kendaraan::all();
        return view('generalaffair.servicekendaraan.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_invoice' => 'required',
            'tanggal' => 'required',
            'kode_kendaraan' => 'required',
            'kode_bengkel' => 'required',
        ]);

        $kode_item = $request->kode_item_service;
        $harga = $request->harga_item_service;
        $jumlah = $request->jumlah_item_service;
        //dd($harga);
        DB::beginTransaction();
        try {
            $lastservice = Servicekendaraan::whereRaw('YEAR(tanggal)="' . date('Y', strtotime($request->tanggal)) . '"')
                ->whereRaw('MONTH(tanggal)="' . date('m', strtotime($request->tanggal)) . '"')
                ->orderBy("kode_service", "desc")
                ->first();

            $lastservice = $lastservice != null ? $lastservice->kode_service : '';
            $kode_service = buatkode($lastservice, "SK" . date('my', strtotime($request->tanggal)), 4);

            $kendaraan = Kendaraan::where('kode_kendaraan', $request->kode_kendaraan)->first();
            Servicekendaraan::create([
                'kode_service' => $kode_service,
                'no_invoice' => $request->no_invoice,
                'tanggal' => $request->tanggal,
                'kode_kendaraan' => $request->kode_kendaraan,
                'kode_bengkel' => $request->kode_bengkel,
                'kode_cabang' => $kendaraan->kode_cabang,
                'keterangan' => $request->keterangan,
            ]);

            if (empty($kode_item)) {
                return Redirect::back()->with(messageError('Item Tidak Boleh Kosong'));
            }

            for ($i = 0; $i < count($kode_item); $i++) {
                $detail[] =  [
                    'kode_service' => $kode_service,
                    'kode_item' => $kode_item[$i],
                    'harga' => toNumber($harga[$i]),
                    'jumlah' => toNumber($jumlah[$i]),
                ];
            }

            Detailservicekendaraan::insert($detail);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e);
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_service)
    {
        $kode_service = Crypt::decrypt($kode_service);
        $servicekendaraan = Servicekendaraan::where('kode_service', $kode_service);
        if (!$servicekendaraan) {
            return Redirect::back()->with(messageError('Data tidak ditemukan'));
        }

        try {
            $servicekendaraan->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
