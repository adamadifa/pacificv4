<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailsaldoawalpiutangpelanggan;
use App\Models\Pelanggan;
use App\Models\Pengajuanfaktur;
use App\Models\Penjualan;
use App\Models\Saldoawalpiutangpelanggan;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

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

        $query->orderBy('tanggal_register', 'desc');

        $pelanggan = $query->paginate('30');
        $pelanggan->appends(request()->all());

        $plg = new Pelanggan();
        $jmlpelanggan = $plg->getJmlpelanggan($request);
        $jmlpelangganaktif = $plg->getJmlpelanggan($request, 1);
        $jmlpelanggannonaktif = $plg->getJmlpelanggan($request, 0);


        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
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
        return view('datamaster.pelanggan.create', compact('cabang'));
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




        $lastpelanggan = Pelanggan::where('kode_cabang', $kode_cabang)
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

        if (isset($request->lokasi)) {
            $lokasi = explode(",", $request->lokasi);
            $latitude = $lokasi[0];
            $longitude = $lokasi[1];
        } else {
            $latitude = NULL;
            $longitude = NULL;
        }

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
            'hari' => $request->hari,
            'limit_pelanggan' => isset($request->limit_pelanggan) ?  toNumber($request->limit_pelanggan) : NULL,
            'ljt' => $request->ljt,
            'kepemilikan' => $request->kepemilikan,
            'lama_berjualan' => $request->lama_berjualan,
            'status_outlet' => $request->status_outlet,
            'type_outlet' => $request->type_outlet,
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
        return view('datamaster.pelanggan.edit', compact('cabang', 'pelanggan'));
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
            'hari' => $request->hari,
            'limit_pelanggan' => isset($request->limit_pelanggan) ?  toNumber($request->limit_pelanggan) : NULL,
            'ljt' => $request->ljt,
            'kepemilikan' => $request->kepemilikan,
            'lama_berjualan' => $request->lama_berjualan,
            'status_outlet' => $request->status_outlet,
            'type_outlet' => $request->type_outlet,
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
            $simpan = Pelanggan::where('kode_pelanggan', $kode_pelanggan)->update($data);
            if ($simpan) {
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
            'pelanggan.kode_pelanggan',
            'nama_pelanggan',
            'pelanggan.kode_salesman',
            'nama_salesman',
            'no_hp_pelanggan',
            'latitude',
            'longitude',
            'limit_pelanggan',
            'foto',
            'alamat_pelanggan'
        )
            ->join('salesman', 'pelanggan.kode_salesman', '=', 'salesman.kode_salesman')
            ->where('kode_pelanggan', Crypt::decrypt($kode_pelanggan))->first();
        return response()->json([
            'success' => true,
            'message' => 'Detail Pelanggan',
            'data'    => $pelanggan
        ]);
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
        $hari_ini = date('Y-m-d');

        $sa = Saldoawalpiutangpelanggan::orderBy('tanggal', 'desc')->first();
        $start_date = $sa != null ? $sa->tanggal : date('Y') . "-01-01";
        $kode_saldo_awal = $sa != null ? $sa->kode_saldo_awal : null;

        $saldo_awal = Detailsaldoawalpiutangpelanggan::select(
            'marketing_penjualan.kode_pelanggan',
            DB::raw('SUM(jumlah) as jumlah')
        )
            ->join('marketing_penjualan', 'marketing_saldoawal_piutang_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->where('marketing_penjualan.kode_pelanggan', $kode_pelanggan)
            ->where('kode_saldo_awal', $kode_saldo_awal)
            ->groupBy('marketing_penjualan.kode_pelanggan')
            ->first();

        $saldo = $saldo_awal != null ? $saldo_awal->jumlah : 0;
        $end_date = date('Y-m-t', strtotime($hari_ini));

        $pj = new Penjualan();
        $penjualan = $pj->getPenjualanpelangganbydate($start_date, $end_date, $kode_pelanggan)->first();
        $sisa_piutang = $saldo + $penjualan->total_bruto -  $penjualan->total_potongan + $penjualan->total_ppn - $penjualan->total_retur - $penjualan->total_bayar;


        return response()->json([
            'success' => true,
            'message' => 'Sisa Piutang',
            'data'    => $sisa_piutang
        ]);
    }

    public function getFakturkredit($kode_pelanggan)
    {
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $ajuanfaktur = Pengajuanfaktur::where('kode_pelanggan', $kode_pelanggan)
            ->where('status', 1)
            ->orderBy('tanggal', 'desc')
            ->first();
        $jml_faktur = $ajuanfaktur != null ? $ajuanfaktur->jumlah_faktur : 1;
        $siklus_pembayaran = $ajuanfaktur != null ? $ajuanfaktur->siklus_pembayaran : 0;

        // Subquery untuk total penjualan bruto
        $subqueryTotalBruto = DB::table('marketing_penjualan_detail')
            ->select('marketing_penjualan_detail.no_faktur', DB::raw('SUM(subtotal) as total_bruto'))
            ->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->where('kode_pelanggan', $kode_pelanggan)
            ->groupBy('no_faktur');

        // Subquery untuk total retur
        $subqueryTotalRetur = DB::table('marketing_retur_detail')
            ->select('marketing_retur.no_faktur', DB::raw('SUM(subtotal) as total_retur'))
            ->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur')
            ->join('marketing_penjualan', 'marketing_retur.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->where('kode_pelanggan', $kode_pelanggan)
            ->groupBy('no_faktur');

        // Subquery untuk total pembayaran
        $subqueryTotalPembayaran = DB::table('marketing_penjualan_historibayar')
            ->select('marketing_penjualan_historibayar.no_faktur', DB::raw('SUM(jumlah) as total_pembayaran'))
            ->join('marketing_penjualan', 'marketing_penjualan_historibayar.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->where('kode_pelanggan', $kode_pelanggan)
            ->groupBy('no_faktur');

        $unpaidSales = Penjualan::select(
            'marketing_penjualan.no_faktur',
            'bruto.total_bruto',
            'retur.total_retur',
            'potongan',
            'potongan_istimewa',
            'penyesuaian',
            'ppn',
            'pembayaran.total_pembayaran'
        )
            ->selectRaw('COALESCE(bruto.total_bruto, 0) - COALESCE(retur.total_retur, 0) - COALESCE(potongan, 0) - COALESCE(potongan_istimewa, 0) - COALESCE(penyesuaian, 0) + COALESCE(ppn, 0) - COALESCE(pembayaran.total_pembayaran, 0) as sisa_piutang')
            ->leftJoinSub($subqueryTotalBruto, 'bruto', 'marketing_penjualan.no_faktur', '=', 'bruto.no_faktur')
            ->leftJoinSub($subqueryTotalRetur, 'retur', 'marketing_penjualan.no_faktur', '=', 'retur.no_faktur')
            ->leftJoinSub($subqueryTotalPembayaran, 'pembayaran', 'marketing_penjualan.no_faktur', '=', 'pembayaran.no_faktur')
            ->where('kode_pelanggan', $kode_pelanggan)
            ->havingRaw('sisa_piutang > 0')
            ->count();
        // $faktur_kredit = Penjualan::addSelect(DB::raw('(SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) as total_bruto'))
        //     ->addSelect(DB::raw('(SELECT SUM(subtotal) FROM marketing_retur_detail
        //     INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur
        //     WHERE no_faktur = marketing_penjualan.no_faktur AND jenis_retur="PF") as total_retur'))

        //     ->addSelect(DB::raw('(SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE no_faktur = marketing_penjualan.no_faktur) as total_bayar'))
        //     ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
        //     ->where('marketing_penjualan.kode_pelanggan', $kode_pelanggan)
        //     ->where('jenis_transaksi', 'K')
        //     ->where('total_bruto', '>=', '1000000')
        //     ->count();

        return response()->json([
            'success' => true,
            'message' => 'Jumlah Faktur Kredit Belum Lunas',
            'data'    => $unpaidSales
        ]);
    }
}
