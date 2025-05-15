<?php

namespace App\Http\Controllers;

use App\Models\Ajuanfakturkredit;
use App\Models\Ajuanlimitkredit;
use App\Models\AktifitasSMM;
use App\Models\Cabang;
use App\Models\Checkinpenjualan;
use App\Models\Detailgiro;
use App\Models\Detailpenjualan;
use App\Models\Detailretur;
use App\Models\Detailtransfer;
use App\Models\Diskon;
use App\Models\Disposisiajuanfaktur;
use App\Models\Disposisiajuanlimitkredit;
use App\Models\Harga;
use App\Models\Historibayarpenjualan;
use App\Models\Klasifikasioutlet;
use App\Models\Pelanggan;
use App\Models\Pengajuanfaktur;
use App\Models\Penjualan;
use App\Models\Retur;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\RawbtPrintConnector;
use Mike42\Escpos\Printer;
use Yajra\DataTables\Facades\DataTables;

class item
{
    private $name;
    private $price;
    private $dollarSign;

    public function __construct($name = '', $price = '', $dollarSign = false)
    {
        $this->name = $name;
        $this->price = $price;
        $this->dollarSign = $dollarSign;
    }

    public function getAsString($width = 48)
    {
        $rightCols = 10;
        $leftCols = $width - $rightCols;
        if ($this->dollarSign) {
            $leftCols = $leftCols / 2 - $rightCols / 2;
        }
        $left = str_pad($this->name, $leftCols);

        $sign = ($this->dollarSign ? '$ ' : '');
        $right = str_pad($sign . $this->price, $rightCols, ' ', STR_PAD_LEFT);
        return "$left$right\n";
    }

    public function __toString()
    {
        return $this->getAsString();
    }
}

class SfaControler extends Controller
{

    public function dashboard(Request $request)
    {
        $user = User::findorFail(auth()->user()->id);
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');
        $start_date = $tahun . "-" . $bulan . "-01";
        $dari = $tahun . "-" . $bulan . "-01";
        $sampai = date("Y-m-t", strtotime($dari));
        $i = 1;
        $select_date_activity = [];
        $field_date_activity = [];
        while (strtotime($dari) <= strtotime($sampai)) {
            $select_date_activity[] = DB::raw("SUM(IF(DATE(tanggal)='" . $dari . "',1,0)) as tgl_" . $i);
            $field_date_activity[] = "tgl_" . $i;
            $i++;
            $dari = date("Y-m-d", strtotime("+1 day", strtotime($dari)));
        }
        $activity = AktifitasSMM::select('id_user', ...$select_date_activity)
            ->whereBetween('tanggal', [$start_date, $sampai])
            ->groupBy('id_user');


        $users = User::select('id', 'name', 'users.kode_cabang', ...$field_date_activity)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'sales marketing manager');
            })
            ->join('cabang', 'users.kode_cabang', '=', 'cabang.kode_cabang')
            ->when($user->hasRole('regional sales manager'), function ($query) use ($user) {
                return $query->where('cabang.kode_regional', $user->kode_regional);
            })
            ->leftjoinSub($activity, 'activity', function ($join) {
                $join->on('users.id', '=', 'activity.id_user');
            })
            ->where('users.status', 1)
            ->orderBy('name')
            ->get();


        $rsm = User::select('id', 'name', 'users.kode_cabang', 'nama_regional', ...$field_date_activity)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'regional sales manager');
            })
            ->leftjoinSub($activity, 'activity', function ($join) {
                $join->on('users.id', '=', 'activity.id_user');
            })

            ->join('regional', 'users.kode_regional', '=', 'regional.kode_regional')
            ->where('users.status', 1)
            ->orderBy('name')
            ->get();

        $gm = User::select('id', 'name', 'users.kode_cabang', 'nama_regional', ...$field_date_activity)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'gm marketing');
            })
            ->leftjoinSub($activity, 'activity', function ($join) {
                $join->on('users.id', '=', 'activity.id_user');
            })

            ->join('regional', 'users.kode_regional', '=', 'regional.kode_regional')
            ->where('users.status', 1)
            ->orderBy('name')
            ->get();

        $data['users']  = $users;
        $data['user']  = $user;
        $data['rsm']  = $rsm;
        $data['gm']  = $gm;
        $data['start_date'] = $start_date;
        $data['end_date'] = $sampai;
        $data['jmlhari'] = $i - 1;
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('sfa.dashboard', $data);
    }
    public function pelanggan()
    {

        return view('sfa.pelanggan');
    }

    public function createpelanggan()
    {
        $klasifikasi_outlet = Klasifikasioutlet::orderBy('kode_klasifikasi')->get();
        return view('sfa.pelanggan_create', compact('klasifikasi_outlet'));
    }


    public function storepelanggan(Request $request)
    {


        $user = User::findorFail(auth()->user()->id);
        $kode_cabang = auth()->user()->kode_cabang;
        $request->validate([
            'nama_pelanggan' => 'required',
            'alamat_pelanggan' => 'required',
            'alamat_toko' => 'required',
            'kode_wilayah' => 'required',
            'hari' => 'required'
        ]);




        $lastpelanggan = pelanggan::whereraw('left(kode_pelanggan,3)="' . $kode_cabang . '"')
            ->orderby('kode_pelanggan', 'desc')
            ->first();
        $last_kode_pelanggan = $lastpelanggan->kode_pelanggan;
        $kode_pelanggan =  buatkode($last_kode_pelanggan, $kode_cabang . '-', 5);

        // dd($lastpelanggan . " " . $kode_pelanggan);

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
            'kode_cabang' => $user->kode_cabang,
            'kode_salesman' => $user->kode_salesman,
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
            'status_aktif_pelanggan' => 1,
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
            return redirect(route('sfa.pelanggan'))->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect(route('sfa.pelanggan'))->with(messageError($e->getMessage()));
        }
    }


    public function editpelanggan($kode_pelanggan)
    {
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $pelanggan = Pelanggan::where('kode_pelanggan', $kode_pelanggan)->first();
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $klasifikasi_outlet = Klasifikasioutlet::orderBy('kode_klasifikasi')->get();
        return view('sfa.pelanggan_edit', compact('cabang', 'pelanggan', 'klasifikasi_outlet'));
    }

    public function updatepelanggan(Request $request, $kode_pelanggan)
    {
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);

        //dd($kode_pelanggan);
        $pelanggan = Pelanggan::where('kode_pelanggan', $kode_pelanggan)->first();




        $request->validate([
            'nama_pelanggan' => 'required',
            'alamat_pelanggan' => 'required',
            'alamat_toko' => 'required',
            'kode_wilayah' => 'required',
            'hari' => 'required'
        ]);







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
            'kode_wilayah' => $request->kode_wilayah,
            'hari' => $request->hari,
            // 'limit_pelanggan' => isset($request->limit_pelanggan) ?  toNumber($request->limit_pelanggan) : NULL,
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

    public function showpelanggan($kode_pelanggan)
    {

        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $value = Cookie::get('kodepelanggan');
        //dd($value);
        $pelanggan = Pelanggan::select(
            'pelanggan.*',
            'nama_salesman',
            'nama_cabang'
        )
            ->join('salesman', 'pelanggan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang')
            ->where('kode_pelanggan', $kode_pelanggan)->first();
        $data['pelanggan'] = $pelanggan;

        $data['ajuanfaktur'] = Pengajuanfaktur::where('kode_pelanggan', $kode_pelanggan)
            ->where('status', 1)
            ->orderBy('tanggal', 'desc')
            ->first();
        $data['fakturkredit'] = Penjualan::where('kode_pelanggan', $kode_pelanggan)
            ->where('status', 0)
            ->where('status_batal', 0)
            ->where('jenis_transaksi', 'kredit')
            ->count();

        $hariini = date('Y-m-d');
        $data['checkin'] = Checkinpenjualan::where('tanggal', $hariini)
            ->where('kode_salesman', auth()->user()->kode_salesman)
            ->where('kode_pelanggan', $kode_pelanggan)
            ->count();
        return view('sfa.pelanggan_show', $data);
    }


    public function checkinstore(Request $request)
    {

        //$getcookie =  Cookie::get('kodepelanggan');
        $kode_salesman = auth()->user()->kode_salesman;
        $currentLocation = $request->lokasi;
        $lokasiCheckin = explode(",", $currentLocation);
        $latitudeCheckin = $lokasiCheckin[0];
        $longitudeCheckin = $lokasiCheckin[1];

        // echo $latitude . "," . $longitude;
        // die;
        $kode_pelanggan = $request->kode_pelanggan;
        $hariini = date("Y-m-d");

        $tglskrg = date("d");
        $bulanskrg = date("m");
        $tahunskrg = date("y");

        $format = $tahunskrg . $bulanskrg . $tglskrg;
        $checkin = Checkinpenjualan::where('tanggal', $hariini)
            ->orderBy("kode_checkin", "desc")
            ->first();
        $last_kode_checkin = $checkin != null ? $checkin->kode_checkin : '';
        $kode_checkin  = buatkode($last_kode_checkin, $format, 4);

        //Cek Apkah Sudah Checkin Di Pelanggan Hari Ini
        $check = Checkinpenjualan::where('tanggal', $hariini)->where('kode_salesman', auth()->user()->kode_salesman)
            ->where('kode_pelanggan', $kode_pelanggan)->count();

        $pelanggan = Pelanggan::where('kode_pelanggan', $kode_pelanggan)->first();
        $status_lokasi = $pelanggan->status_lokasi;
        $latitude_pelanggan = $status_lokasi == 1 ? $pelanggan->latitude : $latitudeCheckin;
        $longitude_pelanggan = $status_lokasi == 1 ? $pelanggan->longitude : $longitudeCheckin;

        // $jarak = $this->distance($latitude_pelanggan, $longitude_pelanggan, $latitude, $longitude);
        // $radius =  ROUND($jarak["meters"]);
        DB::beginTransaction();
        try {
            if (empty($status_lokasi)) {
                Pelanggan::where('kode_pelanggan', $kode_pelanggan)->update([
                    'latitude' => $latitudeCheckin,
                    'longitude' => $longitudeCheckin,
                    'status_lokasi' => 1
                ]);
            }

            if ($check == 0) {
                $data = [
                    'kode_checkin' => $kode_checkin,
                    'tanggal' => $hariini,
                    'kode_salesman' => $kode_salesman,
                    'kode_pelanggan' => $kode_pelanggan,
                    'checkin_time' => date('Y-m-d H:i:s'),
                    'latitude' => $latitudeCheckin,
                    'longitude' => $longitudeCheckin,
                ];

                Checkinpenjualan::create($data);
                Cookie::queue(Cookie::forever('kodepelanggan', Crypt::encrypt($request->kode_pelanggan)));
                DB::commit();
            } else {
                Cookie::queue(Cookie::forever('kodepelanggan', Crypt::encrypt($request->kode_pelanggan)));
            }
            return response()->json([
                'success' => true,
                'message' => 'Terimakasih Sudah Melakukan Checkin',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal Checkin ' . $e->getMessage(),
            ]);
        }
    }


    public function checkout($kode_pelanggan)
    {
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $hariini = date("Y-m-d");
        $cek = Checkinpenjualan::where('kode_pelanggan', $kode_pelanggan)->where('tanggal', $hariini)->first();
        $kode_checkin = $cek->kode_checkin;
        $checkout_time = date("Y-m-d H:i:s");
        try {
            Checkinpenjualan::where('kode_checkin', $kode_checkin)->update([
                'checkout_time' => $checkout_time
            ]);
            Cookie::queue(Cookie::forget('kodepelanggan'));
            return redirect('/sfa/pelanggan');
        } catch (\Exception $e) {
            return redirect('/sfa/pelanggan')->with(messageError($e->getMessage()));
        }
    }


    public function capture($kode_pelanggan)
    {
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $pelanggan = Pelanggan::where('kode_pelanggan', $kode_pelanggan)->first();
        return view('sfa.pelanggan_capture', compact('pelanggan'));
    }

    public function storepelanggancapture(Request $request)
    {

        $lokasi = explode(",", $request->lokasi);
        $latitude = $lokasi[0];
        $longitude = $lokasi[1];
        $img = $request->image;
        $folderPath = "public/pelanggan/";

        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];

        $image_base64 = base64_decode($image_parts[1]);
        $fileName =  $request->kode_pelanggan . '.png';

        $file = $folderPath . $fileName;
        $data = [
            'foto' => $fileName,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'status_lokasi' => 1
        ];
        try {
            Pelanggan::where('kode_pelanggan', $request->kode_pelanggan)->update($data);
            if (Storage::exists($file)) {
                Storage::delete($file);
            }
            Storage::put($file, $image_base64);
            return response()->json([
                'success' => true,
                'message' => 'Foto Berhasil Disimpan',
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }


    public function showpenjualan($no_faktur)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        $data['jenis_bayar'] = config('penjualan.jenis_bayar');
        $pnj = new Penjualan();
        $penjualan = $pnj->getFaktur($no_faktur);
        // dd($penjualan);
        $data['penjualan'] = $penjualan;

        $detailpenjualan = new Penjualan();
        $data['detail'] = $detailpenjualan->getDetailpenjualan($no_faktur);

        $data['retur'] = Detailretur::select(
            'tanggal',
            'marketing_retur_detail.*',
            'jenis_retur',
            'produk_harga.kode_produk',
            'nama_produk',
            'isi_pcs_dus',
            'isi_pcs_pack',
            'subtotal'
        )
            ->join('produk_harga', 'marketing_retur_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur')
            ->where('no_faktur', $no_faktur)
            ->get();

        $data['historibayar'] = Historibayarpenjualan::select(
            'marketing_penjualan_historibayar.*',
            'nama_salesman',
            'marketing_penjualan_historibayar_giro.kode_giro',
            'no_giro',
            'giro_to_cash',
            'nama_voucher'
        )

            ->leftJoin('jenis_voucher', 'marketing_penjualan_historibayar.jenis_voucher', '=', 'jenis_voucher.id')
            ->leftJoin('marketing_penjualan_historibayar_giro', 'marketing_penjualan_historibayar.no_bukti', '=', 'marketing_penjualan_historibayar_giro.no_bukti')
            ->leftJoin('marketing_penjualan_giro', 'marketing_penjualan_historibayar_giro.kode_giro', '=', 'marketing_penjualan_giro.kode_giro')
            ->join('salesman', 'marketing_penjualan_historibayar.kode_salesman', '=', 'salesman.kode_salesman')
            ->where('no_faktur', $no_faktur)
            ->orderBy('created_at', 'desc')
            ->get();

        $data['giro'] = Detailgiro::select(
            'no_giro',
            'marketing_penjualan_giro.tanggal',
            'bank_pengirim',
            'marketing_penjualan_giro_detail.*',
            'jatuh_tempo',
            'status',
            'tanggal_ditolak',
            'keterangan',
            'historibayargiro.tanggal as tanggal_diterima',
            // 'marketing_penjualan_historibayar_giro.no_bukti as no_bukti_giro',
            'nama_salesman'
        )
            ->join('marketing_penjualan_giro', 'marketing_penjualan_giro_detail.kode_giro', '=', 'marketing_penjualan_giro.kode_giro')
            ->join('salesman', 'marketing_penjualan_giro.kode_salesman', '=', 'salesman.kode_salesman')
            ->leftJoin(
                DB::raw("(
                    SELECT kode_giro,marketing_penjualan_historibayar_giro.no_bukti,tanggal
                    FROM marketing_penjualan_historibayar_giro
                    INNER JOIN marketing_penjualan_historibayar ON marketing_penjualan_historibayar_giro.no_bukti = marketing_penjualan_historibayar.no_bukti
                    WHERE marketing_penjualan_historibayar.no_faktur = '$no_faktur'
                ) historibayargiro"),
                function ($join) {
                    $join->on('marketing_penjualan_giro_detail.kode_giro', '=', 'historibayargiro.kode_giro');
                }
            )
            ->where('marketing_penjualan_giro_detail.no_faktur', $no_faktur)
            ->orderBy('marketing_penjualan_giro.tanggal', 'desc')
            ->get();

        $data['transfer'] = Detailtransfer::select(
            'marketing_penjualan_transfer_detail.*',
            'marketing_penjualan_transfer.tanggal',
            'bank_pengirim',
            'jatuh_tempo',
            'status',
            'tanggal_ditolak',
            'keterangan',
            'historibayartransfer.tanggal as tanggal_diterima',
            'nama_salesman'
        )
            ->join('marketing_penjualan_transfer', 'marketing_penjualan_transfer_detail.kode_transfer', '=', 'marketing_penjualan_transfer.kode_transfer')
            ->join('salesman', 'marketing_penjualan_transfer.kode_salesman', '=', 'salesman.kode_salesman')
            ->leftJoin(
                DB::raw("(
                    SELECT kode_transfer,marketing_penjualan_historibayar_transfer.no_bukti,tanggal
                    FROM marketing_penjualan_historibayar_transfer
                    INNER JOIN marketing_penjualan_historibayar ON marketing_penjualan_historibayar_transfer.no_bukti = marketing_penjualan_historibayar.no_bukti
                    WHERE marketing_penjualan_historibayar.no_faktur = '$no_faktur'
                ) historibayartransfer"),
                function ($join) {
                    $join->on('marketing_penjualan_transfer_detail.kode_transfer', '=', 'historibayartransfer.kode_transfer');
                }
            )
            ->where('marketing_penjualan_transfer_detail.no_faktur', $no_faktur)
            ->orderBy('marketing_penjualan_transfer.tanggal', 'desc')
            ->get();


        //dd($data['detail']);
        $data['checkin'] = Checkinpenjualan::where('tanggal', $penjualan->tanggal)->where('kode_pelanggan', $penjualan->kode_pelanggan)->first();

        $data['print_tagihan'] = Historibayarpenjualan::where('no_faktur', $no_faktur)
            ->where('tanggal', '>', '2024-10-20')
            ->where('print_tagihan', 0)->count();

        $data['print_giro'] = Detailgiro::where('no_faktur', $no_faktur)
            ->join('marketing_penjualan_giro', 'marketing_penjualan_giro_detail.kode_giro', '=', 'marketing_penjualan_giro.kode_giro')
            ->where('print_giro', 0)
            ->count();

        $data['print_transfer'] = Detailtransfer::where('no_faktur', $no_faktur)
            ->join('marketing_penjualan_transfer', 'marketing_penjualan_transfer_detail.kode_transfer', '=', 'marketing_penjualan_transfer.kode_transfer')
            ->where('print_transfer', 0)
            ->count();

        // dd($data['print_tagihan']);
        return view('sfa.penjualan_show', $data);
    }

    public function cetakfaktur($no_faktur)
    {
        $no_faktur = Crypt::decrypt($no_faktur);

        //Update Jumlah Print


        //Data Faktur
        $pnj = new Penjualan();
        $penjualan = $pnj->getFaktur($no_faktur);
        $faktur = $penjualan;
        // if ($faktur->jenis_transaksi == 'K' && $faktur->print == 0) {
        //     dd('OK');
        // }
        //Detail Faktur
        $detailpenjualan = new Penjualan();
        $detail = $detailpenjualan->getDetailpenjualan($no_faktur);


        //Pembayaran
        $pembayaran = Historibayarpenjualan::select(
            'marketing_penjualan_historibayar.*',
            'nama_salesman',
            'marketing_penjualan_historibayar_giro.kode_giro',
            'no_giro',
            'giro_to_cash',
            'nama_voucher'
        )

            ->leftJoin('jenis_voucher', 'marketing_penjualan_historibayar.jenis_voucher', '=', 'jenis_voucher.id')
            ->leftJoin('marketing_penjualan_historibayar_giro', 'marketing_penjualan_historibayar.no_bukti', '=', 'marketing_penjualan_historibayar_giro.no_bukti')
            ->leftJoin('marketing_penjualan_giro', 'marketing_penjualan_historibayar_giro.kode_giro', '=', 'marketing_penjualan_giro.kode_giro')
            ->join('salesman', 'marketing_penjualan_historibayar.kode_salesman', '=', 'salesman.kode_salesman')
            ->where('no_faktur', $no_faktur)
            ->orderBy('created_at', 'desc')
            ->get();

        if (!empty($faktur->kode_cabang_pkp)) {
            $kode_cabang = $faktur->kode_cabang_pkp;
        } else {
            $kode_cabang = $faktur->kode_cabang;
        }

        $cabang = Cabang::where('kode_cabang', $kode_cabang)->first();

        $giro = Detailgiro::where('no_faktur', $no_faktur)
            ->join('marketing_penjualan_giro', 'marketing_penjualan_giro_detail.kode_giro', '=', 'marketing_penjualan_giro.kode_giro')
            ->get();

        $transfer = Detailtransfer::where('no_faktur', $no_faktur)
            ->join('marketing_penjualan_transfer', 'marketing_penjualan_transfer_detail.kode_transfer', '=', 'marketing_penjualan_transfer.kode_transfer')
            ->get();


        $profile = CapabilityProfile::load("POS-5890");
        $connector = new RawbtPrintConnector();
        $printer = new Printer($connector, $profile);

        $total = 0;
        foreach ($detail as $d) {

            $jml = convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->jumlah);
            $qty = explode('|', $jml);
            $dus = $qty[0];
            $pack = $qty[1];
            $pcs = $qty[2];
            $total += $d->subtotal;

            $datadetail[] = new item($d->nama_produk, "");
            if (!empty($dus)) {
                $datadetail[] = new item($dus . " Dus x " . $d->harga_dus, formatAngka($dus * $d->harga_dus));
            }
            if (!empty($pack)) {
                $datadetail[] = new item($pack . " Pack x " . $d->harga_pack, formatAngka($pack * $d->harga_pack));
            }
            if (!empty($pcs)) {
                $datadetail[] = new item($pcs . " Pcs x " . $d->harga_pcs, formatAngka($pcs * $d->harga_pcs));
            }
        }




        $nama_pt = strtoupper($cabang->nama_pt);
        $alamat = $cabang->alamat_cabang;

        $totalbayar = 0;

        if (!$pembayaran->isEmpty()) {
            foreach ($pembayaran as $d) {
                $totalbayar += $d->jumlah;
                $databayar[] = new item(date("d-m-y", strtotime($d->tanggal)), formatAngka($d->jumlah));
            }
        } else {
            $databayar[] = new item('', '');
        }

        if (!$giro->isEmpty()) {
            foreach ($giro as $g) {
                $status_list = ['Pending', 'Diterima', 'Ditolak'];
                $datagiro[] = new item($g->no_giro . " " . date('d-m-y', strtotime($g->tanggal)) . "\n" . formatAngka($g->jumlah) . " " . $status_list[$g->status] . "\n" . "------------------");
            }
        }

        if (!$transfer->isEmpty()) {
            foreach ($transfer as $t) {
                $status_list = ['Pending', 'Diterima', 'Ditolak'];
                $datatransfer[] = new item($t->no_transfer . " " . date('d-m-y', strtotime($t->tanggal)) . "\n" . formatAngka($t->jumlah) . " " . $status_list[$t->status] . "\n" . "------------------");
            }
        }

        try {
            //Detail Penjualan
            $items = $datadetail;


            //Detail Pembayaran
            if (!$pembayaran->isEmpty()) {
                $itemsbayar = $databayar;
            }

            if (!$giro->isEmpty()) {
                $itemsgiro = $datagiro;
            }

            if (!$transfer->isEmpty()) {
                $itemstransfer = $datatransfer;
            }


            //Tanggal Input Transaksi
            // $date = date('l jS \of F Y h:i:s A');
            $date = date("l jS \of F Y h:i:s A");

            /* Start the printer */
            // $logo = EscposImage::load($urllogo, false);

            // /* Print top logo */
            // if ($profile->getSupportsGraphics()) {
            //     $printer->graphics($logo);
            // }
            // if ($profile->getSupportsBitImageRaster() && !$profile->getSupportsGraphics()) {
            //     $printer->bitImage($logo);
            // }

            /* Name of shop */


            $printer->setJustification(Printer::JUSTIFY_CENTER);
            // $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer->setEmphasis(true);
            $printer->text($nama_pt . ".\n");
            $printer->setEmphasis(false);
            $printer->selectPrintMode();
            $printer->text($alamat . ".\n");
            $printer->text($date . "\n");
            $printer->text(textCamelCase($faktur->telepon_cabang) . "\n");


            /* Title of receipt */
            $printer->setEmphasis(true);
            $printer->text("LEMBAR UNTUK PERUSAHAAN\n");
            $printer->setEmphasis(false);

            /* Items */
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setEmphasis(true);
            $printer->text(new item('', ''));
            $pelanggan_salesman = new item($faktur->no_faktur, $faktur->nama_salesman);
            $printer->text($pelanggan_salesman->getAsString(32));
            $printer->text(date("d-m-Y H:i:s", strtotime($faktur->created_at)) . "\n");
            $printer->text($faktur->kode_pelanggan . " - " . $faktur->nama_pelanggan . "\n");
            $printer->text(textCamelCase($faktur->alamat_pelanggan) . "\n");
            if ($faktur->jenis_transaksi == 'K') {
                $jatuhtempo = date("Y-m-d", strtotime("+$faktur->ljt days", strtotime($faktur->tanggal)));
                $printer->text("Jatuh Tempo : " . date("d-m-Y", strtotime($jatuhtempo)) . "\n");
            }
            $printer->text(new item('', ''));

            // Mengatur teks menjadi tebal (bold)
            // Metode setEmphasis(true) digunakan untuk mengaktifkan mode penekanan teks
            // pada printer thermal, yang akan membuat teks berikutnya dicetak dengan format tebal
            // sampai mode ini dinonaktifkan dengan setEmphasis(false)
            $printer->setEmphasis(true);
            foreach ($items as $item) {
                $printer->text($item->getAsString(32)); // for 58mm Font A
            }

            $subtotal = new item('Subtotal', formatRupiah($faktur->total_bruto));
            $potongan = new item('Potongan', formatRupiah($faktur->potongan));
            if (!empty($faktur->penyesuaian)) {
                $penyesuaian = new item('Penyesuaian', formatRupiah($faktur->penyesuaian));
            }
            $totalnonppn = $faktur->total_bruto - $faktur->potongan - $faktur->potistimewa  - $faktur->penyesuaian;
            $total = new item('Total', formatAngka($totalnonppn));
            if (!empty($faktur->ppn)) {
                $ppn = new item('PPN', formatAngka($faktur->ppn));
            }
            $_grandtotal = $totalnonppn + $faktur->ppn - $faktur->total_retur;
            $retur = new item('Retur', formatAngka($faktur->total_retur));
            $grandtotal = new item('Grand Total', formatAngka($_grandtotal));
            //$total = new item('Total', '14.25', true);


            $printer->setEmphasis(true);
            $printer->text($subtotal->getAsString(32));
            $printer->setEmphasis(false);
            $printer->feed();

            // /* Tax and total */
            $printer->text($potongan->getAsString(32));
            if (!empty($faktur->penyesuaian)) {
                $printer->text($penyesuaian->getAsString(32));
            }
            $printer->text($total->getAsString(32));
            if (!empty($faktur->ppn)) {
                $printer->text($ppn->getAsString(32));
            }
            $printer->text($retur->getAsString(32));
            // $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer->feed();
            $printer->setEmphasis(true);
            $printer->text($grandtotal->getAsString(32));
            $printer->feed();
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $jenis_transaksi = $faktur->jenis_transaksi == 'T' ? 'TUNAI' : 'KREDIT';
            $printer->text(strtoupper($jenis_transaksi) . ".\n");
            $printer->selectPrintMode();

            // /* Footer */

            if (!$pembayaran->isEmpty()) {
                $printer->feed();
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text("PEMBAYARAN \n");
                $printer->setEmphasis(true);
                foreach ($itemsbayar as $itembayar) {
                    $printer->text($itembayar->getAsString(32)); // for 58mm Font A
                }
                $total_netto = $faktur->total_bruto - $faktur->total_retur - $faktur->potongan - $faktur->potongan_istimewa - $faktur->penyesuaian + $faktur->ppn;
                $sisatagihan = $total_netto - $totalbayar;
                $sisa = new item('SISA TAGIHAN', formatRupiah($sisatagihan));
                $grandtotalbayar = new item('TOTAL BAYAR', formatAngka($totalbayar));
                $printer->text($grandtotalbayar->getAsString(32)); // for 58mm Font A
                $printer->text($sisa->getAsString(32)); // for 58mm Font A
            }


            if (!$giro->isEmpty()) {
                $printer->feed();
                // Metode feed() digunakan untuk menggerakkan kertas printer ke bawah
                // Ini menciptakan baris kosong/spasi antara bagian pembayaran dan bagian giro
                // Memisahkan informasi untuk meningkatkan keterbacaan pada struk yang dicetak
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text("GIRO \n");
                $printer->setEmphasis(true);
                foreach ($itemsgiro as $itemgiro) {
                    $printer->text($itemgiro->getAsString(32)); // for 58mm Font A
                    // Kode ini mencetak informasi giro pada struk
                    // Parameter 32 menunjukkan lebar karakter untuk printer 58mm dengan Font A
                    // itemgiro berisi informasi seperti nomor giro, tanggal, dan nominal
                    // getAsString() memformat data giro menjadi string yang siap dicetak
                }
            }

            if (!$transfer->isEmpty()) {
                $printer->feed();
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text("TRANSFER \n");
                $printer->setEmphasis(true);
                foreach ($itemstransfer as $itemtransfer) {
                    $printer->text($itemtransfer->getAsString(32)); // for 58mm Font A
                }
            }


            $printer->feed(2);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Tidak Di Perkenankan Transfer \n");
            $printer->text("Ke Rekening Salesman \n");
            $printer->text("Apapun Jenis Transaksinya \n");
            $printer->text("Wajib Ditandatangani \n");
            $printer->text("kedua belah pihak,\n");
            $printer->text("Terimakasih\n");
            $printer->text("www.pedasalami.com\n");
            $printer->text("Print Ke: " . $faktur->print . "\n");
            $printer->feed();

            if (!empty($faktur->signature)) {
                $urlsignature = base_path('/public/storage/signature/') . $faktur->signature;
                $signature = EscposImage::load($urlsignature, false);
                /* Print top logo */
                if ($profile->getSupportsGraphics()) {
                    $printer->graphics($signature);
                }
                if ($profile->getSupportsBitImageRaster() && !$profile->getSupportsGraphics()) {
                    $printer->bitImage($signature);
                }
            }

            // /* Barcode Default look */
            // $printer->barcode($faktur->no_faktur, Printer::BARCODE_CODE128);
            // $printer->feed();
            // $printer->feed();






            // //Faktur PERUSAHAAN

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            //$printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer->text("\n");
            $printer->text("\n");
            $printer->feed(2);
            $printer->setEmphasis(true);
            $printer->text($nama_pt . ".\n");
            $printer->setEmphasis(false);
            $printer->selectPrintMode();
            $printer->text($alamat . ".\n");
            $printer->text(textCamelCase($faktur->telepon_cabang) . "\n");
            $printer->text($date . "\n");


            /* Title of receipt */
            $printer->setEmphasis(true);
            $printer->text("LEMBAR UNTUK PELANGGAN\n");
            $printer->setEmphasis(false);

            /* Items */
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setEmphasis(true);
            $printer->text(new item('', ''));
            $pelanggan_salesman = new item($faktur->no_faktur, $faktur->nama_salesman);
            $printer->text($pelanggan_salesman->getAsString(32));
            $printer->text(date("d-m-Y H:i:s", strtotime($faktur->created_at)) . "\n");
            $printer->text($faktur->kode_pelanggan . " - " . $faktur->nama_pelanggan . "\n");
            $printer->text(textCamelCase($faktur->alamat_pelanggan) . "\n");
            if ($faktur->jenis_transaksi == 'K') {
                $jatuhtempo = date("Y-m-d", strtotime("+$faktur->ljt days", strtotime($faktur->tanggal)));
                $printer->text("Jatuh Tempo : " . date("d-m-Y", strtotime($jatuhtempo)) . "\n");
            }
            $printer->text(new item('', ''));

            $printer->setEmphasis(true);
            foreach ($items as $item) {
                $printer->text($item->getAsString(32)); // for 58mm Font A
            }

            $subtotal = new item('Subtotal', formatRupiah($faktur->total_bruto));
            $potongan = new item('Potongan', formatRupiah($faktur->potongan));
            if (!empty($faktur->penyesuaian)) {
                $penyesuaian = new item('Penyesuaian', formatRupiah($faktur->penyesuaian));
            }
            $totalnonppn = $faktur->total_bruto - $faktur->potongan - $faktur->potistimewa  - $faktur->penyesuaian;
            $total = new item('Total', formatAngka($totalnonppn));
            if (!empty($faktur->ppn)) {
                $ppn = new item('PPN', formatAngka($faktur->ppn));
            }
            $_grandtotal = $totalnonppn + $faktur->ppn - $faktur->total_retur;
            $retur = new item('Retur', formatAngka($faktur->total_retur));
            $grandtotal = new item('Grand Total', formatAngka($_grandtotal));
            //$total = new item('Total', '14.25', true);


            $printer->setEmphasis(true);
            $printer->text($subtotal->getAsString(32));
            $printer->setEmphasis(false);
            $printer->feed();

            // /* Tax and total */
            $printer->text($potongan->getAsString(32));
            if (!empty($faktur->penyesuaian)) {
                $printer->text($penyesuaian->getAsString(32));
            }
            $printer->text($total->getAsString(32));
            if (!empty($faktur->ppn)) {
                $printer->text($ppn->getAsString(32));
            }
            $printer->text($retur->getAsString(32));
            // $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer->feed();
            $printer->setEmphasis(true);
            $printer->text($grandtotal->getAsString(32));
            $printer->feed();
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $jenis_transaksi = $faktur->jenis_transaksi == 'T' ? 'TUNAI' : 'KREDIT';
            $printer->text(strtoupper($jenis_transaksi) . ".\n");
            $printer->selectPrintMode();

            // /* Footer */

            if (!$pembayaran->isEmpty()) {
                $printer->feed();
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text("PEMBAYARAN \n");
                $printer->setEmphasis(true);
                foreach ($itemsbayar as $itembayar) {
                    $printer->text($itembayar->getAsString(32)); // for 58mm Font A
                }
                $total_netto = $faktur->total_bruto - $faktur->total_retur - $faktur->potongan - $faktur->potongan_istimewa - $faktur->penyesuaian + $faktur->ppn;
                $sisatagihan = $total_netto - $totalbayar;
                $sisa = new item('SISA TAGIHAN', formatRupiah($sisatagihan));
                $grandtotalbayar = new item('TOTAL BAYAR', formatAngka($totalbayar));
                $printer->text($grandtotalbayar->getAsString(32)); // for 58mm Font A
                $printer->text($sisa->getAsString(32)); // for 58mm Font A
            }


            if (!$giro->isEmpty()) {
                $printer->feed();
                // Metode feed() digunakan untuk menggerakkan kertas printer ke bawah
                // Ini menciptakan baris kosong/spasi antara bagian pembayaran dan bagian giro
                // Memisahkan informasi untuk meningkatkan keterbacaan pada struk yang dicetak
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text("GIRO \n");
                $printer->setEmphasis(true);
                foreach ($itemsgiro as $itemgiro) {
                    $printer->text($itemgiro->getAsString(32)); // for 58mm Font A
                    // Kode ini mencetak informasi giro pada struk
                    // Parameter 32 menunjukkan lebar karakter untuk printer 58mm dengan Font A
                    // itemgiro berisi informasi seperti nomor giro, tanggal, dan nominal
                    // getAsString() memformat data giro menjadi string yang siap dicetak
                }
            }

            if (!$transfer->isEmpty()) {
                $printer->feed();
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text("TRANSFER \n");
                $printer->setEmphasis(true);
                foreach ($itemstransfer as $itemtransfer) {
                    $printer->text($itemtransfer->getAsString(32)); // for 58mm Font A
                }
            }

            $printer->feed(2);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Tidak Di Perkenankan Transfer \n");
            $printer->text("Ke Rekening Salesman \n");
            $printer->text("Apapun Jenis Transaksinya \n");
            $printer->text("Wajib Ditandatangani \n");
            $printer->text("kedua belah pihak,\n");
            $printer->text("Terimakasih\n");
            $printer->text("www.pedasalami.com\n");
            $printer->text("Print Ke: " . $faktur->print . "\n");
            $printer->feed();

            if (!empty($faktur->signature)) {
                $urlsignature = base_path('/public/storage/signature/') . $faktur->signature;
                $signature = EscposImage::load($urlsignature, false);
                /* Print top logo */
                if ($profile->getSupportsGraphics()) {
                    $printer->graphics($signature);
                }
                if ($profile->getSupportsBitImageRaster() && !$profile->getSupportsGraphics()) {
                    $printer->bitImage($signature);
                }
            }


            if ($faktur->jenis_transaksi == "K" && $faktur->print == '0') {
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                //$printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                $printer->text("\n");
                $printer->text("\n");
                $printer->feed(2);
                $printer->setEmphasis(true);
                $printer->text($nama_pt . ".\n");
                $printer->setEmphasis(false);
                $printer->selectPrintMode();
                $printer->text($alamat . ".\n");
                $printer->text(textCamelCase($faktur->telepon_cabang) . "\n");
                $printer->text($date . "\n");


                /* Title of receipt */
                $printer->setEmphasis(true);
                $printer->text("LEMBAR UNTUK ARSIP\n");
                $printer->setEmphasis(false);

                /* Items */
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->setEmphasis(true);
                $printer->text(new item('', ''));
                $pelanggan_salesman = new item($faktur->no_faktur, $faktur->nama_salesman);
                $printer->text($pelanggan_salesman->getAsString(32));
                $printer->text(date("d-m-Y H:i:s", strtotime($faktur->created_at)) . "\n");
                $printer->text($faktur->kode_pelanggan . " - " . $faktur->nama_pelanggan . "\n");
                $printer->text(textCamelCase($faktur->alamat_pelanggan) . "\n");
                if ($faktur->jenis_transaksi == 'K') {
                    $jatuhtempo = date("Y-m-d", strtotime("+$faktur->ljt days", strtotime($faktur->tanggal)));
                    $printer->text("Jatuh Tempo : " . date("d-m-Y", strtotime($jatuhtempo)) . "\n");
                }
                $printer->text(new item('', ''));

                $printer->setEmphasis(true);
                foreach ($items as $item) {
                    $printer->text($item->getAsString(32)); // for 58mm Font A
                }

                $subtotal = new item('Subtotal', formatRupiah($faktur->total_bruto));
                $potongan = new item('Potongan', formatRupiah($faktur->potongan));
                if (!empty($faktur->penyesuaian)) {
                    $penyesuaian = new item('Penyesuaian', formatRupiah($faktur->penyesuaian));
                }
                $totalnonppn = $faktur->total_bruto - $faktur->potongan - $faktur->potistimewa  - $faktur->penyesuaian;
                $total = new item('Total', formatAngka($totalnonppn));
                if (!empty($faktur->ppn)) {
                    $ppn = new item('PPN', formatAngka($faktur->ppn));
                }
                $_grandtotal = $totalnonppn + $faktur->ppn - $faktur->total_retur;
                $retur = new item('Retur', formatAngka($faktur->total_retur));
                $grandtotal = new item('Grand Total', formatAngka($_grandtotal));
                //$total = new item('Total', '14.25', true);


                $printer->setEmphasis(true);
                $printer->text($subtotal->getAsString(32));
                $printer->setEmphasis(false);
                $printer->feed();

                // /* Tax and total */
                $printer->text($potongan->getAsString(32));
                if (!empty($faktur->penyesuaian)) {
                    $printer->text($penyesuaian->getAsString(32));
                }
                $printer->text($total->getAsString(32));
                if (!empty($faktur->ppn)) {
                    $printer->text($ppn->getAsString(32));
                }
                $printer->text($retur->getAsString(32));
                // $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                $printer->feed();
                $printer->setEmphasis(true);
                $printer->text($grandtotal->getAsString(32));
                $printer->feed();
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                $jenis_transaksi = $faktur->jenis_transaksi == 'T' ? 'TUNAI' : 'KREDIT';
                $printer->text(strtoupper($jenis_transaksi) . ".\n");
                $printer->selectPrintMode();

                // /* Footer */

                if (!$pembayaran->isEmpty()) {
                    $printer->feed();
                    $printer->setJustification(Printer::JUSTIFY_LEFT);
                    $printer->text("PEMBAYARAN \n");
                    $printer->setEmphasis(true);
                    foreach ($itemsbayar as $itembayar) {
                        $printer->text($itembayar->getAsString(32)); // for 58mm Font A
                    }
                    $total_netto = $faktur->total_bruto - $faktur->total_retur - $faktur->potongan - $faktur->potongan_istimewa - $faktur->penyesuaian + $faktur->ppn;
                    $sisatagihan = $total_netto - $totalbayar;
                    $sisa = new item('SISA TAGIHAN', formatRupiah($sisatagihan));
                    $grandtotalbayar = new item('TOTAL BAYAR', formatAngka($totalbayar));
                    $printer->text($grandtotalbayar->getAsString(32)); // for 58mm Font A
                    $printer->text($sisa->getAsString(32)); // for 58mm Font A
                }

                if (!$giro->isEmpty()) {
                    $printer->feed();
                    // Metode feed() digunakan untuk menggerakkan kertas printer ke bawah
                    // Ini menciptakan baris kosong/spasi antara bagian pembayaran dan bagian giro
                    // Memisahkan informasi untuk meningkatkan keterbacaan pada struk yang dicetak
                    $printer->setJustification(Printer::JUSTIFY_LEFT);
                    $printer->text("GIRO \n");
                    $printer->setEmphasis(true);
                    foreach ($itemsgiro as $itemgiro) {
                        $printer->text($itemgiro->getAsString(32)); // for 58mm Font A
                        // Kode ini mencetak informasi giro pada struk
                        // Parameter 32 menunjukkan lebar karakter untuk printer 58mm dengan Font A
                        // itemgiro berisi informasi seperti nomor giro, tanggal, dan nominal
                        // getAsString() memformat data giro menjadi string yang siap dicetak
                    }
                }

                if (!$transfer->isEmpty()) {
                    $printer->feed();
                    $printer->setJustification(Printer::JUSTIFY_LEFT);
                    $printer->text("TRANSFER \n");
                    $printer->setEmphasis(true);
                    foreach ($itemstransfer as $itemtransfer) {
                        $printer->text($itemtransfer->getAsString(32)); // for 58mm Font A
                    }
                }

                $printer->feed(2);
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->text("Tidak Di Perkenankan Transfer \n");
                $printer->text("Ke Rekening Salesman \n");
                $printer->text("Apapun Jenis Transaksinya \n");
                $printer->text("Wajib Ditandatangani \n");
                $printer->text("kedua belah pihak,\n");
                $printer->text("Terimakasih\n");
                $printer->text("www.pedasalami.com\n");
                $printer->text("Print Ke: " . $faktur->print . "\n");
                $printer->feed();

                if (!empty($faktur->signature)) {
                    $urlsignature = base_path('/public/storage/signature/') . $faktur->signature;
                    $signature = EscposImage::load($urlsignature, false);
                    /* Print top logo */
                    if ($profile->getSupportsGraphics()) {
                        $printer->graphics($signature);
                    }
                    if ($profile->getSupportsBitImageRaster() && !$profile->getSupportsGraphics()) {
                        $printer->bitImage($signature);
                    }
                }
            }

            // /* Barcode Default look */

            // $printer->barcode($faktur->no_faktur, Printer::BARCODE_CODE128);
            // $printer->feed();
            // $printer->feed();


            // // Demo that alignment QRcode is the same as text
            // $printer2 = new Printer($connector); // dirty printer profile hack !!
            // $printer->setJustification(Printer::JUSTIFY_CENTER);
            // $printer->qrCode("https://rawbt.ru/mike42", Printer::QR_ECLEVEL_M, 8);
            // $printer->text("rawbt.ru/mike42\n");
            // $printer->setJustification();
            // $printer->feed();


            /* Cut the receipt and open the cash drawer */
            $printer->cut();
            $printer->pulse();
        } catch (Exception $e) {
            echo $e->getMessage();
        } finally {
            $printer->close();

            //Update Print
            Penjualan::where('no_faktur', $no_faktur)->update([
                'print' => DB::raw('print + 1'),
                'lock_print' => 0
            ]);

            Historibayarpenjualan::where('no_faktur', $no_faktur)->update([
                'print_tagihan' => 1
            ]);

            Detailgiro::where('no_faktur', $no_faktur)->where('print_giro', 0)->update([
                'print_giro' => 1
            ]);

            Detailtransfer::where('no_faktur', $no_faktur)->where('print_transfer', 0)->update([
                'print_transfer' => 1
            ]);
        }
    }


    public function uploadsignature(Request $request)
    {
        $no_faktur = $request->no_faktur;
        $format = $no_faktur;
        $folderPath = "public/signature/";
        $image_parts = explode(";base64,", $request->signed);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $fileName =  $format . '.png';
        $file = $folderPath . $fileName;
        $data = [
            'signature' => $fileName
        ];
        $update = Penjualan::where('no_faktur', $no_faktur)->update($data);
        if ($update) {
            if (Storage::exists($file)) {
                Storage::delete($file);
            }
            Storage::put($file, $image_base64);
            return Redirect::back()->with(messageSuccess('Tanda Tangan Berhasil Disimpan'));
        }
    }


    public function deletesignature($no_faktur)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        $data = [
            'signature' => NULL
        ];
        $folderPath = "public/signature/";
        $faktur = Penjualan::where('no_faktur', $no_faktur)->first();
        $file = $folderPath . $faktur->signature;
        $update = Penjualan::where('no_faktur', $no_faktur)->update($data);
        if ($update) {
            Storage::delete($file);
            return Redirect::back()->with(['success' => 'Tanda Tanggan Berhasil Dihapus']);
        }
    }


    public function penjualan()
    {
        return view('sfa.penjualan');
    }

    public function createpenjualan()
    {


        $kodepelanggan = Cookie::get('kodepelanggan');
        if ($kodepelanggan == null) {
            return Redirect::route('sfa.pelanggan')->with(messageError('Anda Belum Memilih Pelanggan'));
        }

        $data['kode_pelanggan'] = Crypt::decrypt($kodepelanggan);
        $diskon = Diskon::orderBy('kode_kategori_diskon')->get();

        $diskon_json = json_encode($diskon);
        $data['diskon'] = $diskon_json;

        $hariini = date('Y-m-d');
        $querypenjualan = Penjualan::query();
        $querypenjualan->select(
            'marketing_penjualan.no_faktur',
            'marketing_penjualan.tanggal',
            DB::raw("IFNULL((SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE marketing_penjualan_detail.no_faktur = marketing_penjualan.no_faktur),0) - potongan - potongan_istimewa - penyesuaian + ppn -  IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
            INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF'),0)
            as total_piutang"),
            DB::raw("(SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur ) as jmlbayar"),
        );


        $querypenjualan->where('marketing_penjualan.status', 0);
        $querypenjualan->where('jenis_transaksi', 'K');
        $querypenjualan->where('status_batal', 0);
        $querypenjualan->where('marketing_penjualan.kode_pelanggan', $data['kode_pelanggan']);

        $data['piutang'] = $querypenjualan->get();
        return view('sfa.penjualan_create', $data);
    }


    public function addproduk($kode_pelanggan)
    {
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $hrg = new Harga();
        $data['harga'] = $hrg->getHargabypelanggan($kode_pelanggan);
        $kode_pelanggan = Pelanggan::where('kode_pelanggan', $kode_pelanggan)->first();
        $data['pelanggan'] = $kode_pelanggan;
        return view('sfa.penjualan_addproduk', $data);
    }

    public function editpenjualan($no_faktur)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        $pj = new Penjualan();
        $penjualan = $pj->getFaktur($no_faktur);
        $data['penjualan'] = $penjualan;
        $total_netto = $penjualan->total_bruto - $penjualan->total_retur - $penjualan->potongan - $penjualan->potongan_istimewa - $penjualan->penyesuaian + $penjualan->ppn;
        $data['total_netto'] = $total_netto;
        $data['detail'] = Detailpenjualan::select('marketing_penjualan_detail.*', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack', 'kode_kategori_diskon')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->where('no_faktur', $no_faktur)
            ->get();
        $titipan = Historibayarpenjualan::where('tanggal', $penjualan->tanggal)
            ->where('jenis_bayar', 'TP')
            ->where('voucher', 0)
            ->where('no_faktur', $no_faktur)
            ->orderBy('no_bukti')
            ->first();

        $voucher = Historibayarpenjualan::where('tanggal', $penjualan->tanggal)
            ->where('voucher', 1)
            ->where('jenis_voucher', 2)
            ->where('jenis_bayar', 'TN')
            ->where('no_faktur', $no_faktur)
            ->orderBy('no_bukti')
            ->first();


        $data['titipan'] = $titipan == null ? 0 : $titipan->jumlah;
        $data['voucher'] = $voucher == null ? 0 : $voucher->jumlah;
        $diskon = Diskon::orderBy('kode_kategori_diskon')->get();
        $diskon_json = json_encode($diskon);
        $data['diskon'] = $diskon_json;
        return view('sfa.penjualan_edit', $data);
    }


    public function ubahfakturbatal($no_faktur)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        $data['no_faktur'] = $no_faktur;
        return view('sfa.penjualan_ubahfakturbatal', $data);
    }

    public function storeubahfakturbatal(Request $request, $no_faktur)
    {

        $no_faktur = Crypt::decrypt($no_faktur);
        $data = [
            'keterangan' => $request->keterangan,
            'status_batal' => 2
        ];
        $update = Penjualan::where('no_faktur', $no_faktur)->update($data);
        if ($update) {
            return Redirect::back()->with(['success' => 'Menunggu Persetujuan Operation Manager']);
        }
    }


    public function batalkanubahfakturbatal($no_faktur)
    {

        $no_faktur = Crypt::decrypt($no_faktur);
        $data = [
            'status_batal' => 0
        ];
        $update = Penjualan::where('no_faktur', $no_faktur)->update($data);
        if ($update) {
            return Redirect::back()->with(['success' => 'Faktur Batal Di Batalkan']);
        }
    }

    public function createajuanfaktur($kode_pelanggan)
    {
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $pelanggan = Pelanggan::where('kode_pelanggan', $kode_pelanggan)->first();
        $data['pelanggan'] = $pelanggan;
        return view('sfa.ajuanfaktur_create', $data);
    }


    public function storeajuanfaktur(Request $request, $kode_pelanggan)
    {

        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $pelanggan = Pelanggan::where('kode_pelanggan', $kode_pelanggan)->first();
        $request->validate([
            'tanggal' => 'required',
            'jumlah_faktur' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $pelanggan = Pelanggan::where('kode_pelanggan', $kode_pelanggan)->first();

            //Generate No. Pengajuan
            $lastajuan = Pengajuanfaktur::select('no_pengajuan')
                ->whereRaw('YEAR(tanggal) = "' . date('Y', strtotime($request->tanggal)) . '"')
                ->whereRaw('MID(no_pengajuan,4,3) = "' . $pelanggan->kode_cabang . '"')
                ->orderBy('no_pengajuan', 'desc')
                ->first();

            $last_no_pengajuan = $lastajuan != null ? $lastajuan->no_pengajuan : '';
            $no_pengajuan = buatkode($last_no_pengajuan, 'PJF' . $pelanggan->kode_cabang . substr(date('Y', strtotime($request->tanggal)), 2, 2), 5);


            if ($pelanggan->limit_pelanggan <= 10000000 && $request->cod == '1' && $request->jumlah_faktur <= 2) {
                Pengajuanfaktur::create([
                    'no_pengajuan' => $no_pengajuan,
                    'tanggal' => $request->tanggal,
                    'kode_pelanggan' => $kode_pelanggan,
                    'kode_salesman' => $pelanggan->kode_salesman,
                    'jumlah_faktur' => toNumber($request->jumlah_faktur),
                    'siklus_pembayaran' => isset($request->cod) ? $request->cod : 0,
                    'status' => 1,
                    'keterangan' => $request->keterangan
                ]);
            } else {
                Pengajuanfaktur::create([
                    'no_pengajuan' => $no_pengajuan,
                    'tanggal' => $request->tanggal,
                    'kode_pelanggan' => $kode_pelanggan,
                    'kode_salesman' => $pelanggan->kode_salesman,
                    'jumlah_faktur' => toNumber($request->jumlah_faktur),
                    'siklus_pembayaran' => isset($request->cod) ? $request->cod : 0,
                    'status' => 0,
                    'keterangan' => $request->keterangan
                ]);
                //Disposisi

                $tanggal_hariini = date('Y-m-d');
                $lastdisposisi = Disposisiajuanfaktur::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
                    ->orderBy('kode_disposisi', 'desc')
                    ->first();
                $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
                $format = "DPFK" . date('Ymd');
                $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);


                $regional = Cabang::where('kode_cabang', $pelanggan->kode_cabang)->first();
                $smm = User::role('sales marketing manager')->where('kode_cabang', $pelanggan->kode_cabang)
                    ->where('status', 1)
                    ->first();

                if ($smm != null) {
                    $id_penerima = $smm->id;
                } else {
                    $rsm = User::role('regional sales manager')->where('kode_regional', $regional->kode_regional)
                        ->where('status', 1)
                        ->first();
                    $id_penerima = $rsm->id;
                    if ($rsm == NULL) {
                        $gm = User::role('gm marketing')
                            ->where('status', 1)
                            ->first();
                        $id_penerima = $gm->id;
                    }
                }


                Disposisiajuanfaktur::create([
                    'kode_disposisi' => $kode_disposisi,
                    'no_pengajuan' => $no_pengajuan,
                    'id_pengirim' => auth()->user()->id,
                    'id_penerima' => $id_penerima,
                    'catatan' => $request->keterangan,
                    'status' => 0
                ]);
            }

            DB::commit();
            return redirect('/sfa/pelanggan/' . Crypt::encrypt($kode_pelanggan) . '/show')->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e);
            return redirect('/sfa/pelanggan/' . Crypt::encrypt($kode_pelanggan) . '/show')->with(messageError($e->getMessage()));
        }
    }


    public function createajuanlimit($kode_pelanggan)
    {
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $pelanggan = Pelanggan::where('kode_pelanggan', $kode_pelanggan)->first();
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $klasifikasi_outlet = Klasifikasioutlet::orderBy('kode_klasifikasi')->get();
        return view('sfa.ajuanlimit_create', compact('cabang', 'pelanggan', 'klasifikasi_outlet'));
    }


    public function storeajuanlimit(Request $request, $kode_pelanggan)
    {
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $tanggal = date('Y-m-d');
        DB::beginTransaction();
        try {
            $pelanggan = Pelanggan::where('kode_pelanggan', $kode_pelanggan)->first();
            $last_ajuan_limit = Ajuanlimitkredit::select('no_pengajuan')
                ->whereRaw('YEAR(tanggal) = "' . date('Y', strtotime($tanggal)) . '"')
                ->whereRaw('MID(no_pengajuan,4,3) = "' . $pelanggan->kode_cabang . '"')
                ->whereRaw('MID(no_pengajuan,7,2) = "' . date('y', strtotime($tanggal)) . '"')
                ->orderBy('no_pengajuan', 'desc')
                ->first();


            if ($last_ajuan_limit == null) {
                $last_no_pengajuan = 'PLK' . $pelanggan->kode_cabang . substr(date('Y', strtotime($tanggal)), 2, 2) . '00000';
            } else {
                $last_no_pengajuan = $last_ajuan_limit->no_pengajuan;
            }
            $no_pengajuan = buatkode($last_no_pengajuan, 'PLK' . $pelanggan->kode_cabang . substr(date('Y', strtotime($tanggal)), 2, 2), 5);


            //dd($no_pengajuan);
            $lokasi = explode(",", $request->lokasi);
            // dd($lokasi);
            //Update Data Pelanggan
            Pelanggan::where('kode_pelanggan', $kode_pelanggan)->update([
                'nik' => $request->nik,
                'nama_pelanggan' => $request->nama_pelanggan,
                'alamat_pelanggan' => $request->alamat_pelanggan,
                'alamat_toko' => $request->alamat_toko,
                // 'latitude' => $lokasi[0],
                // 'longitude' => $lokasi[1],
                'no_hp_pelanggan' => $request->no_hp_pelanggan,
                'hari'  => $request->hari,
                'status_outlet' => $request->status_outlet,
                'type_outlet' => $request->type_outlet,
                'cara_pembayaran' => $request->cara_pembayaran,
                'kepemilikan' => $request->kepemilikan,
                'lama_langganan' => $request->lama_langganan,
                'lama_berjualan' => $request->lama_berjualan,
                'jaminan' => $request->jaminan,
                'kode_klasifikasi' => $request->kode_klasifikasi,
                'omset_toko' => toNumber($request->omset_toko)
            ]);

            //Insert Pengajuan
            Ajuanlimitkredit::create([
                'no_pengajuan' => $no_pengajuan,
                'tanggal' => date('Y-m-d'),
                'kode_pelanggan' => $kode_pelanggan,
                'limit_sebelumnya' => !empty($pelanggan->limit_pelanggan) ? $pelanggan->limit_pelanggan : 0,
                'omset_sebelumnya' => !empty($pelanggan->omset_toko) ? $pelanggan->omset_toko : 0,
                'jumlah'  => toNumber($request->jumlah),
                'ljt' => $request->ljt,
                'topup_terakhir' => $request->topup_terakhir,
                'lama_topup' => 1,
                'jml_faktur' => $request->jml_faktur,
                'histori_transaksi' => $request->histori_transaksi,
                'status_outlet' => $request->status_outlet,
                'type_outlet' => $request->type_outlet,
                'cara_pembayaran' => $request->cara_pembayaran,
                'kepemilikan' => $request->kepemilikan,
                'lama_langganan' => $request->lama_langganan,
                'lama_berjualan' => $request->lama_berjualan,
                'jaminan' => $request->jaminan,
                'omset_toko' => toNumber($request->omset_toko),
                'status' => 0,
                'skor' => $request->skor,
                'kode_salesman' => $pelanggan->kode_salesman,
                'id_user' => auth()->user()->id
            ]);


            //Disposisi

            $tanggal_hariini = date('Y-m-d');
            $lastdisposisi = Disposisiajuanlimitkredit::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
                ->orderBy('kode_disposisi', 'desc')
                ->first();
            $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
            $format = "DPLK" . date('Ymd');
            $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);


            $regional = Cabang::where('kode_cabang', $pelanggan->kode_cabang)->first();
            $smm = User::role('sales marketing manager')->where('kode_cabang', $pelanggan->kode_cabang)
                ->where('status', 1)
                ->first();

            if ($smm != null) {
                $id_penerima = $smm->id;
            } else {
                $rsm = User::role('regional sales manager')->where('kode_regional', $regional->kode_regional)
                    ->where('status', 1)
                    ->first();
                $id_penerima = $rsm->id;
                if ($rsm == NULL) {
                    $gm = User::role('gm marketing')
                        ->where('status', 1)
                        ->first();
                    $id_penerima = $gm->id;
                }
            }


            Disposisiajuanlimitkredit::create([
                'kode_disposisi' => $kode_disposisi,
                'no_pengajuan' => $no_pengajuan,
                'id_pengirim' => auth()->user()->id,
                'id_penerima' => $id_penerima,
                'uraian_analisa' => $request->uraian_analisa,
                'status' => 0
            ]);

            DB::commit();
            return redirect('/sfa/pelanggan/' . Crypt::encrypt($kode_pelanggan) . '/show')->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/sfa/pelanggan/' . Crypt::encrypt($kode_pelanggan) . '/show')->with(messageError($e->getMessage()));
        }
    }


    public function createretur()
    {
        $kodepelanggan = Cookie::get('kodepelanggan');
        if ($kodepelanggan == null) {
            return Redirect::route('sfa.pelanggan')->with(messageError('Anda Belum Memilih Pelanggan'));
        }

        $data['kode_pelanggan'] = Crypt::decrypt($kodepelanggan);
        return view('sfa.retur_create', $data);
    }

    public function addprodukretur($kode_pelanggan)
    {
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $hrg = new Harga();
        $data['harga'] = $hrg->getHargabypelanggan($kode_pelanggan);
        return view('sfa.retur_addproduk', $data);
    }

    public function showretur($no_retur)
    {
        $no_retur = Crypt::decrypt($no_retur);
        $rtr = new Retur();
        $retur = $rtr->getRetur($request = null, $no_retur)->first();
        $data['retur'] = $retur;
        $data['detail'] = $rtr->getDetailretur($no_retur);
        return view('sfa.retur_show', $data);
    }


    public function trackingsalesman()
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        if (!$user->hasRole($roles_access_all_cabang)) {
            $cabang = Cabang::where('kode_cabang', $user->kode_cabang)->first();
        } else {
            $cabang = Cabang::where('kode_cabang', 'PST')->first();
        }

        $data['lokasi_cabang'] = explode(",", $cabang->lokasi_cabang);
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('sfa.trackingsalesman', $data);
    }

    function getlocationcheckin(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $hariini = $request->tanggal;
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang;
        }
        $kode_salesman = $request->kode_salesman;

        $query = Checkinpenjualan::query();
        $query->select('marketing_penjualan_checkin.*', 'nama_pelanggan', 'pelanggan.foto', 'alamat_pelanggan', 'marker');
        $query->join('pelanggan', 'marketing_penjualan_checkin.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $query->leftjoin('users', 'marketing_penjualan_checkin.kode_salesman', '=', 'users.kode_salesman');
        $query->leftjoin('salesman', 'users.kode_salesman', '=', 'salesman.kode_salesman');
        if (!empty($hariini)) {
            $query->where('tanggal', $hariini);
        } else {
            $query->where('tanggal', date('Y-m-d'));
        }
        if (!empty($kode_cabang)) {
            $query->where('salesman.kode_cabang', $kode_cabang);
        }

        if (!empty($kode_salesman)) {
            $query->where('marketing_penjualan_checkin.kode_salesman', $kode_salesman);
        }
        $checkin = $query->get();


        $jsondata = json_encode($checkin);
        return $jsondata;
    }
}
