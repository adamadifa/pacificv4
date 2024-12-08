<?php

namespace App\Http\Controllers;

use App\Models\Ajuanprogramikatan;
use App\Models\Cabang;
use App\Models\Detailpenjualan;
use App\Models\Detailsaldoawalpiutangpelanggan;
use App\Models\Klasifikasioutlet;
use App\Models\Pelanggan;
use App\Models\Pengajuanfaktur;
use App\Models\Penjualan;
use App\Models\Programikatan;
use App\Models\Saldoawalpiutangpelanggan;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Intervention\Image\Facades\Image;

class PelangganController extends Controller
{
    public function index(Request $request)
    {

        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');


        $query = Pelanggan::query();
        $query->leftjoin('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah');
        $query->join('salesman', 'pelanggan.kode_salesman', '=', 'salesman.kode_salesman');
        $query->join('cabang', 'pelanggan.kode_cabang', '=', 'cabang.kode_cabang');
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('pelanggan.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        if (!empty($request->kode_cabang)) {
            $query->where('pelanggan.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_salesman)) {
            $query->where('pelanggan.kode_salesman', $request->kode_salesman);
        }

        if (!empty($request->kode_pelanggan)) {
            $query->where('kode_pelanggan', $request->kode_pelanggan);
        }

        if (!empty($request->nama_pelanggan)) {
            $query->where('nama_pelanggan', 'like', '%' . $request->nama_pelanggan . '%');
        }

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('pelanggan.tanggal_register', [$request->dari, $request->sampai]);
        }

        if (!empty($request->status)) {
            if ($request->status == 'aktif') {
                $query->where('pelanggan.status_aktif_pelanggan', '1');
            } elseif ($request->status == 'nonaktif') {
                $query->where('pelanggan.status_aktif_pelanggan', '0');
            }
        }

        if ($user->hasRole('salesman')) {
            $query->where('pelanggan.kode_salesman', $user->kode_salesman);
        }
        $query->orderBy('tanggal_register', 'desc');

        $pelanggan = $query->paginate('30');
        $pelanggan->appends(request()->all());

        $plg = new Pelanggan();
        $jmlpelanggan = $plg->getJmlpelanggan($request);
        $jmlpelangganaktif = $plg->getJmlpelanggan($request, 1);
        $jmlpelanggannonaktif = $plg->getJmlpelanggan($request, 0);


        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        if ($user->hasRole('salesman')) {
            return view('sfa.pelanggan', compact('pelanggan'));
        }
        return view('datamaster.pelanggan.index', compact(
            'pelanggan',
            'cabang',
            'jmlpelanggan',
            'jmlpelangganaktif',
            'jmlpelanggannonaktif',
        ));
    }

    public function create()
    {

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $klasifikasi_outlet = Klasifikasioutlet::orderBy('kode_klasifikasi')->get();
        return view('datamaster.pelanggan.create', compact('cabang', 'klasifikasi_outlet'));
    }


    public function store(Request $request)
    {


        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');

        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang;
            $request->validate([
                'nama_pelanggan' => 'required',
                'alamat_pelanggan' => 'required',
                'alamat_toko' => 'required',
                'kode_cabang' => 'required',
                'kode_salesman' => 'required',
                'kode_wilayah' => 'required',
                'hari' => 'required'
            ]);
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
            $request->validate([
                'nama_pelanggan' => 'required',
                'alamat_pelanggan' => 'required',
                'alamat_toko' => 'required',
                'kode_salesman' => 'required',
                'kode_wilayah' => 'required',
                'hari' => 'required'
            ]);
        }




        $lastpelanggan = Pelanggan::whereRaw('LEFT(kode_pelanggan,3)="' . $kode_cabang . '"')
            ->orderBy('kode_pelanggan', 'desc')
            ->first();
        $last_kode_pelanggan = $lastpelanggan->kode_pelanggan;
        $kode_pelanggan =  buatkode($last_kode_pelanggan, $kode_cabang . '-', 5);


        $data_foto = [];
        if ($request->hasfile('foto')) {
            $foto_name =  $kode_pelanggan . "." . $request->file('foto')->getClientOriginalExtension();
            $destination_foto_path = "/public/pelanggan";
            $foto = $foto_name;
            $data_foto = [
                'foto' => $foto
            ];
        }



        // if (isset($request->lokasi)) {
        //     $lokasi = explode(",", $request->lokasi);
        //     $latitude = $lokasi[0];
        //     $longitude = $lokasi[1];
        // } else {
        //     $latitude = NULL;
        //     $longitude = NULL;
        // }
        $latitude = NULL;
        $longitude = NULL;
        $data_pelanggan = [
            'kode_pelanggan' => $kode_pelanggan,
            'tanggal_register' => date('Y-m-d'),
            'nik' => $request->nik,
            'no_kk' => $request->no_kk,
            'nama_pelanggan' => $request->nama_pelanggan,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat_pelanggan' => $request->alamat_pelanggan,
            'alamat_toko' => $request->alamat_toko,
            'no_hp_pelanggan' => $request->no_hp_pelanggan,
            'kode_cabang' => $kode_cabang,
            'kode_salesman' => $request->kode_salesman,
            'kode_wilayah' => $request->kode_wilayah,
            'hari' => implode(",", $request->hari),
            'limit_pelanggan' => isset($request->limit_pelanggan) ?  toNumber($request->limit_pelanggan) : NULL,
            'ljt' => $request->ljt,
            'kepemilikan' => $request->kepemilikan,
            'lama_berjualan' => $request->lama_berjualan,
            'status_outlet' => $request->status_outlet,
            'type_outlet' => $request->type_outlet,
            'kode_klasifikasi' => $request->kode_klasifikasi,
            'cara_pembayaran' => $request->cara_pembayaran,
            'lama_langganan' => $request->lama_langganan,
            'jaminan' => $request->jaminan,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'omset_toko' => isset($request->omset_toko) ?  toNumber($request->omset_toko) : NULL,
            'status_aktif_pelanggan' => $request->status_aktif_pelanggan,
        ];
        $data = array_merge($data_pelanggan, $data_foto);
        DB::beginTransaction();
        try {
            $simpan = Pelanggan::create($data);
            if ($simpan) {
                if ($request->hasfile('foto')) {
                    $request->file('foto')->storeAs($destination_foto_path, $foto_name);
                }
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($kode_pelanggan)
    {
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $pelanggan = Pelanggan::where('kode_pelanggan', $kode_pelanggan)->first();
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $klasifikasi_outlet = Klasifikasioutlet::orderBy('kode_klasifikasi')->get();
        return view('datamaster.pelanggan.edit', compact('cabang', 'pelanggan', 'klasifikasi_outlet'));
    }

    public function update(Request $request, $kode_pelanggan)
    {
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);

        //dd($kode_pelanggan);
        $pelanggan = Pelanggan::where('kode_pelanggan', $kode_pelanggan)->first();



        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');

        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang;
            $request->validate([
                'nama_pelanggan' => 'required',
                'alamat_pelanggan' => 'required',
                'alamat_toko' => 'required',
                'kode_cabang' => 'required',
                'kode_salesman' => 'required',
                'kode_wilayah' => 'required',
                'hari' => 'required'
            ]);
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
            $request->validate([
                'nama_pelanggan' => 'required',
                'alamat_pelanggan' => 'required',
                'alamat_toko' => 'required',
                'kode_salesman' => 'required',
                'kode_wilayah' => 'required',
                'hari' => 'required'
            ]);
        }







        $data_foto = [];
        if ($request->hasfile('foto')) {
            $foto_name =  $kode_pelanggan . "." . $request->file('foto')->getClientOriginalExtension();
            $destination_foto_path = "/public/pelanggan";
            $foto = $foto_name;
            $data_foto = [
                'foto' => $foto
            ];
        }

        if (isset($request->lokasi)) {
            $lokasi = explode(",", $request->lokasi);
            $latitude = $lokasi[0];
            $longitude = $lokasi[1];
        } else {
            $latitude = NULL;
            $longitude = NULL;
        }

        if ($user->hasRole('super admin')) {
            $data_pelanggan = [
                'nik' => $request->nik,
                'no_kk' => $request->no_kk,
                'nama_pelanggan' => $request->nama_pelanggan,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat_pelanggan' => $request->alamat_pelanggan,
                'alamat_toko' => $request->alamat_toko,
                'no_hp_pelanggan' => $request->no_hp_pelanggan,
                'kode_cabang' => $kode_cabang,
                'kode_salesman' => $request->kode_salesman,
                'kode_wilayah' => $request->kode_wilayah,
                'hari' => implode(",", $request->hari),
                'limit_pelanggan' => isset($request->limit_pelanggan) ?  toNumber($request->limit_pelanggan) : NULL,
                'ljt' => $request->ljt,
                'kepemilikan' => $request->kepemilikan,
                'lama_berjualan' => $request->lama_berjualan,
                'status_outlet' => $request->status_outlet,
                'type_outlet' => $request->type_outlet,
                'kode_klasifikasi' => $request->kode_klasifikasi,
                'cara_pembayaran' => $request->cara_pembayaran,
                'lama_langganan' => $request->lama_langganan,
                'jaminan' => $request->jaminan,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'omset_toko' => isset($request->omset_toko) ?  toNumber($request->omset_toko) : NULL,
                'status_aktif_pelanggan' => $request->status_aktif_pelanggan,
            ];
        } else {
            $data_pelanggan = [
                'nik' => $request->nik,
                'no_kk' => $request->no_kk,
                'nama_pelanggan' => $request->nama_pelanggan,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat_pelanggan' => $request->alamat_pelanggan,
                'alamat_toko' => $request->alamat_toko,
                'no_hp_pelanggan' => $request->no_hp_pelanggan,
                'kode_cabang' => $kode_cabang,
                'kode_salesman' => $request->kode_salesman,
                'kode_wilayah' => $request->kode_wilayah,
                'hari' => implode(",", $request->hari),

                'ljt' => $request->ljt,
                'kepemilikan' => $request->kepemilikan,
                'lama_berjualan' => $request->lama_berjualan,
                'status_outlet' => $request->status_outlet,
                'type_outlet' => $request->type_outlet,
                'kode_klasifikasi' => $request->kode_klasifikasi,
                'cara_pembayaran' => $request->cara_pembayaran,
                'lama_langganan' => $request->lama_langganan,
                'jaminan' => $request->jaminan,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'omset_toko' => isset($request->omset_toko) ?  toNumber($request->omset_toko) : NULL,
                'status_aktif_pelanggan' => $request->status_aktif_pelanggan,
            ];
        }
        $data = array_merge($data_pelanggan, $data_foto);
        DB::beginTransaction();
        try {
            $simpan = Pelanggan::where('kode_pelanggan', $kode_pelanggan)->update($data);
            if ($simpan) {
                $image = $request->file('foto');
                if ($request->hasfile('foto')) {

                    Storage::delete($destination_foto_path . "/" . $pelanggan->foto);
                    $request->file('foto')->storeAs($destination_foto_path, $foto_name);
                }
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function destroy($kode_pelanggan)
    {
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        try {
            Pelanggan::where('kode_pelanggan', $kode_pelanggan)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function show(Request $request, $kode_pelanggan)
    {
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $pelanggan = Pelanggan::where('kode_pelanggan', $kode_pelanggan)
            ->leftjoin('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah')
            ->join('salesman', 'pelanggan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('cabang', 'pelanggan.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();
        $kepemilikan = config('pelanggan.kepemilikan');
        $lama_berjualan = config('pelanggan.lama_berjualan');
        $status_outlet = config('pelanggan.status_outlet');
        $type_outlet = config('pelanggan.type_outlet');
        $cara_pembayaran = config('pelanggan.cara_pembayaran');
        $lama_langganan = config('pelanggan.lama_langganan');

        $pj =  new Penjualan();
        $penjualan = $pj->getFakturbyPelanggan($request, $kode_pelanggan)->cursorPaginate(10);
        return view('datamaster.pelanggan.show', compact(
            'pelanggan',
            'kepemilikan',
            'lama_berjualan',
            'status_outlet',
            'type_outlet',
            'cara_pembayaran',
            'lama_langganan',
            'penjualan'
        ));
    }


    public function getPelanggan($kode_pelanggan)
    {
        $pelanggan = Pelanggan::select(
            'pelanggan.*',
            'nama_salesman',
            'nama_cabang'
        )
            ->join('salesman', 'pelanggan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang')
            ->where('kode_pelanggan', Crypt::decrypt($kode_pelanggan))->first();
        return response()->json([
            'success' => true,
            'message' => 'Detail Pelanggan',
            'data'    => $pelanggan
        ]);
    }

    public function getAvgpelanggan($kode_pelanggan, $kode_program)
    {
        $kode_program = Crypt::decrypt($kode_program);
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $programikatan = Ajuanprogramikatan::where('marketing_program_ikatan.kode_program', $kode_program)
            ->join('program_ikatan', 'marketing_program_ikatan.kode_program', '=', 'program_ikatan.kode_program')
            ->first();
        $bulan = date('m', strtotime($programikatan->periode_dari));
        $tahun = date('Y', strtotime($programikatan->periode_dari));
        $lastbulan = getbulandantahunlalu($bulan, $tahun, "bulan");


        $lasttahun = getbulandantahunlalu($bulan, $tahun, "tahun");
        $dari_lastbulan = $lasttahun . "-" . $lastbulan . "-01";


        $lastduabulan = getbulandantahunlalu($lastbulan, $lasttahun, "bulan");
        $lastduabulantahun = getbulandantahunlalu($lastbulan, $lasttahun, "tahun");

        $lastigabulan = getbulandantahunlalu($lastduabulan, $lastduabulantahun, "bulan");
        $lasttigabulantahun = getbulandantahunlalu($lastduabulan, $lastduabulantahun, "tahun");



        $dari_lasttigabulan = $lasttigabulantahun . "-" . $lastigabulan . "-01";
        $sampai_lastbulan = date('Y-m-t', strtotime($dari_lastbulan));


        $produk = json_decode($programikatan->produk, true) ?? [];
        $detailpenjualan = Detailpenjualan::join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->whereIn('produk_harga.kode_produk', $produk)
            ->where('marketing_penjualan.kode_pelanggan', $kode_pelanggan)
            ->whereBetween('marketing_penjualan.tanggal', [$dari_lasttigabulan, $sampai_lastbulan])
            ->where('status_promosi', 0)
            ->where('status_batal', 0)
            ->select(
                'marketing_penjualan.kode_pelanggan',
                'nama_pelanggan',
                DB::raw('SUM(FLOOR(marketing_penjualan_detail.jumlah / produk.isi_pcs_dus)) as qty'),
            )
            ->groupBy('marketing_penjualan.kode_pelanggan', 'nama_pelanggan')
            ->first();

        if ($detailpenjualan != null) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Pelanggan',
                'type'    => 1,
                'data'    => $detailpenjualan
            ]);
        } else {
            $pelanggan = Pelanggan::where('kode_pelanggan', $kode_pelanggan)->first();
            return response()->json([
                'success' => true,
                'message' => 'Detail Pelanggan',
                'type'    => 2,
                'data'    => $pelanggan
            ]);
        }
    }

    public function cekFotopelanggan(Request $request)
    {
        $filePath = $request->file;
        if (Storage::disk('public')->exists($filePath)) {
            return response()->json(['exists' => true]);
        } else {
            return response()->json(['exists' => false]);
        }
    }


    public function cekfoto()
    {
        dd(Storage::disk('public')->exists('/pelanggan/TSM-00700.jpg'));
    }

    public function getPiutangpelanggan($kode_pelanggan)
    {
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);

        $piutang = new Penjualan();
        $sisa_piutang = $piutang->getPiutangpelanggan($kode_pelanggan);

        return response()->json([
            'success' => true,
            'message' => 'Sisa Piutang',
            'data'    => $sisa_piutang
        ]);
    }

    public function getFakturkredit($kode_pelanggan)
    {
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $penjualan = new Penjualan();
        $faktur_kredit = $penjualan->getFakturkredit($kode_pelanggan);
        return response()->json([
            'success' => true,
            'message' => 'Jumlah Faktur Kredit Belum Lunas',
            'data'    => $faktur_kredit
        ]);
    }


    public function getPelangganjson(Request $request)
    {

        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        if ($request->ajax()) {
            $query = Pelanggan::query();
            $query->select(
                'pelanggan.*',
                'wilayah.nama_wilayah',
                'salesman.nama_salesman',
                DB::raw("IF(status_aktif_pelanggan=1,'Aktif','NonAktif') as status_pelanggan")
            );
            $query->join('salesman', 'pelanggan.kode_salesman', '=', 'salesman.kode_salesman');
            $query->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
            $query->join('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah');
            if (!$user->hasRole($roles_access_all_cabang)) {
                if ($user->hasRole('regional sales manager')) {
                    $query->where('cabang.kode_regional', auth()->user()->kode_regional);
                } else {
                    $query->where('pelanggan.kode_cabang', auth()->user()->kode_cabang);
                }
            }
            $pelanggan = $query;
            return DataTables::of($pelanggan)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="#" kode_pelanggan="' . Crypt::encrypt($row->kode_pelanggan) . '" class="pilihpelanggan"><i class="ti ti-external-link"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    public function getPelanggancabangjson(Request $request, $kode_cabang)
    {

        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        if ($request->ajax()) {
            $query = Pelanggan::query();
            $query->select(
                'pelanggan.*',
                'wilayah.nama_wilayah',
                'salesman.nama_salesman',
                DB::raw("IF(status_aktif_pelanggan=1,'Aktif','NonAktif') as status_pelanggan")
            );
            $query->join('salesman', 'pelanggan.kode_salesman', '=', 'salesman.kode_salesman');
            $query->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
            $query->join('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah');
            // if (!$user->hasRole($roles_access_all_cabang)) {
            //     if ($user->hasRole('regional sales manager')) {
            //         $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            //     } else {
            //         $query->where('pelanggan.kode_cabang', auth()->user()->kode_cabang);
            //     }
            // }
            $query->where('pelanggan.kode_cabang', $kode_cabang);
            $query->where('status_aktif_pelanggan', 1);
            $pelanggan = $query;
            return DataTables::of($pelanggan)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="#" kode_pelanggan="' . Crypt::encrypt($row->kode_pelanggan) . '" class="pilihpelanggan"><i class="ti ti-external-link"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    public function getlistFakturkredit($kode_pelanggan)
    {
        $pj = new Penjualan();
        $data['unpaidsales'] = $pj->getlistFakturkredit($kode_pelanggan)->get();
        return view('datamaster.pelanggan.getlistfakturkredit', $data);
    }


    public function getlistFakturkreditoption($kode_pelanggan)
    {
        $pj = new Penjualan();
        $unpaidsales = $pj->getlistFakturkredit($kode_pelanggan)->get();
        echo "<option value=''>Pilih Faktur</option>";
        foreach ($unpaidsales as $d) {
            echo "<option value='$d->no_faktur'>$d->no_faktur</option>";
        }
    }

    public function getpelangganbysalesman(Request $request)
    {

        $user = User::findorfail(auth()->user()->id);
        $kode_salesman = $request->kode_salesman;
        if (!$user->hasRole(config('global.roles_access_all_cabang'))) {
            $kode_cabang = $user->kode_cabang;
        } else {
            $kode_cabang = $request->kode_cabang;
        }

        $query = Pelanggan::query();
        $query->where('pelanggan.kode_cabang', $kode_cabang);

        if (!empty($kode_salesman)) {
            $query->where('pelanggan.kode_salesman', $kode_salesman);
        }
        $pelanggan = $query->get();
        echo "<option value=''>Semua Pelanggan</option>";
        foreach ($pelanggan as $d) {
            echo "<option value='$d->kode_pelanggan'>" . textUpperCase($d->nama_pelanggan) . "</option>";
        }
    }


    public function export(Request $request)
    {

        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang;
        }

        $query = Pelanggan::query();
        $query->leftjoin('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah');
        $query->join('salesman', 'pelanggan.kode_salesman', '=', 'salesman.kode_salesman');
        $query->join('cabang', 'pelanggan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftJoin('marketing_klasifikasi_outlet', 'pelanggan.kode_klasifikasi', '=', 'marketing_klasifikasi_outlet.kode_klasifikasi');
        if (!empty($kode_cabang)) {
            $query->where('salesman.kode_cabang', $kode_cabang);
        }
        if (!empty($request->kode_salesman)) {
            $query->where('pelanggan.kode_salesman', $request->kode_salesman);
        }

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('pelanggan.tanggal_register', [$request->dari, $request->sampai]);
        }

        if (!empty($request->status)) {
            if ($request->status == 'aktif') {
                $query->where('pelanggan.status_aktif_pelanggan', '1');
            } elseif ($request->status == 'nonaktif') {
                $query->where('pelanggan.status_aktif_pelanggand', '0');
            }
        }
        $pelanggan = $query->get();
        $kepemilikan = config('pelanggan.kepemilikan');
        $lama_berjualan = config('pelanggan.lama_berjualan');
        $status_outlet = config('pelanggan.status_outlet');
        $type_outlet = config('pelanggan.type_outlet');
        $cara_pembayaran = config('pelanggan.cara_pembayaran');
        $lama_langganan = config('pelanggan.lama_langganan');
        $cabang = Cabang::where('kode_cabang', $kode_cabang)->first();

        if (isset($_GET['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Data Pelanggan $request->dari-$request->sampai.xls");
        }
        return view('datamaster.pelanggan.export', compact(
            'pelanggan',
            'kepemilikan',
            'lama_berjualan',
            'status_outlet',
            'type_outlet',
            'cara_pembayaran',
            'lama_langganan',

            'cabang'
        ));
    }
}
