<?php

namespace App\Http\Controllers;

use App\Models\Ajuanfakturkredit;
use App\Models\Cabang;
use App\Models\Checkinpenjualan;
use App\Models\Klasifikasioutlet;
use App\Models\Pelanggan;
use App\Models\Pengajuanfaktur;
use App\Models\Penjualan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class SfaControler extends Controller
{
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
            'kode_cabang' => $user->kode_cabang,
            'kode_salesman' => $user->kode_salesman,
            'kode_wilayah' => $request->kode_wilayah,
            'hari' => $request->hari,
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
            ->orderBy('tanggal', 'desc')
            ->first();
        $data['fakturkredit'] = Penjualan::where('kode_pelanggan', $kode_pelanggan)
            ->where('status', 0)
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
}
