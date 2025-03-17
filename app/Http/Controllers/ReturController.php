<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailretur;
use App\Models\Harga;
use App\Models\Historibayarpenjualan;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Retur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class ReturController extends Controller
{
    public function index(Request $request)
    {

        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }
        $rtr = new Retur();
        $retur = $rtr->getRetur($request, $no_retur = "")->cursorPaginate();
        $retur->appends(request()->all());
        $data['retur'] = $retur;


        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('marketing.retur.index', $data);
    }

    public function create()
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        if (request()->ajax()) {
            $query = Pelanggan::query();
            $query->select(
                'pelanggan.*',
                'wilayah.nama_wilayah',
                'salesman.nama_salesman',
                DB::raw("IF(status_aktif_pelanggan=1,'Aktif','NonAktif') as status_pelanggan")
            );
            $query->join('salesman', 'pelanggan.kode_salesman', '=', 'salesman.kode_salesman');
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
                ->addColumn('action', function ($item) {
                    $button =   '<a href="#" kode_pelanggan="' . Crypt::encrypt($item->kode_pelanggan) . '" class="pilihpelanggan"><i class="ti ti-external-link"></i></a>';
                    return $button;
                })
                ->make();
        }


        return view('marketing.retur.create');
    }

    public function editproduk(Request $request)
    {
        $dataproduk = $request->dataproduk;
        $data['dataproduk'] = $dataproduk;

        $hrg = new Harga();
        $data['harga'] = $hrg->getHargabypelanggan($dataproduk['kode_pelanggan']);
        return view('marketing.retur.editproduk', $data);
    }

    public function store(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $request->validate([
            'tanggal' => 'required',
            'kode_pelanggan' => 'required',
            'kode_salesman' => 'required',
            'jenis_retur' => 'required',
            'no_faktur' => 'required'
        ]);

        $tanggal_retur = substr(date('Y', strtotime($request->tanggal)), 2, 2) . date('m', strtotime($request->tanggal)) . date('d', strtotime($request->tanggal));
        //Detail Produk
        $kode_harga = $request->kode_harga_produk;
        $isi_pcs_dus = $request->isi_pcs_dus_produk;
        $isi_pcs_pack = $request->isi_pcs_pack_produk;
        $hargadus = $request->harga_dus_produk;
        $hargapack = $request->harga_pack_produk;
        $hargapcs = $request->harga_pcs_produk;
        $jumlah = $request->jumlah_produk;
        $faktur = Penjualan::where('no_faktur', $request->no_faktur)->first();
        $historibayar_log = [];
        DB::beginTransaction();
        try {

            $cektutuplaporan = cektutupLaporan($request->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            //Generate No. Retur
            $lastretur = Retur::select('no_retur')
                ->where('tanggal', $request->tanggal)
                ->whereRaw('LEFT(no_retur,1) = "R"')
                ->orderBy('no_retur', 'desc')
                ->first();

            $last_no_retur = $lastretur != null ? $lastretur->no_retur : '';
            $no_retur = buatkode($last_no_retur, 'R' . $tanggal_retur, 3);

            $simpanretur = Retur::create([
                'no_retur' => $no_retur,
                'tanggal' => $request->tanggal,
                'no_faktur' => $request->no_faktur,
                'no_ref' => $request->no_retur,
                'jenis_retur' => $request->jenis_retur,
                'id_user' => auth()->user()->id
            ]);

            $total = 0;
            for ($i = 0; $i < count($kode_harga); $i++) {

                $jml = convertToduspackpcsv3($isi_pcs_dus[$i], $isi_pcs_pack[$i], $jumlah[$i]);
                $jml_dus = $jml[0];
                $jml_pack = $jml[1];
                $jml_pcs = $jml[2];
                $harga_dus = toNumber($hargadus[$i]);
                $harga_pack = toNumber($hargapack[$i]);
                $harga_pcs = toNumber($hargapcs[$i]);
                $subtotal = ($jml_dus * $harga_dus) + ($jml_pack * $harga_pack) + ($jml_pcs * $harga_pcs);
                $total += $subtotal;
                $detail[] = [
                    'no_retur' => $no_retur,
                    'kode_harga' => $kode_harga[$i],
                    'jumlah' => $jumlah[$i],
                    'harga_dus' => $harga_dus,
                    'harga_pack' => $harga_pack,
                    'harga_pcs' => $harga_pcs,
                    'subtotal' => $subtotal,
                ];
            }
            if ($request->jenis_retur == "PF") {

                if ($faktur->jenis_bayar == "TN") {
                    $cekbayar = Historibayarpenjualan::where('no_faktur', $request->no_faktur)
                        ->where('voucher', 0)
                        ->where('tanggal', $faktur->tanggal)
                        ->orderBy('no_bukti')
                        ->first();
                    $jumlah = $cekbayar->jumlah - $total;

                    $oldbayar = $cekbayar->getAttributes();
                    if ($cekbayar != null) {
                        $cekbayar->update([
                            'jumlah' => $jumlah
                        ]);
                        $historibayar_log = [
                            'old' => $oldbayar,
                            'new' => $cekbayar->getAttributes(),
                        ];
                    }
                }
            }
            Detailretur::insert($detail);

            activity('retur')
                ->event('create')
                ->performedOn($simpanretur)
                ->withProperties([
                    'penjualan' => $simpanretur->getAttributes(),
                    'detail' => $detail, // Menyertakan detail produk ke dalam log
                    'historibayar' => $historibayar_log
                ])
                ->log("Membuat Retur {$simpanretur->no_faktur}  dengan " . count($detail) . " item.");

            DB::commit();
            if ($user->hasRole('salesman')) {
                return redirect(route('sfa.showpelanggan', Crypt::encrypt($faktur->kode_pelanggan)))->with(messageSuccess('Data Berhasil Disimpan'));
            } else {
                return redirect(route('penjualan.show', Crypt::encrypt($request->no_faktur)))->with(messageSuccess('Data Berhasil Disimpan'));
            }
        } catch (\Exception $e) {
            //dd($e);
            DB::rollBack();
            if ($user->hasRole('salesman')) {
                return redirect(route('sfa.showpelanggan', Crypt::encrypt($faktur->kode_pelanggan)))->with(messageSuccess('Data Berhasil Disimpan'));
            } else {
                return Redirect::back()->with(messageError($e->getMessage()));
            }
        }
    }

    public function show($no_retur)
    {
        $no_retur = Crypt::decrypt($no_retur);
        $rtr = new Retur();
        $retur = $rtr->getRetur($request = null, $no_retur)->first();
        $data['retur'] = $retur;
        $data['detail'] = $rtr->getDetailretur($no_retur);
        return view('marketing.retur.show', $data);
    }


    public function destroy($no_retur)
    {
        $no_retur = Crypt::decrypt($no_retur);

        $retur = Retur::where('no_retur', $no_retur)
            ->select('no_retur', 'marketing_retur.no_faktur', 'marketing_retur.tanggal', 'kode_pelanggan', 'jenis_retur')
            ->join('marketing_penjualan', 'marketing_retur.no_faktur', '=', 'marketing_penjualan.no_faktur')->first();
        $detail = Detailretur::where('no_retur', $no_retur)->get()->toArray();

        $detailretur = Detailretur::select(DB::raw("SUM(subtotal) as total_retur"))->where('no_retur', $no_retur)->first();

        $user = User::findorfail(auth()->user()->id);
        $historibayar_log = [];
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($retur->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            if ($retur->jenis_retur == "PF") {
                $faktur = Penjualan::where('no_faktur', $retur->no_faktur)->first();
                if ($faktur->jenis_bayar == "TN") {
                    $cekbayar = Historibayarpenjualan::where('no_faktur', $retur->no_faktur)
                        ->where('voucher', 0)
                        ->where('tanggal', $faktur->tanggal)
                        ->orderBy('no_bukti')
                        ->first();

                    $jumlah = $cekbayar->jumlah + $detailretur->total_retur;
                    $oldbayar = $cekbayar->getAttributes();

                    if ($cekbayar != null) {
                        $cekbayar->update([
                            'jumlah' => $jumlah
                        ]);



                        $historibayar_log = [
                            'old' => $oldbayar,
                            'new' => $cekbayar->getAttributes(),
                        ];
                    }
                }
            }
            //Hapus Surat Jalan
            Retur::where('no_retur', $no_retur)->delete();

            //Catat Activity
            activity('retur')
                ->event('delete')
                ->performedOn($retur)
                ->withProperties([
                    'retur' => $retur->getAttributes(),
                    'detail' => $detail,
                    'historibayar' => $historibayar_log,
                ])
                ->log("Menghapus  Retur {$retur->no_retur}" . "No Faktur {$retur->no_faktur}  dengan " . count($detail) . " item.");
            DB::commit();
            if ($user->hasRole('salesman')) {
                return redirect('/sfa/pelanggan/' . Crypt::encrypt($retur->kode_pelanggan) . '/show')->with(messageSuccess('Data Berhasil Dihapus'));
            }
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            if ($user->hasRole('salesman')) {
                return redirect('/sfa/pelanggan/' . Crypt::encrypt($retur->kode_pelanggan) . '/show')->with(messageError($e->getMessage()));
            }
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
