<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Coadepartemen;
use App\Models\Detailkontrabonpembelian;
use App\Models\Detailpembelian;
use App\Models\Pembelian;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

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
}
