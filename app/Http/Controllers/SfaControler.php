<?php

namespace App\Http\Controllers;

use App\Models\Ajuanfakturkredit;
use App\Models\Cabang;
use App\Models\Checkinpenjualan;
use App\Models\Detailgiro;
use App\Models\Detailretur;
use App\Models\Detailtransfer;
use App\Models\Historibayarpenjualan;
use App\Models\Klasifikasioutlet;
use App\Models\Pelanggan;
use App\Models\Pengajuanfaktur;
use App\Models\Penjualan;
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


    public function showpenjualan($no_faktur)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        $data['jenis_bayar'] = config('penjualan.jenis_bayar');
        $pnj = new Penjualan();
        $penjualan = $pnj->getFaktur($no_faktur);
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
        return view('sfa.penjualan_show', $data);
    }

    public function cetakfaktur($no_faktur)
    {
        $no_faktur = Crypt::decrypt($no_faktur);

        //Data Faktur
        $pnj = new Penjualan();
        $penjualan = $pnj->getFaktur($no_faktur);
        $faktur = $penjualan;

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
            if (!empty($jumlah_dus)) {
                $datadetail[] = new item($dus . " Dus x " . $d->harga_dus, formatAngka($dus * $d->harga_dus));
            }
            if (!empty($jumlah_pack)) {
                $datadetail[] = new item($pack . " Pack x " . $d->harga_pack, formatAngka($pack * $d->harga_pack));
            }
            if (!empty($jumlah_pcs)) {
                $datadetail[] = new item($pcs . " Pcs x " . $d->harga_pcs, formatAngka($pcs * $d->harga_pcs));
            }
        }

        $nama_pt = strtoupper($cabang->nama_pt);
        $alamat = $cabang->alamat_cabang;

        $totalbayar = 0;

        if ($pembayaran != null) {
            foreach ($pembayaran as $d) {
                $totalbayar += $d->jumlah;
                $databayar[] = new item(date("d-m-y", strtotime($d->tanggal)), formatAngka($d->jumlah));
            }
        } else {
            $databayar[] = new item('', '');
        }

        try {
            //Detail Penjualan
            $items = $datadetail;

            //Detail Pembayaran
            if ($pembayaran != null) {
                $itemsbayar = $databayar;
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
            $printer->text(strtolower(ucwords($faktur->alamat_pelanggan)));
            $printer->text(new item('', ''));

            $printer->setEmphasis(true);
            foreach ($items as $item) {
                $printer->text($item->getAsString(32)); // for 58mm Font A
            }

            // $subtotal = new item('Subtotal', rupiah($faktur->subtotal));
            // $potongan = new item('Potongan', rupiah($faktur->potongan));
            // $totalnonppn = $faktur->subtotal - $faktur->potongan - $faktur->potistimewa - $faktur->penyharga;
            // $total = new item('Total', rupiah($totalnonppn));
            // if (!empty($faktur->ppn)) {
            //     $ppn = new item('PPN', rupiah($faktur->ppn));
            // }
            // $_grandtotal = $faktur->total - $totalretur;
            // $retur = new item('Retur', rupiah($totalretur));
            // $grandtotal = new item('Grand Total', rupiah($_grandtotal));
            // //$total = new item('Total', '14.25', true);


            // $printer->setEmphasis(true);
            // $printer->text($subtotal->getAsString(32));
            // $printer->setEmphasis(false);
            // $printer->feed();

            // /* Tax and total */
            // $printer->text($potongan->getAsString(32));
            // $printer->text($total->getAsString(32));
            // if (!empty($faktur->ppn)) {
            //     $printer->text($ppn->getAsString(32));
            // }
            // $printer->text($retur->getAsString(32));
            // // $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            // $printer->feed();
            // $printer->setEmphasis(true);
            // $printer->text($grandtotal->getAsString(32));
            // $printer->feed();
            // $printer->setJustification(Printer::JUSTIFY_CENTER);
            // $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            // $printer->text(strtoupper($faktur->jenistransaksi) . ".\n");
            // $printer->selectPrintMode();

            // /* Footer */

            // if (!empty($cekpembayaran)) {
            //     $printer->feed();
            //     $printer->setJustification(Printer::JUSTIFY_LEFT);
            //     $printer->text("PEMBAYARAN \n");
            //     $printer->setEmphasis(true);
            //     foreach ($itemsbayar as $itembayar) {
            //         $printer->text($itembayar->getAsString(32)); // for 58mm Font A
            //     }
            //     $sisatagihan = $faktur->total - $totalretur - $totalbayar;
            //     $sisa = new item('SISA TAGIHAN', rupiah($sisatagihan));
            //     $grandtotalbayar = new item('TOTAL BAYAR', rupiah($totalbayar));
            //     $printer->text($grandtotalbayar->getAsString(32)); // for 58mm Font A
            //     $printer->text($sisa->getAsString(32)); // for 58mm Font A
            // }



            $printer->feed(2);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Tidak Di Perkenankan Transfer \n");
            $printer->text("Ke Rekening Salesman \n");
            $printer->text("Apapun Jenis Transaksinya \n");
            $printer->text("Wajib Ditandatangani \n");
            $printer->text("kedua belah pihak,\n");
            $printer->text("Terimakasih\n");
            $printer->text("www.pedasalami.com\n");
            $printer->feed();

            // if (!empty($faktur->signature)) {
            //     $urlsignature = base_path('/public/storage/signature/') . $faktur->signature;
            //     $signature = EscposImage::load($urlsignature, false);
            //     /* Print top logo */
            //     if ($profile->getSupportsGraphics()) {
            //         $printer->graphics($signature);
            //     }
            //     if ($profile->getSupportsBitImageRaster() && !$profile->getSupportsGraphics()) {
            //         $printer->bitImage($signature);
            //     }
            // }







            // //Faktur PERUSAHAAN
            // /* Name of shop */
            // $printer->setJustification(Printer::JUSTIFY_CENTER);
            // //$printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            // $printer->text("\n");
            // $printer->text("\n");
            // $printer->feed(2);
            // // $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            // $printer->setEmphasis(true);
            // $printer->text($perusahaan . ".\n");
            // $printer->text($cabang . ".\n");
            // $printer->setEmphasis(false);
            // $printer->selectPrintMode();
            // $printer->text($alamat . ".\n");
            // $printer->text($date . "\n");


            // /* Title of receipt */
            // $printer->setEmphasis(true);
            // $printer->text("LEMBAR UNTUK PERUSAHAAN\n");
            // $printer->setEmphasis(false);

            // /* Items */
            // $printer->setJustification(Printer::JUSTIFY_LEFT);
            // $printer->setEmphasis(true);
            // $printer->text(new item('', ''));
            // $pelanggan_salesman = new item($faktur->no_fak_penj, $faktur->nama_karyawan);
            // $printer->text($pelanggan_salesman->getAsString(32));
            // $printer->text(date("d-m-Y H:i:s", strtotime($faktur->date_created)) . "\n");
            // $printer->text($faktur->kode_pelanggan . " - " . $faktur->nama_pelanggan . "\n");
            // $printer->text(strtolower(ucwords($faktur->alamat_pelanggan)));
            // $printer->text(new item('', ''));

            // $printer->setEmphasis(true);
            // foreach ($items as $item) {
            //     $printer->text($item->getAsString(32)); // for 58mm Font A
            // }

            // $subtotal = new item('Subtotal', rupiah($faktur->subtotal));
            // $potongan = new item('Potongan', rupiah($faktur->potongan));
            // $totalnonppn = $faktur->subtotal - $faktur->potongan - $faktur->potistimewa - $faktur->penyharga;
            // $total = new item('Total', rupiah($totalnonppn));
            // if (!empty($faktur->ppn)) {
            //     $ppn = new item('PPN', rupiah($faktur->ppn));
            // }
            // $_grandtotal = $faktur->total - $totalretur;
            // $retur = new item('Retur', rupiah($totalretur));
            // $grandtotal = new item('Grand Total', rupiah($_grandtotal));
            // //$total = new item('Total', '14.25', true);


            // $printer->setEmphasis(true);
            // $printer->text($subtotal->getAsString(32));
            // $printer->setEmphasis(false);
            // $printer->feed();

            // /* Tax and total */
            // $printer->text($potongan->getAsString(32));
            // $printer->text($total->getAsString(32));
            // if (!empty($faktur->ppn)) {
            //     $printer->text($ppn->getAsString(32));
            // }
            // $printer->text($retur->getAsString(32));
            // // $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            // $printer->feed();
            // $printer->setEmphasis(true);
            // $printer->text($grandtotal->getAsString(32));
            // $printer->feed();
            // $printer->setJustification(Printer::JUSTIFY_CENTER);
            // $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            // $printer->text(strtoupper($faktur->jenistransaksi) . ".\n");
            // $printer->selectPrintMode();

            // if (!empty($cekpembayaran)) {
            //     $printer->feed();
            //     $printer->setJustification(Printer::JUSTIFY_LEFT);
            //     $printer->text("PEMBAYARAN \n");
            //     $printer->setEmphasis(true);
            //     foreach ($itemsbayar as $itembayar) {
            //         $printer->text($itembayar->getAsString(32)); // for 58mm Font A
            //     }
            //     $grandtotalbayar = new item('TOTAL BAYAR', rupiah($totalbayar));
            //     $sisatagihan = $faktur->total - $totalretur - $totalbayar;
            //     $sisa = new item('SISA TAGIHAN', rupiah($sisatagihan));
            //     $printer->text($grandtotalbayar->getAsString(32)); // for 58mm Font A
            //     $printer->text($sisa->getAsString(32)); // for 58mm Font A
            // }

            // /* Footer */
            // $printer->feed(2);
            // $printer->setJustification(Printer::JUSTIFY_CENTER);
            // $printer->text("Tidak Di Perkenankan Transfer \n");
            // $printer->text("Ke Rekening Salesman \n");
            // $printer->text("Apapun Jenis Transaksinya \n");
            // $printer->text("Wajib Ditandatangani \n");
            // $printer->text("kedua belah pihak,\n");
            // $printer->text("Terimakasih\n");
            // $printer->text("www.pedasalami.com\n");
            // $printer->feed();

            // if (!empty($faktur->signature)) {
            //     $urlsignature = base_path('/public/storage/signature/') . $faktur->signature;
            //     $signature = EscposImage::load($urlsignature, false);
            //     /* Print top logo */
            //     if ($profile->getSupportsGraphics()) {
            //         $printer->graphics($signature);
            //     }
            //     if ($profile->getSupportsBitImageRaster() && !$profile->getSupportsGraphics()) {
            //         $printer->bitImage($signature);
            //     }
            // }




            // /* Barcode Default look */

            // $printer->barcode("ABC", Printer::BARCODE_CODE39);
            // $printer->feed();
            // $printer->feed();


            // // Demo that alignment QRcode is the same as text
            // $printer2 = new Printer($connector); // dirty printer profile hack !!
            // $printer2->setJustification(Printer::JUSTIFY_CENTER);
            // $printer2->qrCode("https://rawbt.ru/mike42", Printer::QR_ECLEVEL_M, 8);
            // $printer2->text("rawbt.ru/mike42\n");
            // $printer2->setJustification();
            // $printer2->feed();


            /* Cut the receipt and open the cash drawer */
            $printer->cut();
            $printer->pulse();
        } catch (Exception $e) {
            echo $e->getMessage();
        } finally {
            $printer->close();
        }
    }
}
