<?php

namespace App\Http\Controllers;

use App\Models\Barangpembelian;
use App\Models\Cabang;
use App\Models\Coadepartemen;
use App\Models\Costratio;
use App\Models\Detailkontrabonpembelian;
use App\Models\Detailpembelian;
use App\Models\Kontrabonpembelian;
use App\Models\Pembelian;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Redis;

class PembelianController extends Controller
{
    public function index(Request $request)
    {

        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }

        $pmb = new Pembelian();
        $pembelian = $pmb->getPembelian(request: $request)->paginate(15);
        $pembelian->appends(request()->all());
        $data['pembelian'] = $pembelian;

        $data['asal_ajuan'] = config('pembelian.list_asal_pengajuan');
        $data['supplier'] = Supplier::orderBy('nama_supplier')->get();
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('pembelian.index', $data);
    }


    public function show($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $pmb = new Pembelian();
        $data['pembelian'] = $pmb->getPembelian(no_bukti: $no_bukti)->first();

        $data['detail'] = Detailpembelian::select('pembelian_detail.*', 'nama_barang')
            ->join('pembelian_barang', 'pembelian_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->where('no_bukti', $no_bukti)
            ->where('pembelian_detail.kode_transaksi', 'PMB')
            ->get();

        $data['potongan'] = Detailpembelian::select('pembelian_detail.*', 'nama_barang')
            ->join('pembelian_barang', 'pembelian_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->where('no_bukti', $no_bukti)
            ->where('pembelian_detail.kode_transaksi', 'PNJ')
            ->get();

        $data['kontrabon'] = Detailkontrabonpembelian::select(
            'pembelian_kontrabon_detail.*',
            'pembelian_kontrabon.tanggal as tanggal_kontrabon',
            'kategori',
            'pembelian_historibayar.tanggal as tanggal_bayar'
        )
            ->join('pembelian_kontrabon', 'pembelian_kontrabon_detail.no_kontrabon', '=', 'pembelian_kontrabon.no_kontrabon')
            ->leftjoin('pembelian_historibayar', 'pembelian_historibayar.no_kontrabon', '=', 'pembelian_kontrabon.no_kontrabon')
            ->where('no_bukti', $no_bukti)
            ->orderBy('pembelian_kontrabon.tanggal', 'desc')
            ->get();


        $data['asal_pengajuan'] = config('pembelian.asal_pengajuan');
        return view('pembelian.show', $data);
    }


    public function create()
    {
        $data['supplier'] = Supplier::orderBy('nama_supplier')->get();
        $data['asal_ajuan'] = config('pembelian.list_asal_pengajuan');
        $data['coa'] = Coadepartemen::where('kode_dept', 'PMB')
            ->join('coa', 'coa_departemen.kode_akun', '=', 'coa.kode_akun')
            ->orderBy('coa_departemen.kode_akun')
            ->get();

        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        return view('pembelian.create', $data);
    }



    public function store(Request $request)
    {
        $request->validate([
            'no_bukti' => 'required',
            'tanggal' => 'required',
            'kode_supplier' => 'required',
            'kode_asal_pengajuan' => 'required',
            'jenis_transaksi' => 'required',
            'jatuh_tempo' => 'required_if:jenis_transaksi,K',
            'ppn' => 'required'
        ]);

        $kode_barang = $request->kode_barang_item;
        $jumlah = $request->jumlah_item;
        $harga = $request->harga_item;
        $penyesuaian = $request->penyesuaian_item;
        $kode_akun = $request->kode_akun_item;
        $keterangan = $request->keterangan_item;
        $kode_cabang = $request->kode_cabang_item;
        $kode_akun_cr = ['6-1', '6-2'];
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($request->tanggal, "pembelian");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            if (count($kode_barang) == 0) {
                return Redirect::back()->with(messageError('Detail Pembelian Masih Kosong'));
            }

            $total_pembelian = 0;
            $lastkodecr = '';
            for ($i = 0; $i < count($kode_barang); $i++) {
                $subtotal = toNumber($jumlah[$i]) * toNumber($harga[$i]) + toNumber($penyesuaian[$i]);
                $total_pembelian += $subtotal;

                //Jika Masuk kategori Cost Ratio
                if (in_array(substr($kode_akun[$i], 0, 3), $kode_akun_cr) && !empty($kode_cabang[$i])) {
                    $bulan_cr = date("m", strtotime($request->tanggal));
                    $tahun_cr = date("y", strtotime($request->tanggal));
                    $kode = "CR" . $bulan_cr . $tahun_cr;
                    $costratio = Costratio::select('kode_cr')
                        ->whereRaw('LEFT(kode_cr,6) ="' . $kode . '"')
                        ->orderBy('kode_cr', 'desc')
                        ->first();


                    if (empty($lastkodecr)) {
                        $last_kode_cr = $costratio != null ? $costratio->kode_cr : '';
                    } else {
                        $last_kode_cr = $lastkodecr;
                    }
                    $kode_cr = buatkode($last_kode_cr, "CR" . $bulan_cr . $tahun_cr, 4);
                    $barangpembelian = Barangpembelian::where('kode_barang', $kode_barang[$i])->first();
                    $data_costratio[] = [
                        'kode_cr' => $kode_cr,
                        'tanggal' => $request->tanggal,
                        'kode_akun' => $kode_akun[$i],
                        'keterangan'   => "Pembelian " . $barangpembelian->nama_barang . "(" . $jumlah[$i] . ")",
                        'kode_cabang'  => $kode_cabang[$i],
                        'kode_sumber' => 4,
                        'jumlah' => $subtotal
                    ];

                    $lastkodecr = $kode_cr;
                } else {
                    $kode_cr = NULL;
                }


                $detail[] = [
                    'no_bukti' => $request->no_bukti,
                    'kode_barang' => $kode_barang[$i],
                    'jumlah' => toNumber($jumlah[$i]),
                    'harga' => toNumber($harga[$i]),
                    'penyesuaian' => toNumber($penyesuaian[$i]),
                    'kode_akun' => $kode_akun[$i],
                    'keterangan' => $keterangan[$i],
                    'kode_cabang' => $kode_cabang[$i],
                    'kode_transaksi' => 'PMB',
                    'kode_cr' => $kode_cr
                ];
            }


            //Insert Data pembelian
            Pembelian::create([
                'no_bukti' => $request->no_bukti,
                'tanggal' => $request->tanggal,
                'kode_supplier' => $request->kode_supplier,
                'kode_asal_pengajuan' => $request->kode_asal_pengajuan,
                'jenis_transaksi' => $request->jenis_transaksi,
                'jatuh_tempo' => $request->jenis_transaksi == "K" ? $request->jatuh_tempo : $request->tanggal,
                'ppn' => $request->ppn,
                'kategori_transaksi' => $request->kategori_transaksi,
                'kode_akun' => $request->kode_asal_pengajuan == 'GDB' ? '2-1300' : '2-1200',
                'id_user' => auth()->user()->id
            ]);


            $timestamp = Carbon::now();
            //Insert Detail Pembelian
            Detailpembelian::insert($detail);

            //Jika Ada Data Pembleian Yang Masuk Kategori Cost Ratio
            if (!empty($data_costratio)) {

                foreach ($data_costratio as &$record) {
                    $record['created_at'] = $timestamp;
                    $record['updated_at'] = $timestamp;
                }
                Costratio::insert($data_costratio);
            }

            //Insert Kontrabon Jika Jenis Transaksi Tunai
            if ($request->jenis_transaksi == 'T') {
                $no_kontrabon = "T" . date('dmY', strtotime($request->tanggal)) . rand(10, 99);
                Kontrabonpembelian::create([
                    'no_kontrabon' => $no_kontrabon,
                    'tanggal' => $request->tanggal,
                    'kode_supplier' => $request->kode_supplier,
                    'kategori' => 'TN',
                    'jenis_bayar' => 'TN',
                    'id_user' => auth()->user()->id,
                    'status' => 0,

                ]);

                Detailkontrabonpembelian::create([
                    'no_kontrabon' => $no_kontrabon,
                    'no_bukti' => $request->no_bukti,
                    'jumlah' => $total_pembelian,
                    'keterangan' => 'tunai'
                ]);
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function destroy($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        DB::beginTransaction();
        try {
            $pembelian = Pembelian::where('no_bukti', $no_bukti)->first();
            $detailpembelian = Detailpembelian::where('no_bukti', $no_bukti)->get();
            $cektutuplaporan = cektutupLaporan($pembelian->tanggal, "pembelian");
            $cekkontrabonpembeliansudahbayar = Detailkontrabonpembelian::leftJoin('pembelian_historibayar', 'pembelian_kontrabon_detail.no_kontrabon', '=', 'pembelian_historibayar.no_kontrabon')
                ->whereNotNull('pembelian_historibayar.no_kontrabon')
                ->where('pembelian_kontrabon_detail.no_bukti', $no_bukti)
                ->count();


            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            if ($cekkontrabonpembeliansudahbayar >  0) {
                return Redirect::back()->with(messageError('Data Tidak Dapat DiHapus, Karena Memiliki Kontrabon Yang Sudah Dibayar'));
            }



            //Hapus Cost Ratio
            foreach ($detailpembelian as $d) {
                if (!empty($d->kode_cr)) {
                    Costratio::where('kode_cr', $d->kode_cr)->delete();
                }
            }

            //List Kontrabon Pembelian
            $kontrabonpembelian = Detailkontrabonpembelian::where('no_bukti', $no_bukti)->get();
            foreach ($kontrabonpembelian as $d) {
                //Hapus Detail Kontrabon Pembelian
                Detailkontrabonpembelian::where('no_bukti', $no_bukti)->where('no_kontrabon', $d->no_kontrabon)->delete();
                $cekdetailkontrabon = Detailkontrabonpembelian::where('no_kontrabon', $d->no_kontrabon)->count();
                if (empty($cekdetailkontrabon)) {
                    Kontrabonpembelian::where('no_kontrabon', $d->no_kontrabon)->delete();
                }
            }
            Pembelian::where('no_bukti', $no_bukti)->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $pmb = new Pembelian();
        $data['pembelian'] = $pmb->getPembelian(no_bukti: $no_bukti)->first();

        $data['detail'] = Detailpembelian::select('pembelian_detail.*', 'nama_barang', 'nama_akun')
            ->join('pembelian_barang', 'pembelian_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->join('coa', 'pembelian_detail.kode_akun', '=', 'coa.kode_akun')
            ->where('pembelian_detail.no_bukti', $no_bukti)
            ->where('pembelian_detail.kode_transaksi', 'PMB')
            ->select('pembelian_detail.*', 'pembelian_barang.nama_barang', 'coa.nama_akun')
            ->get();

        $data['potongan'] = Detailpembelian::select('pembelian_detail.*', 'nama_barang')
            ->join('pembelian_barang', 'pembelian_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->where('no_bukti', $no_bukti)
            ->where('pembelian_detail.kode_transaksi', 'PNJ')
            ->get();

        $data['supplier'] = Supplier::orderBy('nama_supplier')->get();
        $data['asal_ajuan'] = config('pembelian.list_asal_pengajuan');
        $data['coa'] = Coadepartemen::where('kode_dept', 'PMB')
            ->join('coa', 'coa_departemen.kode_akun', '=', 'coa.kode_akun')
            ->orderBy('coa_departemen.kode_akun')
            ->get();

        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        return view('pembelian.edit', $data);
    }


    public function createpotongan()
    {
        $data['coa'] = Coadepartemen::where('kode_dept', 'PMB')
            ->join('coa', 'coa_departemen.kode_akun', '=', 'coa.kode_akun')
            ->orderBy('coa_departemen.kode_akun')
            ->get();

        return view('pembelian.createpotongan', $data);
    }
}
