<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Checkinpenjualan;
use App\Models\Detailgiro;
use App\Models\Detailpenjualan;
use App\Models\Detailretur;
use App\Models\Detailtransfer;
use App\Models\Diskon;
use App\Models\Harga;
use App\Models\Historibayarpenjualan;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Retur;
use App\Models\Salesman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {

        $start_date = config('global.start_date');
        $end_date = config('global.end_date');
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');

        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }
        $query = Penjualan::query();
        $query->select(
            'marketing_penjualan.*',
            'nama_pelanggan',
            'nama_salesman',
            'nama_cabang'
        );
        $query->addSelect(DB::raw('(SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) as total_bruto'));
        $query->addSelect(DB::raw('(SELECT SUM(subtotal) FROM marketing_retur_detail
        INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur
        WHERE no_faktur = marketing_penjualan.no_faktur AND jenis_retur="PF") as total_retur'));
        $query->addSelect(DB::raw('(SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE no_faktur = marketing_penjualan.no_faktur) as total_bayar'));
        $query->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $query->leftJoin(
            DB::raw("(
                SELECT
                    marketing_penjualan.no_faktur,
                    IF( salesbaru IS NULL, marketing_penjualan.kode_salesman, salesbaru ) AS kode_salesman_baru,
                    IF( cabangbaru IS NULL, salesman.kode_cabang, cabangbaru ) AS kode_cabang_baru
                FROM
                    marketing_penjualan
                INNER JOIN salesman ON marketing_penjualan.kode_salesman = salesman.kode_salesman
                LEFT JOIN (
                SELECT
                    no_faktur,
                    marketing_penjualan_movefaktur.kode_salesman_baru AS salesbaru,
                    salesman.kode_cabang AS cabangbaru
                FROM
                    marketing_penjualan_movefaktur
                    INNER JOIN salesman ON marketing_penjualan_movefaktur.kode_salesman_baru = salesman.kode_salesman
                WHERE id IN (SELECT MAX(id) as id FROM marketing_penjualan_movefaktur GROUP BY no_faktur)
                ) movefaktur ON ( marketing_penjualan.no_faktur = movefaktur.no_faktur)
            ) pindahfaktur"),
            function ($join) {
                $join->on('marketing_penjualan.no_faktur', '=', 'pindahfaktur.no_faktur');
            }
        );

        $query->join('salesman', 'pindahfaktur.kode_salesman_baru', '=', 'salesman.kode_salesman');
        $query->join('cabang', 'pindahfaktur.kode_cabang_baru', '=', 'cabang.kode_cabang');

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('kode_cabang_baru', auth()->user()->kode_cabang);
            }
        }

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('marketing_penjualan.tanggal', [$request->dari, $request->sampai]);
        } else {
            $query->whereBetween('marketing_penjualan.tanggal', [$start_date, $end_date]);
        }

        if (!empty($request->no_faktur_search)) {
            $query->where('marketing_penjualan.no_faktur', $request->no_faktur_search);
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('kode_cabang_baru', $request->kode_cabang_search);
        }

        if (!empty($request->kode_salesman_search)) {
            $query->where('kode_salesman_baru', $request->kode_salesman_search);
        }

        if (!empty($request->kode_pelanggan_search)) {
            $query->where('marketing_penjualan.kode_pelanggan', $request->kode_pelanggan_search);
        }


        if (!empty($request->nama_pelanggan_search)) {
            $query->WhereRaw("MATCH(nama_pelanggan) AGAINST('" . $request->nama_pelanggan_search .  "')");
        }

        $query->orderBy('marketing_penjualan.tanggal', 'desc');
        $query->orderBy('marketing_penjualan.no_faktur', 'desc');
        $penjualan = $query->cursorPaginate();
        $penjualan->appends(request()->all());
        $data['penjualan'] = $penjualan;
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('marketing.penjualan.index', $data);
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
                ->addColumn('action', function ($item) {
                    $button =   '<a href="#" kode_pelanggan="' . Crypt::encrypt($item->kode_pelanggan) . '" class="pilihpelanggan"><i class="ti ti-external-link"></i></a>';
                    return $button;
                })
                ->make();
        }

        $diskon = Diskon::orderBy('kode_kategori_diskon')->get();
        $diskon_json = json_encode($diskon);
        $data['diskon'] = $diskon_json;
        return view('marketing.penjualan.create', $data);
    }



    public function cetakfaktur($no_faktur)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        $pnj = new Penjualan();
        $penjualan = $pnj->getFaktur($no_faktur);
        $data['penjualan'] = $penjualan;

        $detailpenjualan = new Penjualan();
        $data['detail'] = $detailpenjualan->getDetailpenjualan($no_faktur);

        return view('marketing.penjualan.cetakfaktur', $data);
    }


    public function cetaksuratjalan($type, $no_faktur)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        $pnj = new Penjualan();
        $penjualan = $pnj->getFaktur($no_faktur);
        $data['penjualan'] = $penjualan;

        $detailpenjualan = new Penjualan();
        $data['detail'] = $detailpenjualan->getDetailpenjualan($no_faktur);
        if ($type == 1) {
            return view('marketing.penjualan.cetaksuratjalan1', $data);
        } else {
            return view('marketing.penjualan.cetaksuratjalan2', $data);
        }
    }


    public function filtersuratjalan()
    {
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('marketing.penjualan.cetaksuratjalan_filter', $data);
    }


    public function cetaksuratjalanrange(Request $request)
    {
        $pnj = new Penjualan();
        $penjualan = $pnj->getFakturwithDetail($request);
        $data['pj'] = $penjualan;

        return view('marketing.penjualan.cetaksuratjalan_range', $data);
    }


    public function batalfaktur($no_faktur)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        $pnj = new Penjualan();
        $data['penjualan'] = $pnj->getFaktur($no_faktur);
        return view('marketing.penjualan.batalkanfaktur', $data);
    }

    public function updatefakturbatal($no_faktur, Request $request)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        try {
            Penjualan::where('no_faktur', $no_faktur)->update([
                'status_batal' => 1,
                'keterangan' => $request->keterangan,
            ]);
            return Redirect::back()->with(messageSuccess('Faktur Berhasil Dibatalkan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function generatefaktur($no_faktur)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        $penjualan = Penjualan::where('no_faktur', $no_faktur)->first();
        $tanggal = $penjualan->tanggal;
        $kode_salesman = $penjualan->kode_salesman;
        //$id_karyawan = "SBDG09";
        $salesman = Salesman::where('kode_salesman', $penjualan->kode_salesman)
            ->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();



        $lastpenjualan = Penjualan::where('kode_salesman', $penjualan->kode_salesman)
            ->where('tanggal', $penjualan->tanggal)
            ->whereRaw('MID(no_faktur,4,2) != "PR"')
            ->orderBy('tanggal', 'desc')->first();

        $lasttanggal = $lastpenjualan != null ? $penjualan->tanggal : date('Y-m-d', strtotime("-3 day", strtotime($penjualan->tanggal)));


        // $start_date = date('Y-m-d', strtotime("-1 month", strtotime(date('Y-m-d'))));
        // $end_date = date('Y-m-t');

        $cekpenjualan = Penjualan::where('kode_salesman', $penjualan->kode_salesman)
            ->where('tanggal', '>=', $penjualan->tanggal)
            ->whereRaw('MID(no_faktur,4,2) != "PR"')
            ->orderBy('no_faktur', 'desc')
            ->first();



        $last_no_faktur = $cekpenjualan != null ? $cekpenjualan->no_faktur : '';


        // echo $lastnofak;
        // die;
        $kode_cabang = $salesman->kode_cabang;
        $kode_faktur = substr($cekpenjualan->no_faktur, 3, 1);
        $nomor_awal = substr($cekpenjualan->no_faktur, 4);
        $jmlchar = strlen($nomor_awal);
        $no_faktur_auto  =  buatkode($last_no_faktur, $kode_cabang . $kode_faktur, $jmlchar);

        $kode_sales = $salesman->kode_sales;
        $kode_pt = $salesman->kode_pt;

        $tahun = date('y', strtotime($penjualan->tanggal));
        $thn = date('Y', strtotime($penjualan->tanggal));

        $start_date = "2024-03-01";
        if ($penjualan->tanggal >= '2024-03-01') {
            $lastransaksi = Penjualan::join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
                ->where('tanggal', '>=', $start_date)
                ->where('kode_sales', $kode_sales)
                ->where('kode_cabang', $kode_cabang)
                ->whereRaw('YEAR(tanggal)="' . $thn . '"')
                ->whereRaw('LEFT(no_faktur,3)="' . $kode_pt . '"')
                ->orderBy('no_faktur', 'desc')
                ->first();
            $last_no_faktur = $lastransaksi != NULL ? $lastransaksi->no_faktur : "";
            $no_faktur_auto = buatkode($last_no_faktur, $kode_pt . $tahun . $kode_sales, 6);
        }

        // echo $no_fak_penj_auto;
        // die;
        try {

            Penjualan::where('no_faktur', $no_faktur)
                ->update([
                    'no_faktur' => $no_faktur_auto
                ]);
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            dd($e);
            return Redirect::back()->with(['warning' => 'No. Faktur Gagal Dibuat']);
        }
    }


    public function editproduk(Request $request)
    {
        $dataproduk = $request->dataproduk;
        $data['dataproduk'] = $dataproduk;

        $hrg = new Harga();
        $data['harga'] = $hrg->getHargabypelanggan($dataproduk['kode_pelanggan']);
        return view('marketing.penjualan.editproduk', $data);
    }


    public function generatenofaktur(Request $request)
    {
        $salesman = Salesman::join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang')
            ->where('kode_salesman', $request->kode_salesman)->first();
        $tahun = date('y', strtotime($request->tanggal));
        $thn = date('Y', strtotime($request->tanggal));
        $start_date = "2024-03-01";
        if ($request->tanggal >= '2024-03-01' && $salesman->kode_cabang != "PST") {
            $lastransaksi = Penjualan::join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
                ->where('tanggal', '>=', $start_date)
                ->where('kode_sales', $salesman->kode_sales)
                ->where('salesman.kode_cabang', $salesman->kode_cabang)
                ->whereRaw('YEAR(tanggal)="' . $thn . '"')
                ->whereRaw('LEFT(no_faktur,3)="' . $salesman->kode_pt . '"')
                ->orderBy('no_faktur', 'desc')
                ->first();
            $last_no_faktur = $lastransaksi != NULL ? $lastransaksi->no_faktur : "";
            $no_faktur = buatkode($last_no_faktur, $salesman->kode_pt . $tahun . $salesman->kode_sales, 6);
            return $no_faktur;
        } else {
            return 0;
        }
    }


    public function store(Request $request)
    {
        $request->validate([
            'no_faktur' => 'required',
            'tanggal' => 'required',
            'kode_pelanggan' => 'required',
            'kode_salesman' => 'required',
            'jenis_transaksi' => 'required'
        ]);

        //No. Faktur Otomatis
        $salesman = Salesman::join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang')
            ->where('kode_salesman', $request->kode_salesman)->first();
        $tahun = date('y', strtotime($request->tanggal));
        $thn = date('Y', strtotime($request->tanggal));
        $start_date = "2024-03-01";

        $tanggal = $request->tanggal;
        $kode_pelanggan = $request->kode_pelanggan;
        $kode_salesman = $request->kode_salesman;
        $keterangan = $request->keterangan;
        //Potongan
        $potongan_aida = toNumber($request->potongan_aida);
        $potongan_swan = toNumber($request->potongan_swan);
        $potongan_stick = toNumber($request->potongan_stick);
        $potongan_sambal = toNumber($request->potongan_sambal);
        $total_potongan =  $potongan_aida + $potongan_swan + $potongan_stick + $potongan_sambal;

        //Potongan Istimewa
        $potis_aida = toNumber($request->potis_aida);
        $potis_swan = toNumber($request->potis_swan);
        $potis_stick = toNumber($request->potis_stick);
        $total_potongan_istimewa = $potis_aida + $potis_swan + $potis_stick;

        //Penyesuaian
        $peny_aida = toNumber($request->peny_aida);
        $peny_swan = toNumber($request->peny_swan);
        $peny_stick = toNumber(($request->peny_stick));
        $total_penyesuaian = $peny_aida + $peny_swan + $peny_stick;

        $jenis_transaksi = $request->jenis_transaksi;
        $jenis_bayar = $jenis_transaksi == "K" ? "TP" : $request->jenis_bayar;
        $titipan = $jenis_transaksi == "T" ? 0 : toNumber($request->titipan);
        $voucher = $jenis_transaksi == "K" ? 0 : toNumber($request->voucher);

        $pelanggan = Pelanggan::where('kode_pelanggan', $kode_pelanggan)->first();

        $ljt = !empty($pelanggan->ljt) ? $pelanggan->ljt : 14;
        $jatuh_tempo = date("Y-m-d", strtotime("+$ljt day", strtotime($tanggal)));


        //Detail Produk
        $kode_harga = $request->kode_harga_produk;
        $isi_pcs_dus = $request->isi_pcs_dus_produk;
        $isi_pcs_pack = $request->isi_pcs_pack_produk;
        $hargadus = $request->harga_dus_produk;
        $hargapack = $request->harga_pack_produk;
        $hargapcs = $request->harga_pcs_produk;
        $jumlah = $request->jumlah_produk;
        $status_promosi = $request->status_promosi_produk;
        $total_bruto = 0;


        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }
            //No. Faktur
            if ($request->tanggal >= '2024-03-01' && $salesman->kode_cabang != "PST") {
                $lastransaksi = Penjualan::join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
                    ->where('tanggal', '>=', $start_date)
                    ->where('kode_sales', $salesman->kode_sales)
                    ->where('salesman.kode_cabang', $salesman->kode_cabang)
                    ->whereRaw('YEAR(tanggal)="' . $thn . '"')
                    ->whereRaw('LEFT(no_faktur,3)="' . $salesman->kode_pt . '"')
                    ->orderBy('no_faktur', 'desc')
                    ->first();
                $last_no_faktur = $lastransaksi != NULL ? $lastransaksi->no_faktur : "";
                $no_faktur = buatkode($last_no_faktur, $salesman->kode_pt . $tahun . $salesman->kode_sales, 6);
            } else {
                $no_faktur =  $request->no_faktur;
            }

            $ceknofaktur = Penjualan::where('no_faktur', $no_faktur)->count();
            if ($ceknofaktur > 0) {
                return Redirect::back()->with(messageError('No. Faktur Suda Digunakan'));
            }


            for ($i = 0; $i < count($kode_harga); $i++) {

                $jml = convertToduspackpcsv3($isi_pcs_dus[$i], $isi_pcs_pack[$i], $jumlah[$i]);
                $jml_dus = $jml[0];
                $jml_pack = $jml[1];
                $jml_pcs = $jml[2];
                $harga_dus = toNumber($hargadus[$i]);
                $harga_pack = toNumber($hargapack[$i]);
                $harga_pcs = toNumber($hargapcs[$i]);
                $subtotal = ($jml_dus * $harga_dus) + ($jml_pack * $harga_pack) + ($jml_pcs * $harga_pcs);
                $total_bruto += $subtotal;
                $detail[] = [
                    'no_faktur' => $no_faktur,
                    'kode_harga' => $kode_harga[$i],
                    'jumlah' => $jumlah[$i],
                    'harga_dus' => $harga_dus,
                    'harga_pack' => $harga_pack,
                    'harga_pcs' => $harga_pcs,
                    'subtotal' => $subtotal,
                    'status_promosi' => $status_promosi[$i]
                ];
            }



            $penjualan = new Penjualan();
            $sisa_piutang = $penjualan->getPiutangpelanggan($kode_pelanggan);

            $faktur_kredit = $penjualan->getFakturkredit($kode_pelanggan);
            $max_faktur = $faktur_kredit['jml_faktur'];
            $unpaid_faktur = $faktur_kredit['unpaid_faktur'];
            $siklus_pembayaran = $faktur_kredit['siklus_pembayaran'];

            $total_netto = $total_bruto - $total_potongan - $total_potongan_istimewa - $total_penyesuaian;
            $total_piutang = $sisa_piutang + $total_netto;

            if ($jenis_transaksi == 'K' && $total_piutang > $pelanggan->limit_pelanggan &&  $siklus_pembayaran === '0') {
                return Redirect::back()->with(messageError('Melebihi Limit, Silahkan Ajukan Penambahan Limit'));
            } else if ($jenis_transaksi == 'K' && $total_netto > $pelanggan->limit_pelanggan && $siklus_pembayaran == '1') {
                return Redirect::back()->with(messageError('Melebihi Limit, Silahkan Ajukan Penambahan Limit'));
            } else if ($unpaid_faktur > $max_faktur && $siklus_pembayaran === '0') {
                return Redirect::back()->with(messageError('Melebihi Jumlah Faktur Kredit'));
            }



            //No. Bukti Pembayaran
            $lastbayar = Historibayarpenjualan::whereRaw('LEFT(no_bukti,6) = "' . $salesman->kode_cabang . date('y') . '-"')
                ->orderBy("no_bukti", "desc")
                ->first();
            $last_no_bukti = $lastbayar != null ? $lastbayar->no_bukti : '';
            $no_bukti  = buatkode($last_no_bukti, $salesman->kode_cabang . date('y') . "-", 6);


            //Insert Penjualan
            Penjualan::create([
                'no_faktur' => $no_faktur,
                'tanggal' => $tanggal,
                'kode_pelanggan' => $kode_pelanggan,
                'kode_salesman' => $kode_salesman,
                'keterangan' => $keterangan,

                'potongan_aida' => $potongan_aida,
                'potongan_swan' => $potongan_swan,
                'potongan_stick' => $potongan_stick,
                'potongan_sambal' => $potongan_sambal,
                'potongan' => $total_potongan,

                'potis_aida' => $potis_aida,
                'potis_swan' => $potis_swan,
                'potis_stick' => $potis_stick,
                'potongan_istimewa' => $total_potongan_istimewa,

                'peny_aida' => $peny_aida,
                'peny_swan' => $peny_swan,
                'peny_stick' => $peny_stick,
                'penyesuaian' => $total_penyesuaian,

                'jenis_transaksi' => $jenis_transaksi,
                'jenis_bayar' => $jenis_bayar,

                'jatuh_tempo' => $jatuh_tempo,
                'id_user' => auth()->user()->id

            ]);


            Detailpenjualan::insert($detail);

            //Pembayaran

            //Jika Transaksi Tunai
            if ($jenis_transaksi == "T") {

                if ($jenis_bayar == "TN") {
                    Historibayarpenjualan::create([
                        'no_bukti' => $no_bukti,
                        'no_faktur' => $no_faktur,
                        'tanggal' => $tanggal,
                        'jenis_bayar' => $jenis_bayar,
                        'jumlah' => $total_netto - $voucher,
                        'kode_salesman' => $kode_salesman,
                        'id_user' => auth()->user()->id
                    ]);
                }


                //Jika Ada Voucher
                if (!empty($voucher)) {
                    Historibayarpenjualan::create([
                        'no_bukti' => $jenis_bayar == 'TR' ? $no_bukti : buatkode($no_bukti, $salesman->kode_cabang . date('y') . "-", 6),
                        'no_faktur' => $no_faktur,
                        'tanggal' => $tanggal,
                        'jenis_bayar' => $jenis_bayar,
                        'jumlah' => $voucher,
                        'voucher' => 1,
                        'jenis_voucher' => 2,
                        'kode_salesman' => $kode_salesman,
                        'id_user' => auth()->user()->id
                    ]);
                }
            } else {
                if (!empty($titipan)) {
                    Historibayarpenjualan::create([
                        'no_bukti' => $no_bukti,
                        'no_faktur' => $no_faktur,
                        'tanggal' => $tanggal,
                        'jenis_bayar' => $jenis_bayar,
                        'jumlah' => $titipan,
                        'kode_salesman' => $kode_salesman,
                        'id_user' => auth()->user()->id
                    ]);
                }
            }
            DB::commit();
            return redirect(route('penjualan.show', Crypt::encrypt($no_faktur)))->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            //throw $th;
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
            //dd($e);
        }
    }

    public function edit($no_faktur)
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
        return view('marketing.penjualan.edit', $data);
    }
    public function show($no_faktur)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        $data['kepemilikan'] = config('pelanggan.kepemilikan');
        $data['lama_berjualan'] = config('pelanggan.lama_berjualan');
        $data['status_outlet'] = config('pelanggan.status_outlet');
        $data['type_outlet'] = config('pelanggan.type_outlet');
        $data['cara_pembayaran'] = config('pelanggan.cara_pembayaran');
        $data['lama_langganan'] = config('pelanggan.lama_langganan');
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
        return view('marketing.penjualan.show', $data);
    }

    public function update($no_faktur, Request $request)
    {

        $request->validate([
            'tanggal' => 'required'
        ]);
        $no_faktur = Crypt::decrypt($no_faktur);
        $pj = new Penjualan();
        $penjualan = $pj->getFaktur($no_faktur);
        $jenis_transaksi = $penjualan->jenis_transaksi;
        $jenis_bayar = $penjualan->jenis_bayar;
        $titipan = $jenis_transaksi == "T" ? 0 : toNumber($request->titipan);
        $voucher = $jenis_transaksi == "K" ? 0 : toNumber($request->voucher);
        $keterangan = $request->keterangan;
        $total_netto_penjualan = $penjualan->total_bruto - $penjualan->total_retur - $penjualan->potongan - $penjualan->potongan_istimewa - $penjualan->penyesuaian + $penjualan->ppn;
        $total_bruto = 0;
        //Salesman
        $salesman = Salesman::where('kode_salesman', $penjualan->kode_salesman)->first();

        //Pelanggan
        $pelanggan = Pelanggan::where('kode_pelanggan', $penjualan->kode_pelanggan)->first();
        //Potongan
        $potongan_aida = toNumber($request->potongan_aida);
        $potongan_swan = toNumber($request->potongan_swan);
        $potongan_stick = toNumber($request->potongan_stick);
        $potongan_sambal = toNumber($request->potongan_sambal);
        $total_potongan =  $potongan_aida + $potongan_swan + $potongan_stick + $potongan_sambal;

        //Potongan Istimewa
        $potis_aida = toNumber($request->potis_aida);
        $potis_swan = toNumber($request->potis_swan);
        $potis_stick = toNumber($request->potis_stick);
        $total_potongan_istimewa = $potis_aida + $potis_swan + $potis_stick;

        //Penyesuaian
        $peny_aida = toNumber($request->peny_aida);
        $peny_swan = toNumber($request->peny_swan);
        $peny_stick = toNumber(($request->peny_stick));
        $total_penyesuaian = $peny_aida + $peny_swan + $peny_stick;

        $ljt = !empty($pelanggan->ljt) ? $pelanggan->ljt : 14;
        $jatuh_tempo = date("Y-m-d", strtotime("+$ljt day", strtotime($request->tanggal)));


        //Detail Produk
        $kode_harga = $request->kode_harga_produk;
        $isi_pcs_dus = $request->isi_pcs_dus_produk;
        $isi_pcs_pack = $request->isi_pcs_pack_produk;
        $hargadus = $request->harga_dus_produk;
        $hargapack = $request->harga_pack_produk;
        $hargapcs = $request->harga_pcs_produk;
        $jumlah = $request->jumlah_produk;
        $status_promosi = $request->status_promosi_produk;
        $total_bruto = 0;

        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($request->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $cektutuplaporanpenjualan = cektutupLaporan($penjualan->tanggal, "penjualan");
            if ($cektutuplaporanpenjualan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            for ($i = 0; $i < count($kode_harga); $i++) {

                $jml = convertToduspackpcsv3($isi_pcs_dus[$i], $isi_pcs_pack[$i], $jumlah[$i]);
                $jml_dus = $jml[0];
                $jml_pack = $jml[1];
                $jml_pcs = $jml[2];
                $harga_dus = toNumber($hargadus[$i]);
                $harga_pack = toNumber($hargapack[$i]);
                $harga_pcs = toNumber($hargapcs[$i]);
                $subtotal = ($jml_dus * $harga_dus) + ($jml_pack * $harga_pack) + ($jml_pcs * $harga_pcs);
                $total_bruto += $subtotal;
                $detail[] = [
                    'no_faktur' => $no_faktur,
                    'kode_harga' => $kode_harga[$i],
                    'jumlah' => $jumlah[$i],
                    'harga_dus' => $harga_dus,
                    'harga_pack' => $harga_pack,
                    'harga_pcs' => $harga_pcs,
                    'subtotal' => $subtotal,
                    'status_promosi' => $status_promosi[$i]
                ];
            }

            $sisa_piutang = $penjualan->getPiutangpelanggan($penjualan->kode_pelanggan) - $total_netto_penjualan;

            $faktur_kredit = $penjualan->getFakturkredit($penjualan->kode_pelanggan);
            $max_faktur = $faktur_kredit['jml_faktur'];
            $unpaid_faktur = $faktur_kredit['unpaid_faktur'] - 1;
            $siklus_pembayaran = $faktur_kredit['siklus_pembayaran'];

            $total_netto = $total_bruto - $total_potongan - $total_potongan_istimewa - $total_penyesuaian;
            $total_piutang = $sisa_piutang + $total_netto;

            if ($jenis_transaksi == 'K' && $total_piutang > $pelanggan->limit_pelanggan &&  $siklus_pembayaran === '0') {
                return Redirect::back()->with(messageError('Melebihi Limit, Silahkan Ajukan Penambahan Limit'));
            } else if ($jenis_transaksi == 'K' && $total_netto > $pelanggan->limit_pelanggan && $siklus_pembayaran == '1') {
                return Redirect::back()->with(messageError('Melebihi Limit, Silahkan Ajukan Penambahan Limit'));
            } else if ($unpaid_faktur > $max_faktur && $siklus_pembayaran === '0') {
                return Redirect::back()->with(messageError('Melebihi Jumlah Faktur Kredit'));
            }

            //No. Bukti Pembayaran
            $lastbayar = Historibayarpenjualan::whereRaw('LEFT(no_bukti,6) = "' . $salesman->kode_cabang . date('y') . '-"')
                ->orderBy("no_bukti", "desc")
                ->first();
            $last_no_bukti = $lastbayar != null ? $lastbayar->no_bukti : '';
            $no_bukti  = buatkode($last_no_bukti, $salesman->kode_cabang . date('y') . "-", 6);
            //Update Penjualan
            Penjualan::where('no_faktur', $no_faktur)->update([
                'tanggal' => $request->tanggal,
                'keterangan' => $keterangan,

                'potongan_aida' => $potongan_aida,
                'potongan_swan' => $potongan_swan,
                'potongan_stick' => $potongan_stick,
                'potongan_sambal' => $potongan_sambal,
                'potongan' => $total_potongan,

                'potis_aida' => $potis_aida,
                'potis_swan' => $potis_swan,
                'potis_stick' => $potis_stick,
                'potongan_istimewa' => $total_potongan_istimewa,

                'peny_aida' => $peny_aida,
                'peny_swan' => $peny_swan,
                'peny_stick' => $peny_stick,
                'penyesuaian' => $total_penyesuaian,

                'jatuh_tempo' => $jatuh_tempo,
                'id_user' => auth()->user()->id
            ]);

            //Hapus Detail Penjualan Sebelmnya
            Detailpenjualan::where('no_faktur', $no_faktur)->delete();
            Detailpenjualan::insert($detail);

            $retur = Detailretur::select(DB::raw("SUM(subtotal) as total_retur"))
                ->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur')
                ->where('no_faktur', $no_faktur)
                ->where('jenis_retur', 'PF')
                ->first();
            if ($jenis_transaksi == 'T') {
                $cekbayar = Historibayarpenjualan::where('no_faktur', $no_faktur)
                    ->where('voucher', 0)
                    ->where('tanggal', $request->tanggal)
                    ->orderBy('no_bukti')
                    ->first();
                $cekvoucher = Historibayarpenjualan::where('no_faktur', $no_faktur)
                    ->where('voucher', 1)
                    ->where('jenis_voucher', 2)
                    ->where('jenis_bayar', 'TN')
                    ->where('tanggal', $request->tanggal)
                    ->orderBy('no_bukti')
                    ->first();


                if ($jenis_bayar == "TN") {
                    if ($cekbayar != null) {
                        Historibayarpenjualan::where('no_bukti', $cekbayar->no_bukti)->update([
                            'tanggal' => $request->tanggal,
                            'jumlah' => $total_netto - $voucher - $retur->total_retur,
                            'id_user' => auth()->user()->id
                        ]);
                    }
                }

                //Jika Ada Voucher
                if (!empty($voucher)) {
                    if ($cekvoucher != null) {
                        Historibayarpenjualan::where('no_bukti', $cekvoucher->no_bukti)
                            ->update([
                                'tanggal' => $request->tanggal,
                                'jenis_bayar' => 'TN',
                                'jumlah' => $voucher,
                                'id_user' => auth()->user()->id
                            ]);
                    } else {
                        Historibayarpenjualan::create([
                            'no_bukti' => $jenis_bayar == 'TR' ? $no_bukti : buatkode($no_bukti, $salesman->kode_cabang . date('y') . "-", 6),
                            'no_faktur' => $no_faktur,
                            'tanggal' => $request->tanggal,
                            'jenis_bayar' => $jenis_bayar,
                            'jumlah' => $voucher,
                            'voucher' => 1,
                            'jenis_voucher' => 2,
                            'kode_salesman' => $penjualan->kode_salesman,
                            'id_user' => auth()->user()->id
                        ]);
                    }
                } else {
                    if ($cekvoucher != null) {
                        Historibayarpenjualan::where('no_bukti', $cekvoucher->no_bukti)->delete();
                    }
                }
            } else {
                $cektitipan = Historibayarpenjualan::where('no_faktur', $no_faktur)
                    ->where('voucher', 0)
                    ->where('tanggal', $request->tanggal)
                    ->orderBy('no_bukti')
                    ->first();
                if ($cektitipan != null) {
                    $no_bukti_titipan = $cektitipan->no_bukti;
                    //Update Titipan
                    Historibayarpenjualan::where('no_bukti', $no_bukti_titipan)->update([
                        'tanggal' => $request->tanggal,
                        'jumlah' => $titipan,
                        'id_user' => auth()->user()->id
                    ]);
                } else {
                    if (!empty($titipan)) {
                        Historibayarpenjualan::create([
                            'no_bukti' => $no_bukti,
                            'no_faktur' => $no_faktur,
                            'tanggal' => $request->tanggal,
                            'jenis_bayar' => $jenis_bayar,
                            'jumlah' => $titipan,
                            'kode_salesman' => $penjualan->kode_salesman,
                            'id_user' => auth()->user()->id
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect(route('penjualan.show', Crypt::encrypt($no_faktur)))->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            //throw $th;
        }
    }

    public function getfakturbypelanggan(Request $request)
    {
        $kode_pelanggan  = $request->kode_pelanggan;
        $listfaktur = Penjualan::where('kode_pelanggan', $kode_pelanggan)->orderBy('created_at', 'desc')->limit(5)->get();
        echo "<option value=''>Pilih Faktur</option>";
        foreach ($listfaktur as $d) {
            echo "<option value='$d->no_faktur'>$d->no_faktur</option>";
        }
    }

    public function getpiutangfaktur($no_faktur)
    {
        $pj = new Penjualan();
        $penjualan = $pj->getpiutangFaktur($no_faktur)->first();
        return response()->json([
            'success' => true,
            'message' => 'Piutang Faktur',
            'data'    => $penjualan
        ]);
    }
    public function destroy($no_faktur)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        $penjualan = Penjualan::where('no_faktur', $no_faktur)->first();
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($penjualan->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            //Hapus Surat Jalan
            Penjualan::where('no_faktur', $no_faktur)->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
